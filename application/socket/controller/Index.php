<?php
namespace app\socket\controller;  
use Workerman\Worker;
use app\index\controller\Common;
use think\Db;
require_once ROOT_PATH.'/vendor/workerman/workerman/Autoloader.php';

class Index extends Common{
    static $global_uid = 0;
	public function index(){
    global $text_worker;
		// 创建一个文本协议的Worker监听2347接口
    $text_worker = new Worker("websocket://127.0.0.1:2347");
    // 只启动1个进程，这样方便客户端之间传输数据
    $text_worker->count = 1;
    $text_worker->onConnect = function($connection){
        global $global_uid;
        // 为这个链接分配一个uid
        $connection->uid = ++$global_uid; 
    };
    $text_worker->onMessage = function($connection, $buffer){
        global $text_worker;
        $data = json_decode($buffer,true);
        $data['price']+=0;
        $UB = Db::name('u-b');
        //买单委托信息
        $DEAL_B = Db::name('deal_b');
        //卖单委托信息
        $DEAL_S = Db::name('deal_s');
        //$keep为交易未完成的量
        $keep = $data['number_no'];
        $uid = input('session.uid');
        $rand = rand(10000,99999);
        //挂单信息
        $data1 = [
            'bid'=>$data['bid'],
            'number'=>$data['number_no'],
            'number_no'=>$data['number_no'],
            'addtime'=>time(),
            'uid'=>$uid,
            'price'=>$data['price'],    
                ];  
        //判断是挂买单还是挂卖单
        switch ($data['type']) {
            case '0':
                //0：表示买单
                //再次判断是否有足够的钱去挂买单
                //$money为用户可用资金
                $money = DB::name('user')->where('uid',$uid)->value('money');
                //挂买单的总共冻结资金
                $smoney = $data['price']*$data['number_no'];
                 //使用BCmath比较
                if(bccomp($smoney,$money,2)===1){
                    //                  
                    break;
                }
                Db::startTrans();
                try{
                    $nid = $DEAL_B->insertGetId($data1);
                    DB::name('user')->where('uid',$uid)->setDec('money',$smoney);
                    // 提交事务
                    Db::commit();    
                }catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                }
                //查询符合条件的卖单
                $where = [
                    'bid'=>$data['bid'],
                    'status'=>0,
                    'price'=>$data['price']
                ];
                // $list = $DEAL_S->field('id,bid,price,number_no,number,uid')->where($where)->order('addtime asc')->select();
                $list = Db::query("SELECT `id`,`bid`,`price`,`number_no`,`number`,`uid` FROM `s_deal_s` WHERE  `bid` = ".$data['bid']."  AND `status` = 0  AND `price` = ".$data['price']." ORDER BY addtime asc");
                //判断是否存在
                if($list){
                    //若符合条件的卖单存在
                    foreach ($list as $k => $v) {
                        //为剩余需求量
                        $noNumber = $keep;
                        //与卖单对接后剩余需求量
                        $keep = bcsub($keep,$v['number_no'],3);
/*-------------------------------------------------------------------------------------------*/  
                        if($keep<=0){
                            //小于0，表示买单可以完成交易,满足需求量
                            //启动事务
                            Db::startTrans();
                            try{
                                //更新买单的委托单
                                $data2 = [
                                    'status'=>1,
                                    'number_no'=>0,
                                    'id'=>$nid
                                ];
                                $DEAL_B->update($data2);
                                //更新卖家的金额
                                $mmoney = bcmul($noNumber,$data['price'],3);
                                Db::name('user')->where('uid',$v['uid'])->setInc('money',$mmoney);
                                //添加或更新买家的币数
                                $up = ['uid'=>$uid,'bid'=>$data['bid']];
                                $count = $UB->where($up)->count();
                                if($count){
                                    $UB->where($up)->setInc('number',$noNumber);
                                }else{
                                    $up['number'] = $noNumber;
                                    $UB->insert($up);
                                }
                                //更新卖单的委托单
                                $status = $keep==0?1:0;
                                $data3 = [
                                    'id'=>$v['id'],
                                    'status'=>$status,
                                    'number_no'=>abs($keep),
                                ];
                                $DEAL_S->update($data3);
                                //添加到交易记录
                                 $deal = [
                                    'uid'=>$uid,
                                    'fuid'=>$v['uid'],
                                    'number'=>$noNumber,
                                    'bid'=>$data['bid'],
                                    'addtime'=>time(),
                                    'money'=>$data['price'],
                                    'status'=>1,
                                    'type'=>3, //这里的3表示买入
                                    'oid'=>date('Ymd',time()).$uid.$rand
                                 ];
                                 Db::name('mrecord')->insert($deal);
                                 Db::commit(); 
                            } catch (\Exception $e) {
                                // 回滚事务
                                Db::rollback();
                                break;
                            }
                            //单独发资产给当前链接
                            $mreturn = [
                                'number_no'=>$data['number_no'],
                                'number'=>$data['number_no'],
                                'price'=>$data['price'],
                                'type'=>3
                            ];
                            $mreturn = json_encode($mreturn);
                            $connection->send($mreturn);

                            //将买卖委托信息发送给前台
                            $return = [
                                'b'=>[
                                    'price'=>$data['price'],
                                    'number'=>$data['number_no'],
                                    'number_no'=>0,
                                    'id'=>$nid,
                                ],
                                's'=>[
                                    'id'=>$v['id'],
                                    'number_no'=>abs($keep),
                                ],
                                'time'=>date('H:i:s',time()),
                                'type'=>10,
                            ];
                            $return = json_encode($return);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return);
                                }
                            //买单完成 退出
                            break;
/*-------------------------------------------------------------------------------------------*/ 
                        }else{
                            //买单完成不了，该卖单结束
                            Db::startTrans();
                            try{
                                //更新该卖单委托信息的状态
                                $data4 = [
                                    'id'=>$v['id'],
                                    'status'=>1,
                                    'number_no'=>0,
                                ];
                                $DEAL_S->update($data4);

                                //增加卖家的钱
                                $mmoney = bcmul($v['price'],$v['number_no'],3);
                                Db::name('user')->where('uid',$v['uid'])->setInc('money',$mmoney);
                                //扣除 买家金额
                                
                                //增加买家的币种
                                $up = ['uid'=>$uid,'bid'=>$data['bid']];
                                $count = $UB->where($up)->count();
                                if($count){
                                    $UB->where($up)->setInc('number',$v['number_no']);
                                }else{
                                    $up['number'] = $v['number_no'];
                                    $UB->insert($up);
                                }
                                 //添加到交易记录
                                 $deal1 = [
                                    'uid'=>$uid,
                                    'fuid'=>$v['uid'],
                                    'number'=>$v['number_no'],
                                    'bid'=>$data['bid'],
                                    'addtime'=>time(),
                                    'money'=>$data['price'],
                                    'status'=>1,
                                    'type'=>3, //这里的3表示买入
                                    'oid'=>date('Ymd',time()).$uid.$rand
                                 ];
                                 Db::name('mrecord')->insert($deal1);
                                // 提交事务
                                Db::commit();    
                            } catch (\Exception $e) {
                                // 回滚事务
                                Db::rollback();
                            }
                             //给前台返回信息,更新卖单数量
                            $return1 = [
                                'id'=>$v['id'],
                                'number_no'=>0,
                                'type'=>2,
                                
                            ];
                            $return1 = json_encode($return1);
                                foreach($text_worker->connections as $conn)
                                    {   
                                        $conn->send($return1);
                                    }
                        }
/*-------------------------------------------------------------------------------------------*/ 
                    }//循环结束
                   if($keep>0){              
                        //更新买单
                        $data5 = [
                            'status'=>0,
                            'number_no'=>$keep,
                            'id'=>$nid
                            ];
                            $DEAL_B->update($data5);
                        //单独发资产给当前链接
                        $mreturn = [
                            'number_no'=>$keep,
                            'number'=>$data['number_no'],
                            'price'=>$data['price'],
                            'type'=>9,
                            'time'=>date('H:i:s',time()),
                        ];
                        $mreturn = json_encode($mreturn);
                        $connection->send($mreturn);
                        //将委托信息返回给前台价格，数量，剩余
                            $return = [
                                'price'=>$data['price'],
                                'number'=>$data['number_no'],
                                'number_no'=>$keep,
                                'id'=>$nid,
                                'type'=>0
                            ];
                            $return = json_encode($return);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return);
                                }
                   }
                   break;
/*-------------------------------------------------------------------------------------------*/  
                }else{
                    //若卖单不存在
                    //单独给连接者发送资金修改的信息  
                    $mreturn = [
                            'number_no'=>$data['number_no'],
                            'number'=>$data['number_no'],
                            'price'=>$data['price'],
                            'type'=>9
                        ];
                        $mreturn = json_encode($mreturn);
                        $connection->send($mreturn);
                    //这里还要返回新增的买单委托信息
                    $return = [
                        'price'=>$data['price'],
                        'number_no'=>$data['number_no'],
                        'number'=>$data['number_no'],
                        'id'=>$nid,
                        'type'=>0,
                    ];
                    $return = json_encode($return);
                    foreach($text_worker->connections as $conn)
                        {   
                            $conn->send($return);
                        }
                }
            break;
/*-------------------------------------------------------------------------------------------*/ 
            case '1':
                //1表示卖单操作
                //再次判断用户是否有足够的币种去挂卖单
                $scurrency = $UB->where(['uid'=>$uid,'bid'=>$data['bid']])->value('number');
                if(bccomp($data['number_no'],$scurrency,3)===1){
                    break;
                }
                //开启事务
                Db::startTrans();
                try{
                    //添加到卖单委托
                    $nid = $DEAL_S->insertGetId($data1);
                    //扣掉货币
                    $UB->where(['uid'=>$uid,'bid'=>$data['bid']])->setDec('number',$data['number_no']);
                    // 提交事务
                    Db::commit();    
                }catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                }
                //查询符合条件的买单
                $where = [
                    'bid'=>$data['bid'],
                    'status'=>0,
                    'price'=>$data['price']
                ];
                // $list = $DEAL_B->field('id,bid,price,number_no,number,uid')->where($where)->order('addtime asc')->select();
                 $list = Db::query("SELECT `id`,`bid`,`price`,`number_no`,`number`,`uid` FROM `s_deal_b` WHERE  `bid` = ".$data['bid']."  AND `status` = 0  AND `price` = ".$data['price']." ORDER BY addtime asc");

                //判断是否存在符合条件的买单
                if($list){
                    foreach ($list as $k => $v) {
                        $noNumber = $keep;
                        $keep = bcsub($keep,$v['number_no'],3);
                        if($keep<=0){
                            //小于0，表示卖单可以完成交易
                            //启动事务
                            Db::startTrans();
                            try{
                                //更新卖单的委托单
                                 $data2 = [
                                    'status'=>1,
                                    'number_no'=>0,
                                    'id'=>$nid
                                ];
                                $DEAL_S->update($data2);
                                //更新卖家的金额
                                $mmoney = bcmul($noNumber,$data['price'],3);
                                Db::name('user')->where('uid',$uid)->setInc('money',$mmoney);
                                //添加或更新买家的币数
                                $up = ['uid'=>$v['uid'],'bid'=>$data['bid']];
                                $count = $UB->where($up)->count();
                                if($count){
                                    $UB->where($up)->setInc('number',$noNumber);
                                }else{
                                    $up['number'] = $noNumber;
                                    $UB->insert($up);
                                }
                                //更新买家的委托单
                                $status = $keep==0?1:0;
                                $data3 = [
                                    'id'=>$v['id'],
                                    'status'=>$status,
                                    'number_no'=>abs($keep),
                                ];
                                $DEAL_B->update($data3);
                                //添加到交易记录
                                 $deal = [
                                    'uid'=>$v['uid'],
                                    'fuid'=>$uid,
                                    'number'=>$noNumber,
                                    'bid'=>$data['bid'],
                                    'addtime'=>time(),
                                    'money'=>$data['price'],
                                    'status'=>1,
                                    'type'=>4, //这里的4表示卖出
                                    'oid'=>date('Ymd',time()).$uid.$rand
                                ];
                                Db::name('mrecord')->insert($deal);
                                Db::commit();
                            } catch (\Exception $e) {
                                // 回滚事务
                                Db::rollback();
                                break;
                            }
                            //单独发送资产给当前链接
                                //单独发资产给当前链接
                            $mreturn = [
                                'number_no'=>$data['number_no'],
                                'number'=>$data['number_no'],
                                'price'=>$data['price'],
                                'type'=>4
                            ];
                            $mreturn = json_encode($mreturn);
                            $connection->send($mreturn);
                            //将买卖委托信息发送给前台
                            $return = [
                                's'=>[
                                    'price'=>$data['price'],
                                    'number'=>$data['number_no'],
                                    'number_no'=>0,
                                    'id'=>$nid,
                                ],
                                'b'=>[
                                    'id'=>$v['id'],
                                    'number_no'=>abs($keep),
                                ],
                                'time'=>date('H:i:s',time()),
                                'type'=>11,
                            ];
                            $return = json_encode($return);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return);
                                }
                            //卖单完成 退出
                            break;
                        }else{
                            //循环的其中买单完成
                            Db::startTrans();
                            try{
                                //更新该买单委托信息的状态
                                $data4 = [
                                    'id'=>$v['id'],
                                    'status'=>1,
                                    'number_no'=>0,
                                ];
                                $DEAL_B->update($data4);

                                 //增加买家的该币种数量
                                $up = ['uid'=>$v['uid'],'bid'=>$data['bid']];
                                $count = $UB->where($up)->count();
                                if($count){
                                    $UB->where($up)->setInc('number',$v['number_no']);
                                }else{
                                    $up['number'] = $v['number_no'];
                                    $UB->insert($up);
                                }
                                //给卖家加钱
                                $mmoney = bcmul($v['price'],$v['number_no'],3);
                                Db::name('user')->where('uid',$uid)->setInc('money',$mmoney);
                                //添加到交易记录
                                 $deal1 = [
                                    'uid'=>$v['uid'],
                                    'fuid'=>$uid,
                                    'number'=>$v['number_no'],
                                    'bid'=>$data['bid'],
                                    'addtime'=>time(),
                                    'money'=>$data['price'],
                                    'status'=>1,
                                    'type'=>4, //这里的4表示卖出
                                    'oid'=>date('Ymd',time()).$uid.$rand
                                 ];
                                 Db::name('mrecord')->insert($deal1);
                                // 提交事务
                                Db::commit();    
                            } catch (\Exception $e) {
                                // 回滚事务
                                Db::rollback();
                            }
                            //给前台返回信息,更新买单数量
                            $return1 = [
                                'id'=>$v['id'],
                                'number_no'=>0,
                                'type'=>2,
                            ];
                            $return1 = json_encode($return1);
                                foreach($text_worker->connections as $conn)
                                    {   
                                        $conn->send($return1);
                                    }
                        }
                    }//循环结束
                    if($keep>0){
                        //更新卖单
                         $data5 = [
                            'status'=>0,
                            'number_no'=>$keep,
                            'id'=>$nid
                            ];
                            $DEAL_S->update($data5);
                        //单独发资产给当前链接
                        $mreturn = [
                            'number_no'=>$keep,
                            'number'=>$data['number_no'],
                            'price'=>$data['price'],
                            'type'=>8,
                            'time'=>date('H:i:s',time()),
                        ];
                        $mreturn = json_encode($mreturn);
                        $connection->send($mreturn);
                        //将卖单委托信息返回给前台价格，数量，剩余
                            $return = [
                                'price'=>$data['price'],
                                'number'=>$data['number_no'],
                                'number_no'=>$keep,
                                'id'=>$nid,
                                'type'=>1,
                            ];
                            $return = json_encode($return);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return);
                                }                    
                    }
                break;
                }else{
                    //若不存在符合条件的买单
                    //单独给连接者发送资金修改的信息  
                    $mreturn = [
                            'number_no'=>$data['number_no'],
                            'number'=>$data['number_no'],
                            'price'=>$data['price'],
                            'type'=>8
                        ];
                        $mreturn = json_encode($mreturn);
                        $connection->send($mreturn);
                    //返回新增卖单的委托信息
                    $return = [
                        'price'=>$data['price'],
                        'number_no'=>$data['number_no'],
                        'number'=>$data['number_no'],
                        'id'=>$nid,
                        'type'=>1,
                    ];
                    $return = json_encode($return);
                    foreach($text_worker->connections as $conn)
                        {   
                            $conn->send($return);
                        }
                }
                break;
            default:
                break;
        }
    };
    // $text_worker->onClose = function($connection){
    // };
    Worker::runAll();
	}
    public function aa(){
        echo 1;
    }
}
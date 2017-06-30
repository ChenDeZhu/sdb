<?php
namespace app\socket\controller;  
use Workerman\Worker;
use app\index\controller\Common;
use think\Db;
require_once __DIR__ .'/../../../vendor/workerman/workerman/Autoloader.php';

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
        $UB = Db::name('u-b');
        $DEAL = Db::name('deal');
        //$keep为交易未完成的量
        $keep = $data['number_no'];
        $uid = input('session.uid');

        //挂单信息
        $data1 = [
            'bid'=>$data['bid'],
            'number'=>$data['number_no'],
            'number_no'=>$data['number_no'],
            'addtime'=>time(),
            'uid'=>1,
            'price'=>$data['price'],
            'type'=>$data['type']
                ];  
        //判断是挂买单还是挂卖单
        switch ($data['type']) {
            case '0':
                //挂买单
                $money = db('user')->where('uid',$uid)->value('money');
                $smoney = $data['price']*$data['number_no'];
                if($money < $smoney){
                    // $connection->send('余额不够');
                    break;
                }
                 //挂单,冻结购买的金额或卖出的货币
                // 启动事务
                Db::startTrans();
                try{
                    //添加自己的委托信息到记录表
                    $nid = $DEAL->insertGetId($data1);
                    //扣除金额
                    DB::name('user')->where('uid',$uid)->setDec('money',$smoney);           
                    // 提交事务
                    Db::commit();    
                } catch (\Exception $e) {
                    // 回滚事务              
                    Db::rollback();             
                }
                //通过价格与币种类获取 符合条件的卖家委托单
                $where = [
                    'bid'=>$data['bid'],
                    'type'=>1,
                    'status'=>0,
                    'price'=>$data['price']
                ];
                $list = $DEAL->field('id,bid,price,number_no,number,uid')->where($where)->order('addtime asc')->select();
                //如果存在，则更新买卖委托信息
                if($list){
                    foreach ($list as $k => $v) {
                        //如果交易量小于等于卖单的数量
                        $keep = $keep - $v['number_no'] ;
                        if($keep<=0){
                            //自己的委托交易完成,进行更新操作
                            $data2 = [
                                'status'=>1,
                                'number_no'=>0,
                                'id'=>$nid
                            ];
                        $DEAL->update($data2);
                        
                        // 启动事务
                        Db::startTrans();
                        try{
                            //一次性买单交易完成
                            //更新卖家的钱
                            
                            Db::name('user')->where('uid',$v['uid'])->setInc('money',$smoney);
                            //买家添加或更新币种
                            $up = ['uid'=>$uid,'bid'=>$data['bid']];
                            $count = $UB->where($up)->count();
                            if($count){
                                $UB->where($up)->setInc('number',$data['number_no']);
                            }else{
                                $up['number'] = $data['number_no'];
                                $UB->insert($up);
                            }
                             //更新该委托单的状态
                            $status = $keep==0?1:0;
                            $data3 = [
                                'id'=>$v['id'],
                                'status'=>$status,
                                'number_no'=>abs($keep),
                            ];
                             $DEAL->update($data3);

                             //添加到交易记录
                             $deal = [
                                'uid'=>$uid,
                                'fuid'=>$v['uid'],
                                'number'=>$data['number_no'],
                                'bid'=>$data['bid'],
                                'addtime'=>time(),
                                'money'=>$data['price'],
                                'status'=>1,
                                'type'=>3 //这里的3表示买入
                             ];
                             Db::name('mrecord')->insert($deal);

                            // 提交事务
                            Db::commit();    
                        } catch (\Exception $e) {
                            // 回滚事务
                            Db::rollback();
                            break;
                        }

                        //将资产信息发送给前台
                        $mreturn = [
                            'rmoney'=>$smoney,//总花费
                            'type'=>3,
                        ];
                        $mreturn = json_encode($mreturn);
                        $connection->send($mreturn);


                        //改变前台卖家委托单的状态
                         $return1 = [
                            'id'=>$v['id'],
                            'number_no'=>abs($keep),
                            'type'=>2//这里的2表示前台更新操作
                        ];
                        $return1 = json_encode($return1);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return1);
                                }
                       
                        //将买家委托信息返回给前台价格，数量，剩余
                            $return = [
                                'price'=>$data['price'],
                                'number'=>$data['number_no'],
                                'number_no'=>0,
                                'id'=>$nid,
                                'type'=>0
                            ];
                            $return = json_encode($return);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return);
                                }
                         
                             break;
                        }else{
                            //该买单大于该卖单数量
                        //////////////
                        // 添加到交易记录表 //
                        //待定        //
                        //////////////
                        Db::startTrans();
                        try{
                            
                            //更新该委托信息的状态
                            $data4 = [
                                'id'=>$v['id'],
                                'status'=>1,
                                'number_no'=>0,
                            ];
                             $DEAL->update($data4);

                            //增加卖家的钱
                            $mmoney = $v['price']*$v['number_no'];
                            Db::name('user')->where('uid',$v['uid'])->setInc('money',$mmoney);
                            //增加买家的币种
                            $up = ['uid'=>$uid,'bid'=>$data['bid']];
                            $count = $UB->where($up)->count();
                            if($count){
                                $UB->where($up)->setInc('number',$v['number_no']);
                            }else{
                                $up['number'] = $data['number_no'];
                                $UB->insert($up);
                            }

                            // 提交事务
                            Db::commit();    
                        } catch (\Exception $e) {
                            // 回滚事务
                            Db::rollback();
                        }

                        //给前台返回信息
                        $return1 = [
                            'id'=>$v['id'],
                            'number_no'=>0,
                            'type'=>2
                        ];
                        $return1 = json_encode($return1);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return1);
                                }
                        }
                        
                    }
                    //循环结束以后,买单委托数量还有
                   if($keep>0){
                    //更新买单
                     $data5 = [
                        'status'=>0,
                        'number_no'=>$keep,
                        'id'=>$nid
                        ];
                        $DEAL->update($data5);
                     //将委托信息返回给前台价格，数量，剩余
                        $return = [
                            'price'=>$data['price'],
                            'number'=>$data['number_no'],
                            'number_no'=>$keep,
                            'id'=>$nid,
                            'type'=>$data['type']
                        ];
                        $return = json_encode($return);
                        foreach($text_worker->connections as $conn)
                            {   
                                $conn->send($return);
                            }
                   }
                   return;
                }else{
                    //如果不存在,不做更新操作
                    //返回冻结金额
                    $mreturn = [
                            'rmoney'=>$smoney,//总花费
                            'type'=>4,
                        ];
                        $mreturn = json_encode($mreturn);
                        $connection->send($mreturn);
                    //返回前台数据
                    $return = [
                        'price'=>$data['price'],
                        'number_no'=>$data['number_no'],
                        'number'=>$data['number_no'],
                        'id'=>$nid,
                        'type'=>$data['type']
                    ];
                    $return = json_encode($return);
                    foreach($text_worker->connections as $conn)
                        {   
                            $conn->send($return);
                        }
                    }
                break;
            case '1':
                $smoney = $data['price']*$data['number_no'];
                //挂卖单
                //再次判断是否有足够的该币种
                
                //挂单,冻结购买的金额或卖出的货币    
                // 启动事务
                
                Db::startTrans();
                try{
                    //添加自己的委托信息到记录表
                    $nid = $DEAL->insertGetId($data1);
                    //扣除货币数量
                    Db::name('u-b')->where(['uid'=>$uid,'bid'=>$data['bid']])->setDec('number',$data['number_no']);
                    // 提交事务
                    Db::commit();    
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    break;
                }
                //通过价格与币种类获取 符合条件的买家委托单
                $where = [
                    'bid'=>$data['bid'],
                    'type'=>0,
                    'status'=>0,
                    'price'=>$data['price']
                ];
                $list = $DEAL->field('id,bid,price,number_no,number,uid')->where($where)->order('addtime asc')->select();
                
                //如果存在，则更新买卖委托信息
                if($list){
                    foreach ($list as $k => $v) {
                        //如果交易量小于等于卖单的数量
                        $keep = $keep - $v['number_no'] ;
                        if($keep<=0){
                            //自己的委托交易完成,进行更新操作
                            $data2 = [
                                'status'=>1,
                                'number_no'=>0,
                                'id'=>$nid
                            ];
                        $DEAL->update($data2);
                       
                        
                        // 启动事务
                        Db::startTrans();
                        try{
                            //买者币种数量增加
                            //若买着有这个币种，这增加，没有 则更新
                            $up = ['uid'=>$v['uid'],'bid'=>$data['bid']];
                            $count = $UB->where($up)->count();
                            if($count){
                                $UB->where($up)->setInc('number',$data['number_no']);
                            }else{
                                $up['number'] = $data['number_no'];
                                $UB->insert($up);
                            }
                            //卖者金额增加
                            Db::name('user')->where('uid',$uid)->setInc('money',$smoney);
                             //更新该委托单的状态
                            $status = $keep==0?1:0;
                            $data3 = [
                                'id'=>$v['id'],
                                'status'=>$status,
                                'number_no'=>abs($keep),
                            ];
                            $DEAL->update($data3);            
                            //添加到交易记录               
                            // 提交事务
                            Db::commit();    
                        } catch (\Exception $e) {
                            // 回滚事务
                            Db::rollback();
                        }
                        //将资产信息发送给前台
                        $mreturn = [
                            'rcurrency'=>$data['number_no'],//总花费
                            'type'=>5,
                        ];
                        $mreturn = json_encode($mreturn);
                        $connection->send($mreturn);

                        //改变前台买家委托单的状态
                         $return1 = [
                            'id'=>$v['id'],
                            'number_no'=>abs($keep),
                            'type'=>2//这里的2表示前台更新操作
                        ];
                        $return1 = json_encode($return1);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return1);
                                }
                       
                        //将买家委托信息返回给前台价格，数量，剩余
                            $return = [
                                'price'=>$data['price'],
                                'number'=>$data['number_no'],
                                'number_no'=>0,
                                'id'=>$nid,
                                'type'=>1
                            ];
                            $return = json_encode($return);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return);
                                }
                         
                            break;
                        }else{
                        //////////////
                        // 添加到交易记录表 //
                        //待定        //
                        //////////////
                        ///          
                        //给前台返回信息
                        $return1 = [
                            'id'=>$v['id'],
                            'number_no'=>0,
                            'type'=>2//表示更新前台委托信息
                        ];
                        $return1 = json_encode($return1);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return1);
                                }

                        //更新该委托信息的状态
                        $data4 = [
                            'id'=>$v['id'],
                            'status'=>1,
                            'number_no'=>0,
                        ];
                         $DEAL->update($data4);
                        }
                        //
                    }
                    //循环结束以后,买单委托数量还有
                   if($keep>0){
                    //更新买单
                     $data5 = [
                        'status'=>0,
                        'number_no'=>$keep,
                        'id'=>$nid
                        ];
                        $DEAL->update($data5);
                     //将委托信息返回给前台价格，数量，剩余
                        $return = [
                            'price'=>$data['price'],
                            'number'=>$data['number_no'],
                            'number_no'=>$keep,
                            'id'=>$nid,
                            'type'=>$data['type']
                        ];
                        $return = json_encode($return);
                        foreach($text_worker->connections as $conn)
                            {   
                                $conn->send($return);
                            }
                   }
                   return;
                }else{
                    //如果不存在,不做更新操作
                    //返回冻结币种数量
                    $mreturn = [
                            'rcurrency'=>$data['number_no'],//总花费
                            'type'=>6,
                        ];
                        $mreturn = json_encode($mreturn);
                        $connection->send($mreturn);
                    //返回前台数据
                    $return = [
                        'price'=>$data['price'],
                        'number_no'=>$data['number_no'],
                        'number'=>$data['number_no'],
                        'id'=>$nid,
                        'type'=>$data['type']
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
   
}
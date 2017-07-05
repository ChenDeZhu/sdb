<?php
namespace app\socket\controller;  
use Workerman\Worker;
use app\index\controller\Common;
use think\Db;
use think\cache\driver\Redis;
require_once ROOT_PATH.'/vendor/workerman/workerman/Autoloader.php';


class Index extends Common{
    static $global_uid = 0;
    public function index(){
        global $text_worker;
        global $redis;
    		// 创建一个文本协议的Worker监听2347接口
        $text_worker = new Worker("websocket://127.0.0.1:2347");
        // 只启动1个进程，这样方便客户端之间传输数据
        $text_worker->count = 1;
        $redis = new Redis();
                // 进程启动时
        $text_worker->onWorkerStart = function() {
            
        };



        $text_worker->onConnect = function($connection){
            global $global_uid;
            // 为这个链接分配一个uid
            $connection->uid = ++$global_uid;
            
        };


        $text_worker->onMessage = function($connection, $buffer){
            global $text_worker;
            global $rand;
            global $redis;
            $data = json_decode($buffer,true);
            $data['price'] = bcadd($data['price'],0,2);
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
                    $list = $this->findList($data);
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
                                $this->doneAdeal($data,$v,$nid,$noNumber,$keep);
                                //单独发资产给当前链接   
                                $mreturn = $this->dealRself($data,3);
                                $connection->send($mreturn);
                                //将买卖委托信息发送给前台
                                $return = $this->addinfo($data,$redis,10);
                                foreach($text_worker->connections as $conn)
                                    {   
                                        $conn->send($return);
                                    }
                                //买单完成 退出
                                break;
    /*-------------------------------------------------------------------------------------------*/ 
                            }else{
                                //买单完成不了，被动的单子结束
                                $this->doneBdeal($data,$v);   
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

                            $return = $this->addinfo($data,$redis,12,$keep);
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
                        

                        //添加委托信息
                        $return = $this->addinfo($data,$redis,0);
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
                    $scurrency = Db::name('u-b')->where(['uid'=>$uid,'bid'=>$data['bid']])->value('number');
                    if(bccomp($data['number_no'],$scurrency,3)===1){
                        break;
                    }
                    //开启事务
                    Db::startTrans();
                    try{
                        //添加到卖单委托
                        $nid = $DEAL_S->insertGetId($data1);
                        //扣掉货币
                        Db::name('u-b')->where(['uid'=>$uid,'bid'=>$data['bid']])->setDec('number',$data['number_no']);
                        // 提交事务
                        Db::commit();    
                    }catch (\Exception $e) {
                        // 回滚事务
                        Db::rollback();
                    }
                    //查询符合条件的买单
                    $list = $this->findList($data);
                    //判断是否存在符合条件的买单
                    if($list){
                        foreach ($list as $k => $v) {
                            $noNumber = $keep;
                            $keep = bcsub($keep,$v['number_no'],3);
                            if($keep<=0){
                                //小于0，表示卖单可以完成交易
                                $this->doneAdeal($data,$v,$nid,$noNumber,$keep);
                                //单独发送资产给当前链接
                                $mreturn = [
                                    'number_no'=>$data['number_no'],
                                    'number'=>$data['number_no'],
                                    'price'=>$data['price'],
                                    'type'=>4
                                ];
                                $mreturn = json_encode($mreturn);
                                $connection->send($mreturn);
                                //将买卖委托信息发送给前台
                                $return = $this->addinfo($data,$redis,11);
                                foreach($text_worker->connections as $conn)
                                    {   
                                        $conn->send($return);
                                    }
                                //卖单完成 退出
                                break;
                            }else{
                                //循环的其中被动买单完成
                                $this->doneBdeal($data,$v);   
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



                            $return = $this->addinfo($data,$redis,13,$keep);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return);
                                }       
                        }
                    break;
                    }else{
                        //若不存在符合条件的买单
                        //单独给连接者发送资金修改的信息  
                        $mreturn = $this->dealRself($data,8);
                        $connection->send($mreturn);
                        //返回新增卖单的委托信息
                        $return = $this->addinfo($data,$redis,1);
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
        
        Worker::runAll();
	}   
    /**
     * [findList 查询符合条件的委托信息]
     * @param  [array] $data [行为发起者的数据]
     * @return [array]       [符合条件的委托信息]
     */
    private function findList($data){
        //查询符合条件的卖单
        $where = [
            'bid'=>$data['bid'],
            'status'=>0,
            'price'=>$data['price']
        ];
        $db = $data['type'] == 0?"s_deal_s":"s_deal_b";
        // $list = $DEAL_S->field('id,bid,price,number_no,number,uid')->where($where)->order('addtime asc')->select();
        $list = Db::query("SELECT `id`,`bid`,`price`,`number_no`,`number`,`uid` FROM `".$db."` WHERE  `bid` = ".$data['bid']."  AND `status` = 0  AND `price` = ".$data['price']." ORDER BY addtime asc");
        return $list;
    }
    /**
     * [doneAdeal 发起者的委托信息完成]
     * @param  [array] $data [发起者的委托信息]
     * @param  [array] $v    [匹配到的被动委托信息]
     * @param  [int] $nid  [发起者的委托信息ID]
     * @param  [int] $noNumber  [剩余未完成的需求]
     * @param  [int] $keep  [与委托信息对接后剩余需求量]
     */
    private function doneAdeal($data,$v,$nid,$noNumber,$keep){
        global $rand;
        
        if($data['type'] == 0){
            $uid = input('session.uid');
            $vid = $v['uid'];
            $db1 = Db::name('deal_b');
            $db2 = Db::name('deal_s');
            $type = 3;
        }else{
            $uid = $v['uid'];
            $vid = input('session.uid');
            $db1 = Db::name('deal_s');
            $db2 = Db::name('deal_b');
            $type = 4;
        }
        Db::startTrans();
        try{  
            //更新卖单的委托单
             $data2 = [
                'status'=>1,
                'number_no'=>0,
                'id'=>$nid
            ];
            $db1->update($data2);
            //更新卖家的金额
            $mmoney = bcmul($noNumber,$data['price'],3);
            Db::name('user')->where('uid',$vid)->setInc('money',$mmoney);
            //添加或更新买家的币数
            $up = ['uid'=>$uid,'bid'=>$data['bid']];
            $count = Db::name('u-b')->where($up)->count();
            if($count){
                Db::name('u-b')->where($up)->setInc('number',$noNumber);
            }else{
                $up['number'] = $noNumber;
                Db::name('u-b')->insert($up);
            }
            //更新被动的委托单
            $status = $keep==0?1:0;
            $data3 = [
                'id'=>$v['id'],
                'status'=>$status,
                'number_no'=>abs($keep),
            ];
            $db2->update($data3);
            //添加到交易记录
             $deal = [
                'uid'=>$uid,
                'fuid'=>$vid,
                'number'=>$noNumber,
                'bid'=>$data['bid'],
                'addtime'=>time(),
                'money'=>$data['price'],
                'status'=>1,
                'type'=>$type, //这里的4表示卖出
                'oid'=>date('Ymd',time()).$uid.$rand
            ];
            Db::name('mrecord')->insert($deal);
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
    }

    /**
     * [doneBdeal 符合委托信息的被动单子完成]
     * @param  [array] $data [行为发起者的数据]
     * @param  [array] $v    [符合匹配的委托单数据]
     */
    private function doneBdeal($data,$v){
        global $rand;
        
        
        if($data['type'] == 0){
            $uid = input('session.uid');
            $vid = $v['uid'];
            $db = Db::name('deal_s');
            $type = '3';
        }else{
            $uid = $v['uid'];
            $vid = input('session.uid');
            $db = Db::name('deal_b');
            $type = '4';
        }
        Db::startTrans();
        try{
            //更新该卖单委托信息的状态
            $data4 = [
                'id'=>$v['id'],
                'status'=>1,
                'number_no'=>0,
            ];
            $db->update($data4);

            //增加卖家的钱
            $mmoney = bcmul($v['price'],$v['number_no'],3);
            Db::name('user')->where('uid',$vid)->setInc('money',$mmoney);

            //增加买家的币种
            $up = ['uid'=>$uid,'bid'=>$data['bid']];
            $count = Db::name('u-b')->where($up)->count();
            if($count){
                Db::name('u-b')->where($up)->setInc('number',$v['number_no']);
            }else{
                $up['number'] = $v['number_no'];
                Db::name('u-b')->insert($up);
            }
             //添加到交易记录
             $deal1 = [
                'uid'=>$uid,
                'fuid'=>$vid,
                'number'=>$v['number_no'],
                'bid'=>$data['bid'],
                'addtime'=>time(),
                'money'=>$data['price'],
                'status'=>1,
                'type'=>$type, //这里的3表示买入
                'oid'=>date('Ymd',time()).$uid.$rand
             ];
             Db::name('mrecord')->insert($deal1);
            // 提交事务
            Db::commit();    
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
    }


    
    /**
     * [dealRself 完成发起者的委托信息]
     * @param  [array] $data [行为发起者的数据]
     * @param  [int] $type [类型3：买入,4：卖出]
     * @return [string]       [json数据]
     */
    private function dealRself($data,$type){
        $mreturn = [
            'number_no'=>$data['number_no'],
            'number'=>$data['number_no'],
            'price'=>$data['price'],
            'type'=>$type
        ];
        $mreturn = json_encode($mreturn);
        return $mreturn;
    }

    /**
     * [addinfo 添加或更新委托信息]
     * @param  [array] $data  [行为发起者的数据]
     * @param  [object] $redis [redis实例]
     * @param  [int] $type  [类别]
     * @param  [float] $type  [委托信息剩余数量]
     * @return [string]       [更新的委托信息数据]
     */
    public function addinfo($data,$redis,$type,$keep=0){

        $buy = $redis->get('buy');
        $sale = $redis->get('sale');
        $data['number_no'] = bcadd($data['number_no'], 0,6);
        //新的委托信息
        $data1 = [
            'price'=>$data['price'],
            'number'=>($keep == 0?$data['number_no']:$keep),
        ];
        $j = 0;
        switch ($type) {
            case '0':
                
                $arrSort = [];
                //买单无符合条件的卖单
                foreach ($buy as $k => $v) {
                    if($v['price'] == $data['price']){
                        //如果该价格有需求，则更新数量   
                        $buy[$k]['number'] = bcadd($data['number_no'],$v['number'],6);
                        $j = 1;
                        break;
                    }
                }
                //若没有，则新增数量
                if($j==0){
                    array_push($buy,$data1);
                    $arrSort = [];
                    foreach($buy AS $k => $v){  
                        foreach($v AS $key=>$value){  
                            $arrSort[$key][$k] = $value;  
                        }  
                    }
                    array_multisort($arrSort['price'], constant('SORT_DESC'), $buy);
                }
                // unset($data[201]);
                break;
            case '1':
                foreach ($sale as $k => $v) {
                    if($v['price']==$data['price']){
                        //如果该价格有需求，则更新数量
                        $sale[$k]['number'] = bcadd($data['number_no'],$v['number'],6);
                        $j = 1;
                        break;
                    }
                }
                //若没有，则新增数量
                if($j==0){
                    array_push($sale,$data1);
                    $arrSort = [];
                    foreach($sale AS $k => $v){  
                        foreach($v AS $key=>$value){  
                            $arrSort[$key][$k] = $value;  
                        }  
                    }
                    array_multisort($arrSort['price'], constant('SORT_ASC'), $sale);
                }
                //unset($data[201]):'';
                break;
            case '10':
                foreach ($sale as $k => $v) {
                    if($v['price']==$data['price']){
                        if(bcsub($v['number'],$data['number_no'],6)==0){
                            unset($sale[$k]);
                        }else{
                            $sale[$k]['number'] = bcsub($v['number'],$data['number_no'],6);
                        }
                    }
                }
                break;
            case '11':
                foreach ($buy as $k => $v) {
                    if($v['price']==$data['price']){
                        if(bcsub($v['number'],$data['number_no'],6)==0){
                            unset($buy[$k]);
                        }else{
                            $buy[$k]['number'] = bcsub($v['number'],$data['number_no'],6);
                        }
                    }
                }
                break;

            case '12':
                //买单一部分部分完成
                array_push($buy,$data1);
                 $arrSort = [];
                    foreach($buy AS $k => $v){  
                        foreach($v AS $key=>$value){  
                            $arrSort[$key][$k] = $value;  
                        }  
                    }
                array_multisort($arrSort['price'], constant('SORT_DESC'), $buy);
                //清除卖单完成的委托信息
                foreach($sale as $k =>$v){
                    if($v['price'] == $data['price']){
                        unset($sale[$k]);
                    }
                }
                break;
            case '13':
                //卖单一部分部分完成
                array_push($sale,$data1);
                 $arrSort = [];
                    foreach($sale AS $k => $v){  
                        foreach($v AS $key=>$value){  
                            $arrSort[$key][$k] = $value;  
                        }  
                    }
                array_multisort($arrSort['price'], constant('SORT_ASC'), $sale);
                //清除卖单完成的委托信息
                foreach($buy as $k =>$v){
                    if($v['price'] == $data['price']){
                        unset($buy[$k]);
                    }
                }

            default:
                # code...
                break;
        }
        $redis->set('buy',$buy);
        $redis->set('sale',$sale);

        $return = [
        'buy'=>$buy,
        'sale'=>$sale,
        'type'=>$type,
        'time'=>date('H:i:s',time()),
        'data'=>$data,
        'noNumber'=>bcsub($data['number_no'],$keep,6),
        ];
        $return = json_encode($return);
        return $return;
    }




}
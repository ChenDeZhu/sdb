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
        //$keep为交易未完成的量
        $keep = $data['number_no'];
        // 在这先判断该用户是否有足够的余额
        // 或是判断有没有足够的虚拟币
                $uid = input('session.uid');
                $money = db('user')->where('uid',$uid)->value('money');
                if($money <($data['price']*$data['number_no'])){
                    // $connection->send('余额不够');
                    return;
                }
                 //挂单,冻结购买的金额
                 $data1 = [
                            'bid'=>$data['bid'],
                            'number'=>$data['number_no'],
                            'number_no'=>$data['number_no'],
                            'addtime'=>time(),
                            'uid'=>1,
                            'price'=>$data['price'],
                            'type'=>$data['type']
                        ];
                        //添加自己的委托信息到记录表
                $nid = db('deal')->insertGetId($data1);
                //再通过价格与币种类获取 符合条件的委托单
                $type1 = $data['type']?0:1;
                $where = [
                    'bid'=>$data['bid'],
                    'type'=>$type1,
                    'status'=>0,
                    'price'=>$data['price']
                ];
                $list = db('deal')->field('id,bid,price,number_no,number')->where($where)->order('addtime asc')->select();
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
                        db('deal')->update($data2);
                    



                        if($data['type'] ==0){
                            //买入委托
                             //进行数据库事务操作
                         Db::transaction(function(){
                            
                            //买者币种数量增加
                            
                            //卖者金额增加
                            
                            //添加到交易记录
                            
                        });
                        }else{
                            // 卖出委托
                        }
                       
                       









                        //改变前台该委托单的状态
                         $return1 = [
                            'id'=>$v['id'],
                            'number_no'=>abs($keep),
                            'type'=>2
                        ];
                        $return1 = json_encode($return1);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return1);
                                }
                        //更新该委托单的状态
                        $status = $keep==0?1:0;
                        $data3 = [
                            'id'=>$v['id'],
                            'status'=>$status,
                            'number_no'=>abs($keep),
                        ];
                         db('deal')->update($data3);
                        //将委托信息返回给前台价格，数量，剩余
                            $return = [
                                'price'=>$data['price'],
                                'number'=>$data['number_no'],
                                'number_no'=>0,
                                'id'=>$nid,
                                'type'=>$data['type']
                            ];
                            $return = json_encode($return);
                            foreach($text_worker->connections as $conn)
                                {   
                                    $conn->send($return);
                                }
                                    //终止循环     
                                    break;
                    }
                    else{
                        //////////////
                        // 添加到交易记录表 //
                        //待定        //
                        //////////////
                        ///
                        
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

                        //更新该委托信息的状态
                        $data4 = [
                            'id'=>$v['id'],
                            'status'=>1,
                            'number_no'=>0,
                        ];
                         db('deal')->update($data4);



                        }
                        //

                    }
                    //循环结束以后,委托数量还有，返回前台信息
                   if($keep>0){
                    //更新买单
                    
                     $data5 = [
                        'status'=>0,
                        'number_no'=>$keep,
                        'id'=>$nid
                        ];
                        db('deal')->update($data5);
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
                
        
    };
    $text_worker->onClose = function($connection){
        // global $text_worker;
        // foreach($text_worker->connections as $conn)
        // {
        //     $conn->send("user[{$connection->uid}] logout");
        // }
    };
    Worker::runAll();
	}
   
}
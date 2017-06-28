<?php
namespace app\socket\controller;  
use Workerman\Worker;

require_once __DIR__ .'/../../../vendor/workerman/workerman/Autoloader.php';

class Index{
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
        //根据提交的type判断以后执行买卖操作
        switch ($data['type']){
            //0:买入委托
            case '0':
                 //通过价格与币种类获取 符合条件的卖出委托单的货币数
                $where = [
                'bid'=>1,
                'type'=>1,
                'status'=>0,
                'price'=>$data['price']
                ];
                $list = db('deal')->field('price,number_no')->where($where)->order('addtime asc')->select();
                //如果存在
                if($list){
                    foreach ($list as $k => $v) {
                        //如果交易量小于卖单的数量
                        if($data['number']<$v['number_no']){
                            //委托买单的成交状态为1
                           $data1 = [
                            'status'=>1,
                            'number'=>$data['number'],
                            'addtime'=>time(),
                            'bid'=>1,
                            'uid'=>1,
                            'type'=>'0',
                            'price'=>$data['price']
                        ];
                        //添加到委托信息记录表
                        db('deal')->insert($data1);
                        //////////////
                        // 添加到交易记录表 //
                        //待定        //
                        //////////////
                        //更新该卖单的状态
                        $number = $v['number']-$data['number'];
                        $status = $number==0?1:0;
                        $data2 = [
                            'status'=>$status,
                            'number_no'=>$number,
                            'number'=>$v['number']+$number,
                        ];
                            //跳出循环
                            break;
                        }

                    }
                }else{
                    $data3 = [
                            'status'=>1,
                            'number_no'=>$data['number'],
                            'addtime'=>time(),
                            'bid'=>1,
                            'uid'=>1,
                            'type'=>'0',
                            'price'=>$data['price']
                        ];
                    db('deal')->insert($data3);
                     foreach($text_worker->connections as $conn)
                        {   
                            $conn->send($data);
                        }
                    }
                break;
            //1:卖出委托
            case '1':
                
                break;
            
            default:
                
                break;
        }



        // $data1 = [
        // 'bid'=>1,
        // 'type'=>0,
        // 'status'=>1,
        // 'price'=>$data['price'],
        // 'number'=>$data['number'],
        // 'uid'=>1,
        // 'addtime'=>time(),
        // ];
        // db('deal')->insert($data1);
        // $data = json_encode($data);

        // foreach($text_worker->connections as $conn)
        // {   
        //     $conn->send($data);
        // }
        
    };
    $text_worker->onClose = function($connection){
        global $text_worker;
        foreach($text_worker->connections as $conn)
        {
            $conn->send("user[{$connection->uid}] logout");
        }
    };
    Worker::runAll();
	}
   
}
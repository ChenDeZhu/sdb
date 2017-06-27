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
        // dump($buffer);
        $data = json_decode($buffer,true);
        var_dump($data);
        global $text_worker;
        $data1 = [
        'bid'=>1,
        'type'=>0,
        'status'=>1,
        'price'=>$data['price'],
        'number'=>$data['number'],
        'uid'=>1,
        'addtime'=>time(),
        ];
        db('deal')->insert($data1);
        $data
        foreach($text_worker->connections as $conn)
        {   
            $conn->send($data);
        }

    };
    $text_worker->onClose = function($connection){
        global $text_worker;
        foreach($text_worker->connections as $conn)
        {
            $conn->send("user[{$connection->uid}] logout");
        }
        // $connection->send('hello');  
    };
    Worker::runAll();
	}
   
}
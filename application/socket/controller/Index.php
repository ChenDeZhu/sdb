<?php
namespace app\socket\controller;  
use Workerman\Worker;

class Index{
	public function index(){
		 //连接服务器
        
        // 创建一个Worker监听2346端口，使用websocket协议通讯  
        $ws_worker = new Worker("websocket://127.0.0.1:2346");  
        $ws_worker->onWorkerStart = function($worker){
            $inner_text_worker = new Worker('text://127.0.0.1:5678');
             $inner_text_worker->onMessage = function($connection, $buffer)
            {
                // $data数组格式，里面有uid，表示向那个uid的页面推送数据
                $data = json_decode($buffer, true);
                $uid = $data['uid'];
                // 通过workerman，向uid的页面推送数据
                $ret = $this->sendMessageByUid($uid, $buffer);
                // 返回推送结果
                $connection->send($ret ? 'ok' : 'fail');
            };
        };
        // 启动4个进程对外提供服务  
        $ws_worker->listen();
        $ws_worker->count = 4;  
        // 当收到客户端发来的数据后返回hello $data给客户端  
        $ws_worker->onMessage = function($connection, $data)
        {   
             
            $connection->send('hello ' . $data);  
        };  
        // 运行worker  
        Worker::runAll();
      
	}
}
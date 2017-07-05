<?php
namespace app\index\controller;
use think\Controller;
use think\cache\driver\Redis;
/*交易中心*/
class Trade extends Common{
	public function index(){
		$redis = new Redis();
		$data = [
			'1'=>1.12,
			'asd'=>'aa',
		];
	    // $redis->set('a',$data);
	    var_dump ($redis->get('buy'));
		return $this->fetch();
	}
}
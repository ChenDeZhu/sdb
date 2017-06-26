<?php
namespace app\index\controller;
use Workerman\Worker;  
use think\Controller;
use think\Db;
/*首页*/
class Index extends Common{
	//首页
	public function index(){
		//获取币种
		if(input('bid')){
			$where['bid'] = input('bid');
			//如果该币种不存在，则默认1
			if(!$res = db('deal')->where($where)->count()){
				$where['bid'] = 1;
			}
		}else{
			$where['bid'] = 1;
		}
		//获取所有币种的信息
		$list = db('currency')->order('id')->select();
		foreach ($list as $k => $v) {
			$where['bid'] = $v['id'];
			$data[] = model('deal')->getAll($where);
		}
		$this->assign('data',$data);
		return $this->fetch();
	}
}
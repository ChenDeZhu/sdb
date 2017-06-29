<?php
namespace app\index\controller;
use think\Contrller;
/*行情图表*/
class Market extends Common{
	public function index(){
		//获取买卖委托信息
		$bid = 1;
		$where = [
			'bid'=>$bid,
			'type'=>0,
		];
		
		//买家委托
		$list1 = db('deal')->where($where)->field('id,price,number,number_no')->order('addtime desc')->limit('200')->select();
		//卖家委托
		$where['type'] = 1;
		$list2 = db('deal')->where($where)->field('id,price,number,number_no')->order('addtime desc')->limit('200')->select();
		$this->assign('list1',$list1);
		$this->assign('list2',$list2);
		

		return $this->fetch();
	}
}
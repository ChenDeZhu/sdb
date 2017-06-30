<?php
namespace app\index\controller;
use think\Contrller;
use think\Db;
/*行情图表*/
class Market extends Common{
	public function index(){
		$DEAL = Db::name('deal');
		//获取买卖委托信息
		$bid = 1;
		$where = [
			'bid'=>$bid,
			'type'=>0,
			// 'price'=>200.10
		];
		$uid = input('session.uid');
		//买家委托
		$list1 = $DEAL->where($where)->field('id,price,number,number_no')->order('addtime desc')->limit('200')->select();
		//卖家委托
		$where['type'] = 1;
		$list2 = $DEAL->where($where)->field('id,price,number,number_no')->order('addtime desc')->limit('200')->select();
		
		$this->assign('list1',$list1);
		$this->assign('list2',$list2);
		//获取资产相关信息
		//获取可用金额
		$money = Db::name('user')->where('uid',$uid)->value('money');
		$where1 = [
			'type'=>0,
			'uid'=>$uid,
			'status'=>0
		];
		//获取冻结金额
		$list3 = $DEAL->where($where1)->field('number_no,number,price')->select();
		$dmoney = '0';
		foreach ($list3 as $k => $v) {
			$dmoney += ($v['number_no']*$v['price']);
		}
		//获取可用的该币种数量
		$where2 = [
			'uid'=>$uid,
			'bid'=>$bid,

		];
		$currency = Db::name('u-b')->where($where2)->value('number');
		$currency = $currency??0;
		$where2['status'] = 0;
		$where2['type'] = 1;
		$list4 = Db::name('deal')->where($where2)->select();
		$dcurrency = '0';
		foreach ($list4 as $key => $v) {
			$dcurrency += $v['number_no'];
		}
		$this->assign('money',$money);
		$this->assign('dmoney',$dmoney);
		$this->assign('currency',$currency);
		$this->assign('dcurrency',$dcurrency);
		return $this->fetch();
	}
}
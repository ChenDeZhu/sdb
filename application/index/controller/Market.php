<?php
namespace app\index\controller;
use think\Contrller;
use think\Db;
use think\cache\driver\Redis;
/*行情图表*/
class Market extends Common{
	public function index(){
		//获取买卖委托信息
		$bid = 1;
		$where = [
			'bid'=>$bid,
			'status'=>0,
		];
		$uid = input('session.uid');
		echo $uid;
		//买家委托
		$list1 = Db::name('deal_b')->where($where)->group('price')->field('price,sum(number_no) as number')->order('price desc')->limit('200')->select();
		//卖家委托
		$list2 = Db::name('deal_s')->where($where)->group('price')->field('price,sum(number_no) as number')->order('price asc')->limit('200')->select();

		//将委托信息放入缓存
		$redis = new Redis();
		$redis->set('buy',$list1);
		$redis->set('sale',$list2);
		//最新成交
		$where1 = [
			'type'=>array('gt',2)
		];
		$list3 = Db::name('mrecord')
		->field('type,money,sum(number) as number,addtime')
		->where($where1)
		->group('oid')
		->order('addtime desc')->limit(60)->select();
		$this->assign('list1',$list1);
		$this->assign('list2',$list2);
		$this->assign('list3',$list3);

		//获取资产相关信息
		//获取可用金额
		$money = Db::name('user')->where('uid',$uid)->value('money');
		$where1 = [
			'uid'=>$uid,
			'status'=>0
		];
		//获取冻结金额
		$list3 = Db::name('deal_b')->where($where1)->field('number_no,number,price')->select();
		$dmoney = '0';
		foreach ($list3 as $k => $v) {
			$dmoney = bcadd($dmoney,bcmul($v['number_no'],$v['price']),2);
		}
		//获取可用的该币种数量
		$where2 = [
			'uid'=>$uid,
			'bid'=>$bid,
		];
		$currency = Db::name('u-b')->where($where2)->value('number');
		$currency = $currency??0;
		//获取冻结币种
		$where2['status'] = 0;
		$list4 = Db::name('deal_s')->where($where2)->select();
		$dcurrency = '0';
		// var_dump($list4);exit;
		foreach ($list4 as $key => $v) {
			$dcurrency = bcadd($dcurrency,$v['number_no'],6);
		}
		$this->assign('money',$money);
		$this->assign('dmoney',$dmoney);
		$this->assign('currency',$currency);
		$this->assign('dcurrency',$dcurrency);
		return $this->fetch();
	}
}
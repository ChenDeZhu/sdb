<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
/*资金管理*/
class Account extends Common{
	//充值界面
	public function index(){
		return $this->fetch();
	}
	//提现界面
	public function withdrawCny(){
		return $this->fetch();
	}
	public function wihtdrawCnya(){
		return $this->fetch();
	}
	//账单明细
	public function record(){
		$this->isLogin();
		$uid=input('session.uid');
		// $where['bid'] = input('get.bid')!=null??'';
		$where['uid'] = $uid;
		$where['type'] = 3;
		
		$list = Db::field('id,addtime,type,sum(number) as number,money,poundage')
		->name('mrecord')
		->where($where)
		->group('oid')
		->union('select id,addtime,type,number,money,poundage from s_mrecord where uid ='.$uid.' and type<3')
		->union('select id,addtime,type,sum(number) as number,money,poundage from s_mrecord where fuid ='.$uid.' and type=4 group by oid order by addtime desc')
		->select();

		$this->assign('list',$list);
		return $this->fetch();
	}
}
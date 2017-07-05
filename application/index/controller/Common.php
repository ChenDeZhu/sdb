<?php
namespace app\index\controller;
use think\Controller;
use think\Session;

class Common extends Controller{
	/**
	 * [_initialize 查询出公司基本信息]
	 *
	 */
	public function _initialize(){
		$cinfo = db('system')->find(1);
		$this->assign('cinfo',$cinfo);
		Session::set('uid',1);
	}
	/**
	 * [验证登陆]
	 */
	public function isLogin(){
		
		if(input('session.uid')){
			return true;
		}else{
			
		}
	}
	

}
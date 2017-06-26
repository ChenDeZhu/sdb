<?php
namespace app\index\controller;
use think\Controller;

class Common extends Controller{
	

	/**
	 * [_initialize 查询出公司基本信息]
	 *
	 */
	public function _initialize(){
		$cinfo = db('system')->find(1);
		$this->assign('cinfo',$cinfo);
	}
	/**
	 * [验证登陆]
	 */
	public function isLogin(){
		if(input('session.uid')){
			return ture;
		}else{
			
		}
	}
}
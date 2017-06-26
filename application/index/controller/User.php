<?php
namespace app\index\controller;
use think\Contrller;
/*个人中心*/
class User extends Common{

	//安全中心
	public function index(){
		return $this->fetch();
	}
	
	//身份认证
	public function chinaID(){
		return $this->fetch();
	}

	//通知设置
	public function notice(){
		return $this->fetch();
	}

	//发起提问
	public function question(){
		return $this->fetch();
	}

	//我的问题列表
	public function questionList(){
		return $this->fetch();
	}
	
}
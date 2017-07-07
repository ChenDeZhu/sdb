<?php
namespace app\index\controller;
use think\Controller;
class Login extends Controller{

	public function index(){
		$url = urlencode("http://www.jtys114.com/index.php/Login/pc_oauth");
		$this->assign('url',$url);
		return $this->fetch();
	}

	public function pc_login(){
		$appid ="wxf3713c3951d84eb4";
        $redirect_uri=urlencode("http://www.jtys114.com/index.php/Login/pc_oauth");
        header('location:https://open.weixin.qq.com/connect/qrconnect?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_login&state=1#wechat_redirect');
	}

	public function pc_oauth(){

	}


}
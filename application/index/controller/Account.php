<?php
namespace app\index\controller;
use think\Contrller;
/*资金管理*/
class Account extends Common{
	public function index(){
		return $this->fetch();
	}
}
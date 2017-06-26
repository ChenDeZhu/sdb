<?php
namespace app\index\controller;
use think\Controller;
/*交易中心*/
class Trade extends Common{
	public function index(){
		return $this->fetch();
	}
}
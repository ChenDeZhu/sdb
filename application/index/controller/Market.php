<?php
namespace app\index\controller;
use think\Contrller;
/*行情图表*/
class Market extends Common{
	public function index(){
		return $this->fetch();
	}
}
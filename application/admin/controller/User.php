<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
class User extends Common{

    public function index()
    {	
    	//关键词查询
        $q = input('q');
        $catid = input('catid');
        if ($q) {
            $map['name'] = ['like', '%' . strip_tags(trim($q)) . '%'];
        }
        if (!isset($map)) {
            $map = 1;
        }
        $list = db('user')->where($map)->order('reg_time desc')->paginate(10, false, ['query' => ['q' => $q]]);
        $page = $list->render();
        unset($list[0]);
        $this->assign('q', $q);
        $this->assign('list', $list);
        $this->assign('page', $page);
        return $this->fetch();

    }
    
 	public function user_detail(){
 		$uid = input('get.id');
 		$map['a.uid'] = $uid;
 		$list = db('u-b')->alias('a')->where($map)->join('__CURRENCY__ b','b.id = a.bid')->field('a.use_num,a.guadan_num,a.con_num,b.name')->select();
 		$data = db('user')->find($uid);
 		$this->assign('data',$data);
 		$this->assign('list', $list);
 		return $this->fetch();
 	}
 	

}
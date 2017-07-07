<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
Class Shequ extends Common{
	public function index(){
		//获取资讯
		$type = empty(input('param.type'))?0:input('type');
		$where = ['a.type'=>$type];
		$list = Db::name('infomation')->alias('a')
		->where($where)
		->join('__USER__ b','b.uid=a.uid')
		->join('__CATE__ c','c.id = a.cid')
		->field('b.nickname,c.name,a.title,a.title,a.click,a.addtime,a.id')
		->paginate(20);
		$page = $list->render();
		$this->assign('page',$page);
		$this->assign('list',$list);
		// var_dump($list);
		return $this->fetch();
	}

	public function detail(){
		$id = input('param.id');
		$detail = db('infomation')->alias('a')
		->join('__USER__ b','b.uid=a.uid')
		->join('__CATE__ c','c.id = a.cid')
		->field('b.nickname,c.name,a.title,a.title,a.click,a.addtime,a.id,content')
		->find($id);
		//获取评论
		$list = Db::name('info_review')->alias('a')
		->join('__USER__ b','b.uid=a.uid')
		->field('b.nickname,a.id,a.pid,a.content,a.addtime')
		->order('')
		->select()
		$this->assign('detail',$detail);
		return $this->fetch();
	}

	//添加评论或回复评论
	public function addReview(){
		if(request()->isAjax()){
			$data = input('post.data');
			$data['addtime'] = time();
		}else{
		//显示页面
		return  $this->fetch();
		}
	}
	//添加新闻或论坛内容
	public function addInfo(){
		if(request()->isAjax()){
			$data = input('post.data');
			$data['addtime'] = time();
		}else{
			
		return  $this->fetch();
		}
	}

}
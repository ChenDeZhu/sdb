<?php
namespace app\admin\controller;


class Currency extends Common{
    public function index()
    {
        $q = input('q');
        $catid = input('catid');
        if ($q) {
            $map['name'] = ['like', '%' . strip_tags(trim($q)) . '%'];
        }
        if (!isset($map)) {
            $map = 1;
        }
        //这里$str留着做排序用
        $str = 'id desc';
        $list = db('currency')->where($map)->order($str)->paginate(10, false, ['query' => ['q' => $q]]);
        $page = $list->render();
        // print_r($list);exit;
        $this->assign('q', $q);
        $this->assign('list', $list);
        $this->assign('page', $page);
        return $this->fetch();
    }
    public function add()
    {
        if (request()->isPost()){
            $data = input('post.');
            $data['addtime'] = time();
            
            $id = db('currency')->insert($data);
            if(!$id){
            	return false;
            }
            if (isset($data['dosubmit'])) {
                $this->success('添加成功', 'Currency/index');
            } else {
                $this->success('添加成功');
            }
        }
        return $this->fetch();
    }
    public function edit()
    {
        if (request()->isPost()) {
            $data = input('post.');
            // $this->edit_content($data);
            $res = db('currency')->update($data);
            $this->success('修改成功', 'Currency/index');
        }
        $id = intval($_GET['id']);
        if (!$id) {
            $this->error('非法参数');
        }
        $data = db('currency')->find($id);
        if (!$data) {
            $this->error('招聘不存在');
        }
        $this->assign('data', $data);
       
        return $this->fetch();
    }
    //删除操作
    public function delete()
    {
       $data = input('param.');
        $id = intval($data['id']);
        if (!$id) {
        	$this->error('非法参数');
        }
            db('currency')->delete($id);

        $this->success('删除成功');
    }
    //更新排序
     public function listorder()
    {
        $data = input('post.');
        if (!$data) {
            $this->error('参数错误');
        }
        foreach ($data['sort'] as $k => $v) {
            $k = intval($k);
            Article2::update(['id' => $k, 'sort' => $v]);
        }
        $this->success('更新成功');
    }
}
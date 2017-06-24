<?php
namespace app\admin\controller;


class Recruitment extends Common{
    public function index()
    {
        $q = input('q');
        $catid = input('catid');
        if ($q) {
            $map['position'] = ['like', '%' . strip_tags(trim($q)) . '%'];
        }
        if (!isset($map)) {
            $map = 1;
        }
        $list = db('recruitment')->where($map)->order('sort desc,id desc')->paginate(10, false, ['query' => ['q' => $q]]);
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
            $id = db('recruitment')->insert($data);
            if(!$id){
            	return false;
            }
            if (isset($data['dosubmit'])) {
                $this->success('添加成功', 'Recruitment/index');
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
            $res = db('recruitment')->update($data);
            $this->success('修改成功', 'Recruitment/index');
        }
        $id = intval($_GET['id']);
        if (!$id) {
            $this->error('非法参数');
        }
        $data = db('recruitment')->find($id);
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
            db('recruitment')->delete($id);

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
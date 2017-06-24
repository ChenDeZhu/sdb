<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\Question as Question1;
class Question extends Common{
    public function index()
    {
        $q = input('q');

        $status = input('status');
        if ($q) {
            $map['a.title'] = ['like', '%' . strip_tags(trim($q)) . '%'];
        }
        if ($status!='') {
            $map['a.status'] = intval($status);
        }
        if (!isset($map)) {
            $map = 1;
        }
        $list = db('Question')->alias('a')->join('__USER__ b','b.uid=a.uid')->where($map)->field('a.id,a.title,a.addtime,b.nickname,a.status,a.description')->order('addtime desc')->paginate(10, false, ['query' => ['q' => $q]]);
        $page = $list->render();
        $this->assign('q', $q);
        $this->assign('status', $status);
        // var_dump($status);exit;
        $this->assign('list', $list);
        $this->assign('page', $page);
        return $this->fetch();
    }
    
    public function edit()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data['status'] = 1;
            $data['returntime'] = time();
            // $this->edit_content($data);
            $res = db('question')->update($data);
            $this->success('修改成功', 'Question/index');
        }
        $id = intval($_GET['id']);
        if (!$id) {
            $this->error('非法参数');
        }
        $data = db('Question')->find($id);
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
        if (!isset($data['id']) || empty($data['id'])) {
            $this->error('参数错误');
        }
        if (is_array($data['id'])) {
            foreach ($data['id'] as $v) {
                Question1::destroy($v);
            }
        } else {
            $id = intval($data['id']);
            if (!$id) {
                $this->error('非法参数');
            }
            Question1::destroy($id);
          
        }
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
            Question1::update(['id' => $k, 'sort' => $v]);
        }
        $this->success('更新成功');
    }
}
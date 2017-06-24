<?php
namespace app\admin\controller;

use think\Controller;
class Page extends Common{
    public function index()
    {
        $list = db('page')->where($map)->order('id asc')->select();
        $this->assign('list', $list);
        return $this->fetch();
    }
    
    public function edit()
    {
        if (request()->isPost()) {
            $data = input('post.');
           
            $res = db('page')->update($data);
            $this->success('修改成功', 'Page/index');
        }
        $id = intval($_GET['id']);
        if (!$id) {
            $this->error('非法参数');
        }
        $data = db('page')->find($id);
        if (!$data) {
            $this->error('单页不存在');
        }
        $this->assign('data', $data);
       
        return $this->fetch();
    }


}
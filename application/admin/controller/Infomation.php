<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\Infomation as Infomation1;
class Infomation extends Common{
    public function index()
    {
        $q = input('q');
        $type = input('type');
        if ($q) {
            $map['a.title'] = ['like', '%' . strip_tags(trim($q)) . '%'];
        }
        if ($type) {
            $map['a.type'] = intval($type);
        }
        if (!isset($map)) {
            $map = 1;
        }
        //这里$str留着做排序用
        $str = 'a.id desc';
        $list = db('infomation')->alias('a')->join('__USER__ b','b.uid=a.uid')->where($map)->field('a.id,a.title,a.addtime,b.nickname,a.sort,a.type')->order($str)->paginate(10, false, ['query' => ['q' => $q]]);
        $page = $list->render();
        
        
        // $type = [
        // '0'=>'论坛',
        // '1'=>'新闻',
        // '2'=>'常见问题'
        // ];
        //取出来的$list是对象所以不能这样循环
        // foreach ($list as $k => $v) {
        //     $list[$k]['type'] = $type[$v['type']];
        // }
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
            
            $id = db('infomation')->insert($data);
            if(!$id){
            	return false;
            }
            if (isset($data['dosubmit'])) {
                $this->success('添加成功', 'Infomation/index');
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
            $res = db('infomation')->update($data);
            $this->success('修改成功', 'Infomation/index');
        }
        $id = intval($_GET['id']);
        if (!$id) {
            $this->error('非法参数');
        }
        $data = db('Infomation')->find($id);
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
                Infomation1::destroy($v);
            }
        } else {
            $id = intval($data['id']);
            if (!$id) {
                $this->error('非法参数');
            }
            Infomation1::destroy($id);
          
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
            Infomation1::update(['id' => $k, 'sort' => $v]);
        }
        $this->success('更新成功');
    }
}
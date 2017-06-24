<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use app\admin\model\Article as Article2;
use app\admin\model\ArticleData;
use app\admin\model\Tag;
use app\admin\model\TagData;
class Article extends Common
{
    public function index()
    {
        $q = input('q');
        $catid = input('catid');
        if ($q) {
            $map['title'] = ['like', '%' . strip_tags(trim($q)) . '%'];
        }
        if ($catid) {
            $map['catid'] = intval($catid);
        }
        if (!isset($map)) {
            $map = 1;
        }
        $article_list = Article2::where($map)->order('listorder desc,id desc')->paginate(10, false, ['query' => ['q' => $q, 'catid' => $catid]]);
        $page = $article_list->render();
        
        $this->assign('q', $q);
        $this->assign('catid', $catid);
        $this->assign('article_list', $article_list);
        $this->assign('cate_list', model('category')->getList());
        $this->assign('page', $page);
        return $this->fetch();
    }
    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $this->add_content($data);
            if (isset($data['dosubmit'])) {
                $this->success('添加成功', 'Article/index');
            } else {
                $this->success('添加成功');
            }
        }
        $content_form = new \org\Content_form();
        $forminfos = $content_form->get();
        $this->assign('forminfos', $forminfos);
        // print_r($forminfos);exit;
        $catid = input('catid');
        $this->assign('list', model('category')->getList());
        $this->assign('catid', intval($catid));
        return $this->fetch();
    }
    public function edit()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $this->edit_content($data);
            $this->success('修改成功', 'Article/index');
        }
        $id = intval($_GET['id']);
        if (!$id) {
            $this->error('非法参数');
        }
        $data = db('article')->alias('a')->join('__ARTICLE_DATA__ d ', 'a.id= d.id')->where('a.id', $id)->find();
        if (!$data) {
            $this->error('文章不存在');
        }
        $this->assign('data', $data);
        $content_form = new \org\Content_form();
        $forminfos = $content_form->get($data);
        $this->assign('forminfos', $forminfos);
        $this->assign('list', model('category')->getList());
        return $this->fetch();
    }
    public function delete()
    {
        $data = input('param.');
        if (!isset($data['id']) || empty($data['id'])) {
            $this->error('参数错误');
        }
        if (is_array($data['id'])) {
            foreach ($data['id'] as $v) {
                Article2::destroy($v);
                ArticleData::destroy($v);
                TagData::destroy(['contentid' => $v]);
            }
        } else {
            $id = intval($data['id']);
            if (!$id) {
                $this->error('非法参数');
            }
            Article2::destroy($id);
            ArticleData::destroy($id);
            TagData::destroy(['contentid' => $id]);
        }
        $this->success('删除成功');
    }
    public function delete_all()
    {
        Db::execute('truncate ' . $this->prefix . 'article');
        Db::execute('truncate ' . $this->prefix . 'article_data');
        Db::execute('truncate ' . $this->prefix . 'tag');
        Db::execute('truncate ' . $this->prefix . 'tag_data');
        $this->success('删除成功');
    }
    public function listorder()
    {
        $data = input('post.');
        if (!$data) {
            $this->error('参数错误');
        }
        foreach ($data['listorder'] as $k => $v) {
            $k = intval($k);
            Article2::update(['id' => $k, 'listorder' => $v]);
        }
        $this->success('更新成功');
    }
    public function status()
    {
        $data = input('post.');
        if (!isset($data['id']) || empty($data['id'])) {
            $this->error('参数错误');
        }
        foreach ($data['id'] as $v) {
            $v = intval($v);
            $status = Article2::where('id', $v)->value('status');
            $status = $status ? 0 : 1;
            Article2::update(['id' => $v, 'status' => $status]);
        }
        $this->success('更新成功');
    }
    private function go_to_tag($contentid, $data)
    {
        $data = preg_split('/[ ,]+/', $data);
        if (is_array($data) && !empty($data)) {
            foreach ($data as $v) {
                $v = safe_replace(addslashes($v));
                $v = str_replace(['//', '#', '.'], ' ', $v);
                $r = Tag::get(['tag' => $v]);
                if (!$r) {
                    $tagid = Tag::insertGetId(['tag' => $v, 'count' => 1]);
                } else {
                    Tag::where('tagid', $r['tagid'])->setInc('count', 1);
                    $tagid = $r['tagid'];
                }
                if (!TagData::get(['tagid' => $tagid, 'contentid' => $contentid])) {
                    TagData::insert(['tagid' => $tagid, 'contentid' => $contentid]);
                }
            }
        }
    }
    private function add_content($data)
    {
        $catid = intval($data['catid']);
        if (!$catid) {
            $this->error('栏目必须选择');
        }
        if (trim($data['title']) == '') {
            $this->error('标题不能为空');
        }
        $content_input = new \org\Content_input();
        $inputinfo = $content_input->get($data);
        $data_a = $inputinfo['a'];
        $data_a['catid'] = $catid;
        $data_a['status'] = 1;
        $data_b = isset($inputinfo['b']) ? $inputinfo['b'] : [];
        if ($data['inputtime'] && !is_numeric($data['inputtime'])) {
            $data_a['inputtime'] = strtotime($data['inputtime']);
        } else {
            $data_a['inputtime'] = request()->time();
        }
        if ($data['updatetime'] && !is_numeric($data['updatetime'])) {
            $data_a['updatetime'] = strtotime($data['updatetime']);
        } else {
            $data_a['updatetime'] = request()->time();
        }
        if (!isset($data_a['description']) && isset($data['content'])) {
            $content = stripslashes($data['content']);
            $data_a['description'] = str_cut(str_replace(array("'", "\r\n", "\t", '[page]', '[/page]', '&ldquo;', '&rdquo;', '&nbsp;'), '', strip_tags($content)), 300);
            $data_a['description'] = addslashes($data_a['description']);
        }
        if (!isset($data_a['thumb']) && isset($data['content'])) {
            $content = stripslashes($data['content']);
            if (preg_match_all("/(src)=([\"|']?)([^ \"'>]+\\.(gif|jpg|jpeg|bmp|png))\\2/i", $content, $matches)) {
                $data_a['thumb'] = $matches[3][0];
            }
        }
        if (isset($data_a['description'])) {
            $data_a['description'] = str_replace(array('/', '\\', '#', '.', "'"), ' ', $data_a['description']);
        }
        if (isset($data_a['keywords'])) {
            $data_a['keywords'] = str_replace(array('/', '\\', '#', '.', "'"), ' ', $data_a['keywords']);
        }
        $id = Article2::insertGetId($data_a);
        if (!$id) {
            return FALSE;
        }
        $data_b['id'] = $id;
        ArticleData::insert($data_b);
        if ($data_a['keywords']) {
            $this->go_to_tag($id, $data_a['keywords']);
        }
        return true;
    }
    private function edit_content($data)
    {
        $catid = intval($data['catid']);
        if (!$catid) {
            $this->error('栏目必须选择');
        }
        if (trim($data['title']) == '') {
            $this->error('标题不能为空');
        }
        $content_input = new \org\Content_input();
        $inputinfo = $content_input->get($data);
        $data_a = $inputinfo['a'];
        $data_a['catid'] = $catid;
        $data_a['status'] = 1;
        $data_b = isset($inputinfo['b']) ? $inputinfo['b'] : [];
        if ($data['inputtime'] && !is_numeric($data['inputtime'])) {
            $data_a['inputtime'] = strtotime($data['inputtime']);
        } else {
            $data_a['inputtime'] = request()->time();
        }
        if ($data['updatetime'] && !is_numeric($data['updatetime'])) {
            $data_a['updatetime'] = strtotime($data['updatetime']);
        } else {
            $data_a['updatetime'] = request()->time();
        }
        if (!isset($data_a['description']) && isset($data['content'])) {
            $content = stripslashes($data['content']);
            $data_a['description'] = str_cut(str_replace(array("'", "\r\n", "\t", '[page]', '[/page]', '&ldquo;', '&rdquo;', '&nbsp;'), '', strip_tags($content)), 300);
            $data_a['description'] = addslashes($data_a['description']);
        }
        if (!isset($data_a['thumb']) && isset($data['content'])) {
            $content = stripslashes($data['content']);
            if (preg_match_all("/(src)=([\"|']?)([^ \"'>]+\\.(gif|jpg|jpeg|bmp|png))\\2/i", $content, $matches)) {
                $data_a['thumb'] = $matches[3][0];
            }
        }
        if (isset($data_a['description'])) {
            $data_a['description'] = str_replace(array('/', '\\', '#', '.', "'"), ' ', $data_a['description']);
        }
        if (isset($data_a['keywords'])) {
            $data_a['keywords'] = str_replace(array('/', '\\', '#', '.', "'"), ' ', $data_a['keywords']);
        }
        $data_a['id'] = $data_b['id'] = $data['id'];
        Article2::update($data_a);
        ArticleData::update($data_b);
        if ($data_a['keywords']) {
            $this->go_to_tag($data['id'], $data_a['keywords']);
        }
        return true;
    }
}
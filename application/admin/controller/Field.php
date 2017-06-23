<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Config;
use app\admin\model\Field as Field2;
class Field extends Common
{
    public $fields, $forbid_fields, $forbid_delete, $forbid_edit, $tablepre;
    public function __construct()
    {
        parent::__construct();
        $this->fields = ['text' => '单行文本', 'textarea' => '多行文本', 'editor' => '编辑器', 'image' => '图片', 'images' => '多图片', 'datetime' => '日期和时间', 'number' => '数字'];
        $this->forbid_fields = ['title', 'updatetime', 'hits', 'inputtime', 'listorder', 'status'];
        $this->forbid_delete = ['title', 'thumb', 'description', 'keywords', 'updatetime', 'inputtime', 'listorder', 'status', 'hits'];
        $this->forbid_edit = ['title', 'thumb', 'keywords', 'updatetime', 'inputtime', 'listorder', 'status', 'hits'];
        $dbconfig = Config::get('database');
        $this->tablepre = $dbconfig['prefix'];
    }
    public function index()
    {
        $list = Field2::order('listorder asc , id asc')->select();
        $this->assign('list', $list);
        $this->assign('forbid_fields', $this->forbid_fields);
        $this->assign('forbid_delete', $this->forbid_delete);
        return $this->fetch();
    }
    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data = $this->checkdata($data);
            if ($data['issystem'] == 1) {
                $tablename = $this->tablepre . 'article';
            } else {
                $tablename = $this->tablepre . 'article_data';
            }
            $field = $data['field'];
            $this->check_field($field, '', $tablename);
            $defaultvalue = isset($data['defaultvalue']) ? $data['defaultvalue'] : '';
            $field_type = $this->field_type($data['formtype']);
            if (isset($data['length'])) {
                $data['length'] = intval($data['length']);
                if ($data['length'] < 1 || $data['length'] > 255) {
                    $this->error('长度范围1~255');
                }
                $length = $data['length'];
            } else {
                $length = $data['length'] = 0;
            }
            $this->add_sql($tablename, $field, $field_type, $length, $defaultvalue);
            $id = Field2::insertGetId($data);
            if ($id > 0) {
                $this->success('添加成功', url('Field/index'));
            } else {
                $this->error('添加失败');
            }
        }
        $this->assign('all_field', $this->fields);
        return $this->fetch();
    }
    public function edit()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data = $this->checkdata($data);
            if ($data['issystem'] == 1) {
                $tablename = $this->tablepre . 'article';
            } else {
                $tablename = $this->tablepre . 'article_data';
            }
            $field = $data['field'];
            $formtype = $data['formtype'];
            $defaultvalue = isset($data['defaultvalue']) ? $data['defaultvalue'] : '';
            $field_type = $this->field_type($data['formtype']);
            $oldfield = $data['oldfield'];
            $this->check_field($field, $oldfield, $tablename);
            if (isset($data['length'])) {
                $data['length'] = intval($data['length']);
                if ($data['length'] < 1 || $data['length'] > 255) {
                    $this->error('长度范围1~255');
                }
                $length = $data['length'];
            } else {
                $length = $data['length'] = 0;
            }
            $this->edit_sql($tablename, $oldfield, $field, $field_type, $length, $defaultvalue);
            Field2::update($data);
            $this->success('修改成功', url('Field/index'));
        }
        $id = intval(input('id'));
        if (!$id) {
            $this->error('参数错误');
        }
        $r = Field2::find($id);
        $type = $this->fields[$r['formtype']];
        $this->assign('type', $type);
        $this->assign('formtype', $r['formtype']);
        $this->assign('id', $id);
        $this->assign('forbid_edit', $this->forbid_edit);
        $this->assign('detail', $r);
        return $this->fetch();
    }
    public function delete()
    {
        $id = intval(input('id'));
		if(!$id){
			$this->error('参数错误');
			}
        $r = db('field')->where('id', $id)->find();
        if ($r['issystem'] == 1) {
            $tablename = $this->tablepre . 'article';
        } else {
            $tablename = $this->tablepre . 'article_data';
        }
        $field = $r['field'];
        Field2::destroy($id);
        Db::execute("ALTER TABLE `{$tablename}` DROP `{$field}`;");
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
            Field2::where('id', $k)->update(['listorder' => $v]);
        }
        $this->success('更新成功');
    }
    public function disabled()
    {
        $id = intval(input('id'));
        if (!$id) {
            $this->error('参数错误！');
        }
        $disabled = input('disabled') ? 0 : 1;
        Field2::where('id', $id)->update(['disabled' => $disabled]);
        $this->success('操作成功！');
    }
    private function check_field($field, $oldfield, $tablename)
    {
        $field = strtolower($field);
        $oldfield = strtolower($oldfield);
        if ($field != $oldfield) {
            $variable = Db::query("SHOW COLUMNS FROM `{$tablename}`");
            $fields = [];
            foreach ($variable as $key => $r) {
                $fields[$r['Field']] = $r['Type'];
            }
            if (array_key_exists($field, $fields)) {
                $this->error('字段已存在');
            }
        }
    }
    private function checkdata($data)
    {
        if (!$data['formtype']) {
            $this->error('请选择字段类型');
        }
        if (!$data['field']) {
            $this->error('字段名不能为空');
        }
        if (!preg_match("/^[a-zA-Z]{1}([a-zA-Z0-9]|[_]){0,19}[a-zA-Z0-9]{1}\$/", $data['field'])) {
            $this->error('字段名格式错误');
        }
        if (!$data['name']) {
            $this->error('字段别名不能为空');
        }
        if ($data['formtype'] == 'number') {
            $data['defaultvalue'] = intval($data['defaultvalue']);
        }
        return $data;
    }
    public function default_value()
    {
        $formtype = input('formtype');
        $html = '';
        switch ($formtype) {
            case 'text':
                $html .= '<input class="common-text" size="60" name="defaultvalue" type="text">长度：<input class="common-text" size="10" value="100" name="length" type="text">范围：1~255';
                break;
            case 'textarea':
                $html .= '<textarea name="defaultvalue"  class="common-textarea" style="height:40px; width:80%"></textarea>';
                break;
            case 'number':
                $html .= '<input class="common-text" size="30" name="defaultvalue" value="0" type="text">';
                break;
            default:
                # code...
                break;
        }
        echo $html;
    }
    private function add_sql($tablename, $field, $field_type, $length, $defaultvalue)
    {
        switch ($field_type) {
            case 'varchar':
                $sql = "ALTER TABLE `{$tablename}` ADD `{$field}` VARCHAR({$length}) DEFAULT '{$defaultvalue}'";
                Db::execute($sql);
                break;
            case 'number':
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` ADD `{$field}` INT(10) UNSIGNED DEFAULT '{$defaultvalue}'";
                Db::execute($sql);
                break;
            case 'int':
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` ADD `{$field}` INT(10) UNSIGNED DEFAULT '{$defaultvalue}'";
                Db::execute($sql);
                break;
            case 'text':
                Db::execute("ALTER TABLE `{$tablename}` ADD `{$field}` TEXT");
                break;
        }
        return true;
    }
    private function field_type($formtype)
    {
        switch ($formtype) {
            case 'datetime':
                $field_type = 'int';
                break;
            case 'editor':
                $field_type = 'text';
                break;
            case 'image':
                $field_type = 'varchar';
                break;
            case 'images':
                $field_type = 'text';
                break;
            case 'number':
                $field_type = 'number';
                break;
            case 'text':
                $field_type = 'varchar';
                break;
            case 'textarea':
                $field_type = 'text';
                break;
        }
        return $field_type;
    }
    private function edit_sql($tablename, $oldfield, $field, $field_type, $length, $defaultvalue)
    {
        switch ($field_type) {
            case 'varchar':
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfield}` `{$field}` VARCHAR({$length}) DEFAULT '{$defaultvalue}'";
                Db::execute($sql);
                break;
            case 'number':
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfield}` `{$field}` INT(10) UNSIGNED DEFAULT '{$defaultvalue}'";
                Db::execute($sql);
                break;
            case 'int':
                $defaultvalue = intval($defaultvalue);
                Db::execute("ALTER TABLE `{$tablename}` CHANGE `{$oldfield}` `{$field}` INT(10) UNSIGNED  DEFAULT '{$defaultvalue}'");
                break;
            case 'text':
                Db::execute("ALTER TABLE `{$tablename}` CHANGE `{$oldfield}` `{$field}` TEXT");
                break;
        }
        return true;
    }
}
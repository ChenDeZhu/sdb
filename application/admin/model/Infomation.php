<?php
namespace app\admin\model;

use think\Model;
class Infomation extends Model
{
    protected $pk = 'id';
    public function getTypeAttr($value)
    {
        $status = [0 => '论坛', 1 => '新闻',2=>'常见问题'];
        return $status[$value];
    }
   
}
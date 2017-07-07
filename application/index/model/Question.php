<?php
namespace app\index\model;
use think\Model;

class Question extends Model{
	public function getStatusAttr($value)
    {
        $status = [0=>'等待处理',1=>'已回复'];
        return $status[$value];
    }
    public function getAddtimeAttr($value)
    {
        
        return date('Y-m-d H:i:s',$value);
    }
    // public function getDescriptionAttr($value)
    // {
        
    //     $value = mb_substr($value,0,10,'utf-8');
    //     return $value;
    // }
}
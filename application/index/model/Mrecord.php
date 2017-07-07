<?php
namespace app\index\model;
use think\Model;
class Mrecord extends Model{

	protected $pk = 'id';

	/**
	 * [getMax 获取今日最高成交价]
	 * @param  [type] $where [查询条件]
	 * @return [type]        [今日最高成交价]
	 */
	public function getMax($where){
		$max = $this->where($where)->max('money');
		return $max;
	}

	/**
	 * [getmin 获取今日最低成交价]
	 * @param  [type] $where [查询条件]
	 * @return [type]        [今日最低成交价]
	 */
	public function getMin($where){
		$min = $this->where($where)->min('money');
		return $min;
	}
	/**
	 * [getCount 获取24小时成交量]
	 * @param  [type] $where [查询条件]
	 * @return [type]        [24小时成交量]
	 */
	public function getCount($where){
		//获取24小时之前的时间戳
		$otime = getPerDay(time());
		$count = $this->where('addtime','between',[$otime,time()])
		->where($where)->sum('number');
		return $count;
	}
	/**
	 * [getRecently 获取最近成交价]
	 * @param  [type] $where [查询条件]
	 * @return [type]        [最近成交价]
	 */
	public function getRecently($where){
		$recently = $this->where($where)->limit('1')->order('addtime desc')->value('money');
		return $recently;
	}
	public function getBName($where){
		$id = $where['bid'];
		$name = db('currency')->where('id',$id)->value('name');
		return $name;
	}


	/**
	 * [getAll 获取该币种的所有信息]
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function getAll($where){
		$where['addtime'] = array('gt',strtotime(date("Y-m-d 00:00:00")));
		$where['status'] = 1;
		$data['bid'] = $where['bid'];
		$data['name'] = $this->getBName($where);
		$data['max'] = $this->getMax($where);
		$data['min'] = $this->getMin($where);
		unset($where['addtime']);
		$data['count'] = $this->getCount($where);
		$data['recently'] = $this->getRecently($where);
		

		return $data;
	}





}
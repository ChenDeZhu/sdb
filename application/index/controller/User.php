<?php
namespace app\index\controller;
use app\index\controller\Common;
use think\Db;

/*个人中心*/
class User extends Common{

	//安全中心
	public function index(){
		return $this->fetch();
	}
	
	//身份认证
	public function chinaID(){
		return $this->fetch();
	}

	//通知设置
	public function notice(){
		return $this->fetch();
	}

	//发起提问
	public function question(){
		$uid = input('session.uid');
		//获取用户信息信息
		$userInfo = Db::name('user')->field('realname,mobile')->find($uid);
		$this->assign('userInfo',$userInfo);
		return $this->fetch();
	}


	//收到问题;这里可以结合微信推送发给客服
	public function getQuestion(){
		if(request()->isAjax()){
			if(!input('session.uid')){
				return;
			}
			$data = input('post.');
			foreach ($data as $k => $v) {
				$data[$k] = htmlspecialchars($v);
			}
			$data['uid']=input('session.uid');
			$data['status']='0';
			$data['addtime']=time();
			$res = db('question')->insert($data);
			if($res){
				return ['status'=>1];
			}else{
				return ['status'=>0];
			}
		}
	}
	//我的问题列表
	public function questionList(){
		$uid = input('session.uid');
		$list = model('question')->where('uid',$uid)->field('id,description,status,addtime')->select();
		$this->assign('list',$list);
		return $this->fetch();
	}
	
	//问题详情
	public function questionDetail(){
		$id = input('get.id');
		$detail = model('question')->find($id);
		$this->assign('detail',$detail);
		return $this->fetch();
	}

	public function chinaIdentity(){
		if(request()->isAjax()){


			$appkey = 'b1f8b71775b20937e5ce0d9699301d20';
			$data = input('post.data');
			$url = "http://api.id98.cn/api/idcard?appkey=d10a8e06284cf889deaf93ffb5d9c60a&name=%E9%82%93%E6%B0%B8%E6%9C%9B&cardno=610922197401232578";
			$info=file_get_contents($url);

	        $info=json_decode($info);
	        return $info;

    	}

	}



}
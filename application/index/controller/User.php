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
			if(empty(input('session.uid'))){
				return;
			}
			//先关闭验证
			return 1;

			//这边还要先重新验证一下传过来的信息
			$count = db('user')->where('cardno',$data['cardno'])->count();
			if($count){
				return 0;
			}
			$appkey = 'b1f8b71775b20937e5ce0d9699301d20';
			$data = input('post.');
			
			
			$url = "http://api.id98.cn/api/idcard?appkey=".$appkey."&name=".$data['name']."&cardno=".$data['cardno'];
			// $url = "http://api.id98.cn/api/idcard?appkey=d10a8e06284cf889deaf93ffb5d9c60a&name=邓永望&cardno=610922197401232578";
			$info=file_get_contents($url);
	        $info=json_decode($info,true);
	        /* 返回的数据格式
	        {
			"isok":1,
			"code":1,
			"data":
			{"err":0,
			"address":"\u5c71\u4e1c\u7701\u6cf0\u5b89\u5e02\u5cb1\u5cb3\u533a",
			"sex":"M",
			"birthday":"1986-02-12"}
			}*/
		    
		    if($info['code'] == 1 && $info['isok'] ==1){
		    	//符合
		    	$data1 = [
		    		'address'=>$info['data']['address'],
		    		'sex'=>$info['data']['sex'],
		    		'cardno'=>$data['cardno'],
		    		'realname'=>$data['name'],
		    		'uid'=>input('session.uid'),
		    		'id_status'=>1,
		    	];
		    	$res = db('user')->update($data1);

		    	// if($res){
		    	// 	return 1;
		    	// }else{
		    	// 	return 0;
		    	// }

		    }

    	}
	}

	public function verifyIdentityBindBankCard(){
		if(request()->isAjax()){
			if(empty(input('session.uid'))){
				return 0;
			}
			return 1;
			$appkey = 'b1f8b71775b20937e5ce0d9699301d20';
			$data =  input('post.');
			// $url = "http://api.id98.cn/api/idcard?appkey=".$appkey."&name=".$data['name']."&cardno=".$data['cardno'];
			$url = "http://api.id98.cn/api/v2/bankcard?appkey=d10a8e06284cf889deaf93ffb5d9c60a&name=邓永望&idcardno=610922197401232578&bankcardno=6228481552887309119&tel=18622523252";
			$info = file_get_contents($url);
	        $info = json_decode($info,true);
	        // var_dump($info)

			if($info['isok'] == 1 && $info['code']==1){
				$data1 = [
					'uid'=>input('session.uid'),
					'cid'=>$data['cid'],
					'card_number'=>$data['bankcardno'],
					'addtime'=>time()
				];
				db('ucard')->insert($data1);
				$data2 = [
					'uid'=>input('session.uid'),
					'realname'=>$data['name'],
					'cardno'=>$data['idcardno'],
					'bank_status'=>1,
					'id_status'=>1,
				];
				db('user')->update($data2);


			}	        
	        return $info;


		}
	}


}
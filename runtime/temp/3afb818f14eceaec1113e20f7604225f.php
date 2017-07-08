<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:58:"D:\wamp64\www\sdb/application/index\view\user\chinaid.html";i:1499417833;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\header.html";i:1499405893;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\footer.html";i:1498450778;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>首页</title>

	<script type="text/javascript" src="__PUBLIC__/home/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/home/common.js"></script>
</head>
<style type="text/css">
	.box{
		border: 1px solid #000;
		margin-top: 10px;
		width: 200px;
	}
	nav ul li{
		list-style-type:none;
		float: left;
		margin-right: 20px;
	}
	.clear{
		clear: both;
	}
</style>
<body>
<nav>
	<ul>
		<li><a href="<?php echo url('Index/index'); ?>">首页</a></li>
		<li><a href="<?php echo url('Trade/index'); ?>">交易中心</a></li>
		<li><a href="<?php echo url('Market/index'); ?>">行情图表</a></li>
		<li><a href="<?php echo url('Account/index'); ?>">资金管理</a></li>
		<li><a href="<?php echo url('User/index'); ?>">个人中心</a></li>
	</ul>
</nav>
<div class="clear"></div>
<button type="button" onclick="javascript:history.go(-1);">返回</button>
<hr>
<div>
	<ul>
		<li><a href="<?php echo url('User/index'); ?>">安全中心</a></li>
		<li><a href="<?php echo url('User/chinaID'); ?>">身份认证</a></li>
		<li><a href="<?php echo url('User/notice'); ?>">通知设置</a></li>
		<li><a href="<?php echo url('User/question'); ?>">发起提问</a></li>
		<li><a href="<?php echo url('User/questionList'); ?>">我的问题列表</a></li>
		<li><a href="<?php echo url('User/index'); ?>">FAQ</a></li>
		<li><a href="<?php echo url('User/index'); ?>">论坛</a></li>
	</ul>
</div>

<div>
	<ul>
		<li>姓名：<input type="text" id="name" placeholder="请输入真实名字"></li>
		<li>国籍：<select><option>中国（China）</option></select></li>
		<li>身份证号：<input type="text" id="cardno" placeholder="请输入本人身份证号"></li>
		<li>
			<input type="checkbox" id="realInfoMake" value=""> 我承诺提交的信息属于我本人。  
		</li>
	</ul>
	<button type="button" id="submitChinaIdentityBtn" onclick="javascript:submitChinaIdentity();">提交</button>
	

</div>
<script type="text/javascript">
	function submitChinaIdentity(){
		
		data = {};
		data['name'] = $('#name').val();
		data['cardno'] = $('#cardno').val();
		url = "<?php echo url('User/chinaIdentity'); ?>"
		
		$.post(url, data,
		 function(data) {
			console.log(data);
		});

	} 

</script>
</body>
</html>
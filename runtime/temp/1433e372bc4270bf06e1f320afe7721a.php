<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:56:"D:\wamp64\www\sdb/application/index\view\user\index.html";i:1498456567;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\header.html";i:1498697763;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\footer.html";i:1498450778;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>首页</title>

	<script type="text/javascript" src="__PUBLIC__/home/jquery-3.2.1.min.js"></script>
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
		<li>手机绑定</li>
		<li>资金密码</li>
		<li>邮箱验证</li>
	</ul>
	<div>
		登陆记录
		最近十次登陆
	</div>

</div>
</body>
</html>
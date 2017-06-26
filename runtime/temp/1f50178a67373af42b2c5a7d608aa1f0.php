<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:59:"D:\wamp64\www\sdb/application/index\view\user\question.html";i:1498456941;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\header.html";i:1498457073;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\footer.html";i:1498450778;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>首页</title>
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
		margin-left: 10px;
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
	发起提问

	<div>
		问题描述：<input type="text" name="">
	</div>
	<div>
		截图：<input type="file" name="">
	</div>
	<div>
		姓名：<input type="text" name="">
	</div>
	<div>
		联系电话：<input type="text" name="">
	</div>


</div>
</body>
</html>
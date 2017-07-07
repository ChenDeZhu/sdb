<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:59:"D:\wamp64\www\sdb/application/index\view\user\question.html";i:1499310298;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\header.html";i:1499405893;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\footer.html";i:1498450778;}*/ ?>
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
	发起提问
<form id="sendQuestion">
	<div>
		问题描述：<textarea name="description" style="width: 300px;height:200px; "></textarea>
	</div>
	<div>
		截图：<input type="file" name="img">
	</div>
	<div>
		姓名：<input type="text" name="name" value="<?php echo $userInfo['realname']; ?>">
	</div>
	<div>
		联系电话：<input type="text" name="mobile" value="<?php echo $userInfo['mobile']; ?>">
	</div>
	<button type="button" id="sendQ">提交</button>
</form>
<script type="text/javascript">


$('#sendQ').click(function(event) {
	var data = $("#sendQuestion").serializeArray();

    postData = {};
    $(data).each(function(i){
       postData[this.name] = this.value;
    });
    
    $.post("<?php echo url('User/getQuestion'); ?>", postData, function(res) {
    	if(res['status']==1){
    		alert('成功');
    		
    	}else{
    		alert('失败');
    	}
    });

});
	
</script>

</div>
</body>
</html>
<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:57:"D:\wamp64\www\sdb/application/index\view\index\index.html";i:1498609114;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\header.html";i:1499133169;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\footer.html";i:1498450778;}*/ ?>
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
	<div>
	<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
			<div class="box">
				<div>货币名称：<?php echo $v['name']; ?></div>
				<div>最新成交价：<?php echo $v['recently']; ?></div>
				<div>今日最高：<?php echo $v['max']; ?></div>
				<div>今日最低：<?php echo $v['min']; ?></div>
				<div>今日涨跌幅：</div>
				<div>24小时成交量：<?php echo $v['count']; ?></div>
			</div>
	<?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
	
	<script type="text/javascript"> 
        // 假设服务端ip为127.0.0.1  
            var ws = new WebSocket('ws://127.0.0.1:2347');
            ws.onopen = function(){
                var uid = 'uid1';
                // ws.send(uid);
                // console.log(uid);
            };
            ws.onmessage = function(e){
            	
                console.log('11');
            };
            </script>
</body>
</html>
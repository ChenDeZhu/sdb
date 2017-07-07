<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:60:"D:\wamp64\www\sdb/application/index\view\account\record.html";i:1499331624;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\header.html";i:1499405893;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\footer.html";i:1498450778;}*/ ?>
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
资金管理
<div>
	<ul>
		<li><a href="<?php echo url('Account/index'); ?>">充值</a></li>
		<li><a href="<?php echo url('Account/withdrawCny'); ?>">提现</a></li>
		<li><a href="<?php echo url('Account/record'); ?>">明细</a></li>
		<li><a href="tencent://message/?uin=420021436&amp;Site=http://www.muye3.com&amp;Menu=yes">在线咨询</a></li>
	</ul>
</div>
账单明细
<table>
	<thead>
		<tr>
			<td>成交时间</td>
			<td>类型</td>
			<td>数量</td>
			<td>手续费</td>
		</tr>
	</thead>
	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
		<tr>
			<td><?php echo date('Y-m-d H:i:s',$vo['addtime']); ?></td>
			<td><?php echo getDType($vo['type']); ?></td>
			<td><?php echo $vo['number']; ?></td>
			<td><?php echo $vo['poundage']; ?></td>
		</tr>
	<?php endforeach; endif; else: echo "" ;endif; ?>
</table>



</body>
</html>
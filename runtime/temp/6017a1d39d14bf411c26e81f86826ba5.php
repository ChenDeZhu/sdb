<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:58:"D:\wamp64\www\sdb/application/index\view\market\index.html";i:1498553513;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\header.html";i:1498457170;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\footer.html";i:1498450778;}*/ ?>
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
行情图表


<div>

	价格：<input type="text" name="" placeholder="请输入价格" id="price">
	数量：<input type="text" name="" placeholder="请输入数量" id="number">
	金额：
	<div>
		<button type="button" id="buy">
			买入
		</button>
		<button type="button" id="sale">
			卖出
		</button>
	</div>
</div>




	<script type="text/javascript"> 
        // 假设服务端ip为127.0.0.1  
            var ws = new WebSocket('ws://127.0.0.1:2347');
            ws.onopen = function(){
			   console.log("连接成功");
            };
            ws.onmessage = function(e){
                console.log(e.data);
            };
            document.getElementById('buy').addEventListener('click',function(){

            	data = {}
            	data['price'] = document.getElementById('price').value;
            	data['number'] = document.getElementById('number').value;
            	// console.log(data);
            	data = JSON.stringify(data);
	            ws.send(data); 
	            
	        })
    </script>
    
</body>
</html>
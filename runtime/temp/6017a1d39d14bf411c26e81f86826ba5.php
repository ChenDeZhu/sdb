<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:58:"D:\wamp64\www\sdb/application/index\view\market\index.html";i:1498898576;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\header.html";i:1498697763;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\footer.html";i:1498450778;}*/ ?>
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
行情图表
<style type="text/css" >

.box{
	border: 1px solid #000;
	float: left;
	margin-left: 20px;
}
.box td{
	text-align: center;
	border-bottom: 1px solid #ccc;
}
</style>

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
<hr>
<div class="weituo">
	委托信息
	<p>可用资产：<span id="money"><?php echo $money; ?></span>￥</p>
	<p>可用币种数量：<span id="currency"><?php echo $currency; ?></span></p>

	<p>冻结资产：<span id="dmoney"><?php echo $dmoney; ?></span>￥</p>
	<p>冻结币种数量：<span id="dcurrency"><?php echo $dcurrency; ?></span></p>
	<div>
		<table class="box">
			<thead>
				<tr>
					
					<th>买入价(￥)</th>
					<th>需求总共买入量</th>
					<th>剩余</th>
				</tr>
			</thead>
			<tbody class="buy">
				<?php if(is_array($list1) || $list1 instanceof \think\Collection || $list1 instanceof \think\Paginator): $i = 0; $__LIST__ = $list1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
				<tr>
					<td><?php echo $v['price']; ?></td>
					<td><?php echo $v['number']; ?></td>
					<td id="<?php echo $v['id']; ?>"><?php echo $v['number_no']; ?></td>
				</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
	</div>
	<div>
		<table class="box">
			<thead>
				<tr>
					<th>卖出价(￥)</th>
					<th>需求总共卖出量</th>
					<th>剩余</th>
				</tr>
			</thead>
			<tbody class="sale">
				<?php if(is_array($list2) || $list2 instanceof \think\Collection || $list2 instanceof \think\Paginator): $i = 0; $__LIST__ = $list2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
				<tr>
					<td><?php echo $v['price']; ?></td>
					<td><?php echo $v['number']; ?></td>
					<td id="<?php echo $v['id']; ?>"><?php echo $v['number_no']; ?></td>
				</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
	</div>

</div>
	<script type="text/javascript"> 
        // 假设服务端ip为127.0.0.1  
            var ws = new WebSocket('ws://127.0.0.1:2347');
            ws.onopen = function(){
			   console.log("连接成功");
            };
            var data1 = '';
            ws.onmessage = function(e){
            	data1 = JSON.parse(e.data);
            	console.log(data1);
                switch(data1['type']){
                	
                	case 1:	
                		//添加卖出委托信息
                		html = '';
		                html+='<tr><td>'+data1["price"]+'</td><td>'+data1["number"]+'</td><td id="'+data1['id']+'">'+data1["number_no"]+'</td></tr>'
		                $('.sale').prepend(html);
                		break;
                	

                	case 8:
                		
		            	//修改可用货币            		
                		var currency = $('#currency').html();
                		currency = Number(currency)-Number(data1['number_no']); 		
                		$('#currency').html(currency);        		
                		
                		$('#money').html(money);
                		//修改冻结货币
                		var dcurrency = $('#dcurrency').html();
                		dcurrency = Number(dcurrency)+Number(data1['number_no']);
                		
                		$('#dcurrency').html(dcurrency);
                		break;





                	
                	case 4:
                		//资产修改
                		var money = $('#money').html();
                		money = money-data1['rmoney'];
                		var dmoney = $('#dmoney').html();
                		dmoney = Number(dmoney)-0+Number(data1['rmoney']);
                		$('#money').html(money);
                		$('#dmoney').html(dmoney);
                		var money = $('#money').html();
                		money = Number(money) - Number(data1['price'])
                		break;    	
                	case 5:
                		//可用货币修改
                		var currency = $('#currency').html();
                		currency = currency-data1['rcurrency'];
                		$('#currency').html(currency); 
                		break;
                	case 6:
                		//冻结货币修改
                		var currency = $('#currency').html();
                		currency = currency-data1['rcurrency'];
                		var dcurrency = $('#dcurrency').html();
                		dcurrency = Number(dcurrency)+Number(data1['rcurrency']);
                		$('#currency').html(currency);
                		$('#dcurrency').html(dcurrency);
                		break;

 /*-------------------------------------------------------------------------------------------*/               		
                	//买方交易行为
                	case 0:
                		//添加买入委托信息
                		html = '';
		                html+='<tr><td>'+data1["price"]+'</td><td>'+data1["number"]+'</td><td id="'+data1['id']+'">'+data1["number_no"]+'</td></tr>'
		                $('.buy').prepend(html);
                		break;
            		case 2:
            		//修改委托信息
            		$("#"+data1['id']).html(data1['number_no']);
            		break;
                	case 3:
                		//可用资产修改
                		var money = $('#money').html();
                		money = Number(money)-(Number(data1['price'])*Number(data1['number']));
                		$('#money').html(Number(money));
                		//可用货币修改
                		var currency = $('#currency').html();
                		currency = Number(currency)-Number(data1['number']);
                		$('#currency').html(currency); 
                		break;
		            case 9:
		            	//修改可用货币            		
                		var currency = $('#currency').html();
                		currency = Number(currency)+Number(data1['number'])-Number(data1['number_no']); 		
                		$('#currency').html(currency);        		
                		//可用资产修改
                		var money = $('#money').html();
                		money = Number(money)-Number(data1['price'])*Number(data1['number']);
                		$('#money').html(money);
                		//修改冻结资金
                		var dmoney = $('#dmoney').html();
                		dmoney = Number(dmoney)+Number(data1['number_no'])*Number(data1['price']);
                		
                		$('#dmoney').html(dmoney);
                		break;
                	case 10:
                		$("#"+data1['s']['id']).html(data1['s']['number_no']);
                		html = '';
		                html+='<tr><td>'+data1['b']["price"]+'</td><td>'+data1['b']["number"]+'</td><td id="'+data1['b']['id']+'">'+data1['b']["number_no"]+'</td></tr>'
		                $('.buy').prepend(html);
		                break;
/*-------------------------------------------------------------------------------------------*/ 
                	default:
                		break;
                }
            };
            document.getElementById('buy').addEventListener('click',function(){
            	data = {}
            	data['price'] = document.getElementById('price').value;
            	data['number_no'] = document.getElementById('number').value;
            	//type 0表示买入委托
            	data['type'] = 0;
            	data['bid'] = 1;
            	if(!data['price']){
            		alert('请输出价格');
            		return;
            	}
            	if(!data['number_no']){
            		alert('请输出数量');
            		return;
            	}
            	// console.log(data);
            	data = JSON.stringify(data);
	            ws.send(data); 
	        })
            $('#sale').click(function(event) {
            	data = {};
            	data['bid'] = 1;
            	data['price'] = $('#price').val();
            	data['number_no'] = $('#number').val();
            	//type1：表示卖出委托
            	data['type'] = 1;
            	if(!data['price']){
            		alert('请输出价格');
            		return;
            	}
            	if(!data['number_no']){
            		alert('请输出数量');
            		return;
            	}
            	data = JSON.stringify(data);
	            ws.send(data);
            });
    </script>
    
</body>
</html>
<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:58:"D:\wamp64\www\sdb/application/index\view\market\index.html";i:1499243810;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\header.html";i:1499133169;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\footer.html";i:1498450778;}*/ ?>
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
行情图表
<style type="text/css" >

.box{
	border: 1px solid #000;
	float: left;
	margin-left: 20px;
	width: 20%;
}
.box td{
	text-align: center;
	border-bottom: 1px solid #ccc;
}
.deal3{
	color:green;
}
.deal4{
	color:red;
}
</style>

<div>

	价格：<input type="text" name="" placeholder="请输入价格" id="price">
	数量：<input type="text" name="" placeholder="请输入数量" id="number">
	金额：
	<div>
		<button type="button" id="buy" class="dealBtn" date-type='0'>
			买入
		</button>
		<button type="button" id="sale" class="dealBtn" date-type='1'>
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
				<tr><th colspan="3">买入委托</th></tr>
				<tr>
					<th>买入价(￥)</th>
					<th>剩余需求</th>
				</tr>
			</thead>
			<tbody class="buy">
				<?php if(is_array($list1) || $list1 instanceof \think\Collection || $list1 instanceof \think\Paginator): $i = 0; $__LIST__ = $list1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
				<tr>
					<td><?php echo $v['price']; ?></td>
					<td><?php echo $v['number']; ?></td>
				</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
	</div>
	<div>
		<table class="box">
			<thead>
				<tr><th colspan="3">卖出委托</th></tr>
				<tr>
					<th>卖出价(￥)</th>
					<th>剩余需求</th>
				</tr>
			</thead>
			<tbody class="sale">
				<?php if(is_array($list2) || $list2 instanceof \think\Collection || $list2 instanceof \think\Paginator): $i = 0; $__LIST__ = $list2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
				<tr>
					<td><?php echo $v['price']; ?></td>
					<td><?php echo $v['number']; ?></td>
				</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
	</div>
	<div>
		<table class="box">
			<thead>
			<tr>
			<th colspan="3">最新成交</th>
			</tr>
				<tr>
					<th>交易时间(￥)</th>
					<th>成交价格</th>
					<th>成交量</th>
				</tr>
			</thead>
			<tbody class="deal">
				<?php if(is_array($list3) || $list3 instanceof \think\Collection || $list3 instanceof \think\Paginator): $i = 0; $__LIST__ = $list3;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
				<tr>
					<td><?php echo date("H:i:s",$v['addtime']); ?></td>
					<td><?php echo $v['money']; ?></td>
					<td class="deal<?php echo $v['type']; ?>"><?php echo $v['number']; ?></td>
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
                	//买方交易行为
                	case 0:
                		//添加买入委托信息
                		add('buy');
                		
                		
                		break;
                	case 1:	
                		//添加卖出委托信息
                		add('sale');
                		break;
            		case 2:
            			//修改委托信息
            			$("#"+data1['id']).html(data1['number_no']);
            			//添加到交易记录
            			html = '';
            		  break;
            		//自己的买入委托单完成
                	case 3:
                		//可用资产修改
                		doneSelf(0);
                		break;
                	//自己的卖出委托单完成
                	case 4:
                		//可用资产修改
                		doneSelf(1);
                		break;
                	//不存在符合条件的买单或部分完成
                	case 8:
                        comPart(0);
                		break;
                	//不存在符合条件的卖单或交易部分完成
		            case 9:
                        comPart(1);
                		break;
                		//自己的买入委托单完成
                	case 10:
                        // doneSelfAll(3);
                        add('sale');
                        html1 = '<tr><td>'+data1["time"]+'</td><td>'+data1['data']['price']+'</td><td class="deal3">'+data1['data']['number_no']+'</td></tr>'
                        $(".deal").prepend(html1);
		                break;
		                //自己的卖出委托单完成
                	case 11:
		                // doneSelfAll(4);
		                add('buy');
                        html1 = '<tr><td>'+data1["time"]+'</td><td>'+data1['data']['price']+'</td><td class="deal4">'+data1['data']['number_no']+'</td></tr>'
                        $(".deal").prepend(html1);
		                break;

		            case 12:
		            	add('sale');
		            	add('buy');
		            	html1 = '<tr><td>'+data1["time"]+'</td><td>'+data1['data']['price']+'</td><td class="deal3">'+data1['noNumber']+'</td></tr>'
                        $(".deal").prepend(html1);
		            	break;
		            case 13:
		            	add('sale');
		            	add('buy');
		            	html1 = '<tr><td>'+data1["time"]+'</td><td>'+data1['data']['price']+'</td><td class="deal4">'+data1['noNumber']+'</td></tr>'
                        $(".deal").prepend(html1);
		            	break;

/*-------------------------------------------------------------------------------------------*/ 
                	default:
                		break;
                }
            };      
            $('.dealBtn').click(function(event) {
            	type = $(this).attr('date-type');
            	data = {};
            	data['bid'] = 1;
            	data['price'] = $('#price').val();
            	data['number_no'] = $('#number').val();
            	//type1：表示卖出委托
            	data['type'] = type;
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
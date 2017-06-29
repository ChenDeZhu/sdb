<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:58:"D:\wamp64\www\sdb/application/index\view\market\index.html";i:1498721961;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\header.html";i:1498697763;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\footer.html";i:1498450778;}*/ ?>
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
				<tr >
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
                
                // //添加挂单
                // if(data1['status'] ==1){
                
                	
                // }else if(data1['status']==2){
                // 	//更新卖单
           		

                // }

                switch(data1['type']){
                	case 0:
                		//添加买入委托信息
                		html = '';
		                html+='<tr id="'+data1['id']+'"><td>'+data1["price"]+'</td><td>'+data1["number"]+'</td><td>'+data1["number_no"]+'</td></tr>'
		                $('.buy').prepend(html);
                		break;
                	case 1:
                		//添加卖出委托信息
                		html = '';
		                html+='<tr><td>'+data1["price"]+'</td><td>'+data1["number"]+'</td><td id="'+data1['id']+'">'+data1["number_no"]+'</td></tr>'
		                $('.sale').prepend(html);
                		break;
                	case 2:
                		//修改委托信息
                		$("#"+data1['id']).html(data1['number_no']);
                		break;
                	
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
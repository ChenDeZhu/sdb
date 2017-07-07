<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:57:"D:\wamp64\www\sdb/application/index\view\trade\start.html";i:1499389610;}*/ ?>
<!DOCTYPE html>
<html class="dark"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 

<meta name="applicable-device" content="pc">

<title>18100 OKCoin BTC/CNY - OKCoin</title>

<link href="__PUBLIC__/home/app_1.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/home/font-awesome.css" rel="stylesheet" type="text/css">

</head>
<body>
<div id="header_outer"><div id="header">

	<div class="navbar navbar-static-top"><div class="navbar-inner"><div class="container">

		<div id="warning"></div>
	</div></div></div>
	<div id="qr"><img></div>

	<div id="control"><div class="inner"><ul id="periods" class="horiz"><li style="line-height:26px">K线时间段:</li><li class="period"><a>1周</a></li><li class="subsep"></li><li class="period"><a>3天</a></li><li class="period"><a>1天</a></li><li class="subsep"></li><li class="period"><a>12小时</a></li><li class="period"><a>6小时</a></li><li class="period"><a>4小时</a></li><li class="period"><a>2小时</a></li><li class="period"><a>1小时</a></li><li class="subsep"></li><li class="period selected"><a>30分</a></li><li class="period"><a>15分</a></li><li class="period"><a>5分</a></li><li class="period"><a>3分</a></li><li class="period"><a>1分</a></li><li class="subsep"></li><li>
	<div class="dropdown">
	<div class="t">
		页面设置
	</div>
	<div class="dropdown-data"><table class="nowrap simple settings">
		<tbody><tr class="main_lines">
			<td>均线设置</td>
			<td><ul id="setting_main_lines">
			<li value="mas" class="active">MA</li>
			<li value="emas">EMA</li>
			<li value="none">关闭均线</li></ul></td>
		</tr>
		<tr class="stick_style">
			<td>图线样式</td>
			<td><ul id="setting_stick_style">
			<li value="candle_stick" class="active">K线-OHLC</li>
			<li value="candle_stick_hlc">K线-HLC</li>
			<li value="ohlc">OHLC</li>
			<li value="line">单线</li>
			<li value="line_o">单线-o</li>
			<li value="none">关闭线图</li>
			</ul></td>
		</tr>
		<tr class="line_style" style="display: none;">
			<td>Line Style</td>
			<td><ul id="setting_ls">
			<li value="c" class="active">Close</li>
			<li value="m">Median Price</li>
			</ul></td>
		</tr>
		<tr class="indicator">
			<td>技术指标</td>
			<td><ul id="setting_indicator">
			<li value="macd">MACD</li>
			<li value="kdj">KDJ</li>
			<li value="stoch_rsi">StochRSI</li>
			<li value="none" class="active">关闭指标</li>
			</ul></td>
		</tr>
		<tr class="scale">
			<td>线图比例</td>
			<td><ul id="setting_scale">
			<li value="normal" class="active">普通K线</li>
			<li value="logarithmic">对数K线</li>
			</ul></td>
		</tr>
	<!-- 	<tr class=theme>
			<td>Theme</td>
			<td><ul id="setting_theme"><li value=dark>Dark</li><li value=light>Light</li></ul></td>
		</tr> -->
		<tr>
			<td></td>
			<td colspan="2"><a id="btn_settings">指标参数设置</a></td>
		</tr>
	</tbody></table></div>
</div>
<div class="dropdown">
	<div class="t">
		工具
	</div>

	<div class="dropdown-data"><table class="nowrap simple">
		<tbody><tr><td><a class="link_estimate_trading">Estimate Trading</a></td></tr>
		<tr><td>
			<a class="mode" mode="draw_line">Draw Lines</a><br>
			<a class="mode" mode="draw_fhline">Draw Fibonacci Retracements</a>
			<br><a class="mode" mode="draw_ffan">Draw Fibonacci Fans</a><br>
			<a class="mode" mode="draw_fhlineex">Draw Fibonacci Extensions</a>
			<div class="hint">
				点击左键画点/线
				<br>点击右键清除
			</div>
		</td></tr>
	</tbody></table></div>
</div></li><li class="sep"></li><li id="mode">
	<a id="mode_cross" class="mode selected" title="Cross Cursor" mode="cross"><img src="__PUBLIC__/home/shape-cross.png"></a><a id="mode_draw_line" class="mode" title="Draw lines" mode="draw_line"><img src="__PUBLIC__/home/shape-line.png"></a><a id="mode_draw_fhline" class="mode" title="Draw Fibonacci Retracements" mode="draw_fhline"><img src="__PUBLIC__/home/shape-fr.png"></a><a id="mode_draw_ffan" class="mode" title="Draw Fibonacci Fans" mode="draw_ffan"><img src="__PUBLIC__/home/shape-ffan.png"></a>
</li><li class="sep"></li><li>
	已更新
	<span id="change">7</span>
	秒
	<span id="realtime_error" style="display:none">in
	<abbr title="Realtime(WebSocket) connection failed. Orderbook update every 1 minute, Trades update every 10 seconds.">Slow Mode</abbr></span>
</li>
</ul></div></div>
</div></div>


<div id="loading" style="display: none;"><div class="inner"><div class="text">加载中...</div></div></div>
<div id="notify" class="notify"><div class="inner"></div></div>
<div id="main" style="display: block; height: 571px;">
	<div id="sidebar_outer" style="display: none;"><div id="sidebar" style="display:none">
		<div id="before_trades" style="display: none;">
			<div id="market"></div>
			<div id="orderbook"><div class="orderbook" style="height: 195px;"><div id="asks" style="margin-top: 0px;"><div class="table"><div class="row"><span class="price">18170.0<g>00</g></span> <span class="amount">0<g>.18</g><g>00</g></span></div><div class="row"><span class="price">18169.97<g>0</g></span> <span class="amount">0<g>.02</g><g>00</g></span></div><div class="row"><span class="price">18160.0<g>00</g></span> <span class="amount">2<g>.485</g><g>0</g></span></div><div class="row"><span class="price">18159.97<g>0</g></span> <span class="amount">0<g>.08</g><g>00</g></span></div><div class="row"><span class="price">18150.0<g>00</g></span> <span class="amount">4<g>.813</g><g>0</g></span></div><div class="row"><span class="price">18149.99<g>0</g></span> <span class="amount">0<g>.101</g><g>0</g></span></div><div class="row"><span class="price">18148.88<g>0</g></span> <span class="amount">0<g>.199</g><g>0</g></span></div><div class="row"><span class="price">18134.25<g>0</g></span> <span class="amount">0<g>.285</g><g>0</g></span></div><div class="row"><span class="price">18130.0<g>00</g></span> <span class="amount">0<g>.109</g><g>0</g></span></div><div class="row"><span class="price">18120.0<g>00</g></span> <span class="amount">0<g>.01</g><g>00</g></span></div><div class="row"><span class="price">18111.0<g>00</g></span> <span class="amount">0<g>.001</g><g>0</g></span></div><div class="row"><span class="price">18110.99<g>0</g></span> <span class="amount">0<g>.262</g><g>0</g></span></div><div class="row"><span class="price">18102.0<g>00</g></span> <span class="amount">0<g>.15</g><g>00</g></span></div><div class="row"><span class="price">18100.01<g>0</g></span> <span class="amount">0<g>.06</g><g>00</g></span></div><div class="row"><span class="price">18099.9<g>00</g></span> <span class="amount">1<g>.423</g><g>0</g></span></div></div></div><div id="gasks" style="margin-top: 0px;"><div class="table"><div class="row"><span class="price">18300</span> <span class="amount">213</span></div><div class="row"><span class="price">18250</span> <span class="amount">208</span></div><div class="row"><span class="price">18200</span> <span class="amount">129</span></div><div class="row"><span class="price">18150</span> <span class="amount">7</span></div><div class="row"><span class="price">18100</span> <span class="amount">1</span></div></div></div></div><div id="price" class="red">18100</div><div class="orderbook" style="height: 195px;"><div id="bids"><div class="table"><div class="row"><span class="price">18070.0<g>00</g></span> <span class="amount">0<g>.185</g><g>0</g></span></div><div class="row"><span class="price">18060.0<g>00</g></span> <span class="amount">2<g>.424</g><g>0</g></span></div><div class="row"><span class="price">18059.0<g>00</g></span> <span class="amount">2<g>.314</g><g>0</g></span></div><div class="row"><span class="price">18055.0<g>00</g></span> <span class="amount">0<g>.212</g><g>0</g></span></div><div class="row"><span class="price">18050.0<g>00</g></span> <span class="amount">23<g>.61</g><g>0</g></span></div><div class="row"><span class="price">18040.0<g>00</g></span> <span class="amount">0<g>.082</g><g>0</g></span></div><div class="row"><span class="price">18030.0<g>00</g></span> <span class="amount">0<g>.03</g><g>00</g></span></div><div class="row"><span class="price">18029.03<g>0</g></span> <span class="amount">2<g>.8</g><g>000</g></span></div><div class="row"><span class="price">18028.78<g>0</g></span> <span class="amount">0<g>.2</g><g>000</g></span></div><div class="row"><span class="price">18025.77<g>0</g></span> <span class="amount">0<g>.285</g><g>0</g></span></div><div class="row"><span class="price">18022.0<g>00</g></span> <span class="amount">0<g>.03</g><g>00</g></span></div><div class="row"><span class="price">18020.76<g>0</g></span> <span class="amount">0<g>.7</g><g>000</g></span></div><div class="row"><span class="price"><h>18020.</h>03<g>0</g></span> <span class="amount">0<g>.2</g><g>000</g></span></div><div class="row"><span class="price"><h>18020.</h>0<g>00</g></span> <span class="amount">22<g>.05</g><g>0</g></span></div><div class="row"><span class="price">18015.0<g>00</g></span> <span class="amount">0<g>.05</g><g>00</g></span></div></div></div><div id="gbids"><div class="table"><div class="row"><span class="price">18050</span> <span class="amount">29</span></div><div class="row"><span class="price">18000</span> <span class="amount">65</span></div><div class="row"><span class="price">17950</span> <span class="amount">88</span></div><div class="row"><span class="price">17900</span> <span class="amount">127</span></div><div class="row"><span class="price">17850</span> <span class="amount">153</span></div><div class="row"><span class="price">17800</span> <span class="amount">161</span></div></div></div></div></div>
		</div>

		<div id="trades" style="height: 558px;"><div class="row"><div class="v">0<g>.091</g></div><div class="t">08:36:41</div><div class="p red">18100</div></div><div class="row"><div class="v">5<g>.473</g></div><div class="t">08:36:11</div><div class="p green">18111</div></div><div class="row"><div class="v">2<g>.809</g></div><div class="t">08:36:09</div><div class="p green">18111</div></div><div class="row"><div class="v">16<g>.092</g></div><div class="t">08:35:21</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.106</g></div><div class="t">08:34:17</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.452</g></div><div class="t">08:34:10</div><div class="p red">18110</div></div><div class="row"><div class="v">12<g>.466</g></div><div class="t">08:33:42</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.251</g></div><div class="t">08:33:36</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.1</g></div><div class="t">08:33:18</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.047</g></div><div class="t">08:33:12</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.072</g></div><div class="t">08:33:07</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.213</g></div><div class="t">08:32:59</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.08</g></div><div class="t">08:32:45</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.966</g></div><div class="t">08:32:39</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.045</g></div><div class="t">08:32:34</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.218</g></div><div class="t">08:32:21</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.115</g></div><div class="t">08:30:46</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.01</g></div><div class="t">08:30:29</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.011</g></div><div class="t">08:30:21</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.1</g></div><div class="t">08:30:09</div><div class="p red">18110</div></div><div class="row"><div class="v">1<g>.124</g></div><div class="t">08:30:00</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.153</g></div><div class="t">08:29:54</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.08</g></div><div class="t">08:29:48</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.864</g></div><div class="t">08:29:43</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.025</g></div><div class="t">08:29:37</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.639</g></div><div class="t">08:29:31</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.387</g></div><div class="t">08:29:26</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.19</g></div><div class="t">08:29:20</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.366</g></div><div class="t">08:29:17</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.015</g></div><div class="t">08:29:14</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.35</g></div><div class="t">08:29:09</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.016</g></div><div class="t">08:29:03</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.18</g></div><div class="t">08:29:00</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.04</g></div><div class="t">08:28:48</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.1</g></div><div class="t">08:28:38</div><div class="p red">18110</div></div><div class="row"><div class="v">1<g>.383</g></div><div class="t">08:28:33</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.03</g></div><div class="t">08:28:24</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.167</g></div><div class="t">08:28:18</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.08</g></div><div class="t">08:28:13</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.688</g></div><div class="t">08:28:11</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.109</g></div><div class="t">08:27:54</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.017</g></div><div class="t">08:27:41</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.079</g></div><div class="t">08:27:34</div><div class="p red">18110</div></div><div class="row"><div class="v">3<g></g></div><div class="t">08:27:16</div><div class="p red">18110</div></div><div class="row"><div class="v">1<g>.572</g></div><div class="t">08:27:04</div><div class="p red">18110</div></div><div class="row"><div class="v">1<g>.004</g></div><div class="t">08:26:23</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.049</g></div><div class="t">08:25:52</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.01</g></div><div class="t">08:25:04</div><div class="p green">18120</div></div><div class="row"><div class="v">0<g>.9</g></div><div class="t">08:24:07</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.08</g></div><div class="t">08:23:49</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.056</g></div><div class="t">08:23:39</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.399</g></div><div class="t">08:23:25</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.701</g></div><div class="t">08:22:55</div><div class="p green">18112</div></div><div class="row"><div class="v">0<g>.141</g></div><div class="t">08:22:52</div><div class="p green">18112</div></div><div class="row"><div class="v">0<g>.015</g></div><div class="t">08:22:45</div><div class="p green">18112</div></div><div class="row"><div class="v">0<g>.024</g></div><div class="t">08:22:33</div><div class="p green">18112</div></div><div class="row"><div class="v">0<g>.142</g></div><div class="t">08:22:06</div><div class="p red">18111</div></div><div class="row"><div class="v">0<g>.258</g></div><div class="t">08:21:54</div><div class="p red">18111</div></div><div class="row"><div class="v">0<g>.031</g></div><div class="t">08:21:28</div><div class="p red">18111</div></div><div class="row"><div class="v">0<g>.09</g></div><div class="t">08:21:17</div><div class="p red">18111</div></div><div class="row"><div class="v">0<g>.01</g></div><div class="t">08:21:09</div><div class="p red">18111</div></div><div class="row"><div class="v">4<g>.049</g></div><div class="t">08:19:17</div><div class="p red">18111</div></div><div class="row"><div class="v">0<g>.02</g></div><div class="t">08:19:15</div><div class="p green">18129.9</div></div><div class="row"><div class="v">0<g>.1</g></div><div class="t">08:18:23</div><div class="p green">18121</div></div><div class="row"><div class="v">0<g>.635</g></div><div class="t">08:18:05</div><div class="p green">18121</div></div><div class="row"><div class="v">1<g>.326</g></div><div class="t">08:17:52</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.242</g></div><div class="t">08:17:43</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.01</g></div><div class="t">08:17:28</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.055</g></div><div class="t">08:17:18</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.013</g></div><div class="t">08:17:12</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.01</g></div><div class="t">08:17:07</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.016</g></div><div class="t">08:16:57</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.037</g></div><div class="t">08:16:49</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.15</g></div><div class="t">08:16:42</div><div class="p green">18148.8</div></div><div class="row"><div class="v">0<g>.016</g></div><div class="t">08:16:37</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.012</g></div><div class="t">08:15:55</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.014</g></div><div class="t">08:15:44</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.036</g></div><div class="t">08:15:35</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.02</g></div><div class="t">08:15:11</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.054</g></div><div class="t">08:15:02</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.012</g></div><div class="t">08:13:46</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.043</g></div><div class="t">08:13:39</div><div class="p red">18121</div></div><div class="row"><div class="v">0<g>.337</g></div><div class="t">08:13:22</div><div class="p green">18148.88</div></div><div class="row"><div class="v">0<g>.039</g></div><div class="t">08:11:43</div><div class="p green">18120</div></div><div class="row"><div class="v">0<g>.032</g></div><div class="t">08:10:49</div><div class="p red">18112</div></div><div class="row"><div class="v">0<g>.029</g></div><div class="t">08:10:42</div><div class="p red">18112</div></div><div class="row"><div class="v">0<g>.096</g></div><div class="t">08:10:36</div><div class="p red">18112</div></div><div class="row"><div class="v">0<g>.029</g></div><div class="t">08:10:30</div><div class="p red">18112</div></div><div class="row"><div class="v">0<g>.027</g></div><div class="t">08:10:24</div><div class="p red">18112</div></div><div class="row"><div class="v">0<g>.071</g></div><div class="t">08:10:18</div><div class="p red">18112</div></div><div class="row"><div class="v">0<g>.878</g></div><div class="t">08:10:13</div><div class="p red">18112</div></div><div class="row"><div class="v">0<g>.972</g></div><div class="t">08:08:19</div><div class="p green">18150</div></div><div class="row"><div class="v">3<g>.1</g></div><div class="t">08:08:15</div><div class="p green">18150</div></div><div class="row"><div class="v">1<g>.115</g></div><div class="t">08:07:55</div><div class="p red">18125</div></div><div class="row"><div class="v">0<g>.037</g></div><div class="t">08:07:51</div><div class="p green">18129.94</div></div><div class="row"><div class="v">0<g>.028</g></div><div class="t">08:07:24</div><div class="p green">18124.96</div></div><div class="row"><div class="v">0<g>.06</g></div><div class="t">08:06:37</div><div class="p green">18122.48</div></div><div class="row"><div class="v">0<g>.011</g></div><div class="t">08:06:18</div><div class="p green">18122.48</div></div><div class="row"><div class="v">0<g>.292</g></div><div class="t">08:05:58</div><div class="p green">18115</div></div><div class="row"><div class="v">0<g>.018</g></div><div class="t">08:04:07</div><div class="p green">18120</div></div><div class="row"><div class="v">0<g>.084</g></div><div class="t">08:03:23</div><div class="p green">18119.96</div></div><div class="row"><div class="v">0<g>.01</g></div><div class="t">08:03:04</div><div class="p green">18115</div></div><div class="row"><div class="v">0<g>.1</g></div><div class="t">08:02:26</div><div class="p green">18113</div></div><div class="row"><div class="v">0<g>.018</g></div><div class="t">08:02:07</div><div class="p green">18112.5</div></div><div class="row"><div class="v">0<g>.014</g></div><div class="t">08:01:44</div><div class="p green">18111</div></div><div class="row"><div class="v">0<g>.099</g></div><div class="t">08:01:38</div><div class="p green">18111</div></div><div class="row"><div class="v">0<g>.061</g></div><div class="t">08:01:32</div><div class="p green">18111</div></div><div class="row"><div class="v">0<g>.127</g></div><div class="t">08:01:28</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.027</g></div><div class="t">08:01:10</div><div class="p green">18115</div></div><div class="row"><div class="v">0<g>.14</g></div><div class="t">08:00:44</div><div class="p green">18115</div></div><div class="row"><div class="v">0<g>.073</g></div><div class="t">08:00:38</div><div class="p green">18115</div></div><div class="row"><div class="v">0<g>.07</g></div><div class="t">08:00:32</div><div class="p green">18115</div></div><div class="row"><div class="v">0<g>.097</g></div><div class="t">08:00:27</div><div class="p green">18115</div></div><div class="row"><div class="v">0<g>.037</g></div><div class="t">08:00:21</div><div class="p green">18115</div></div><div class="row"><div class="v">0<g>.042</g></div><div class="t">08:00:17</div><div class="p green">18114.98</div></div><div class="row"><div class="v">0<g>.069</g></div><div class="t">08:00:15</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.155</g></div><div class="t">08:00:10</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.029</g></div><div class="t">07:59:59</div><div class="p red">18110</div></div><div class="row"><div class="v">2<g>.503</g></div><div class="t">07:58:56</div><div class="p red">18110</div></div><div class="row"><div class="v">6<g></g></div><div class="t">07:58:52</div><div class="p red">18110</div></div><div class="row"><div class="v">1<g>.4</g></div><div class="t">07:58:45</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.056</g></div><div class="t">07:58:36</div><div class="p red">18110</div></div><div class="row"><div class="v">2<g>.128</g></div><div class="t">07:57:54</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.03</g></div><div class="t">07:57:10</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.763</g></div><div class="t">07:57:04</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.175</g></div><div class="t">07:56:59</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.828</g></div><div class="t">07:56:24</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.099</g></div><div class="t">07:56:11</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.04</g></div><div class="t">07:56:09</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.204</g></div><div class="t">07:56:07</div><div class="p red">18110</div></div><div class="row"><div class="v">0<g>.985</g></div><div class="t">07:56:04</div><div class="p green">18135</div></div></div>
	</div></div>
	<div id="wrapper" class="hide_cursor"><canvas id="canvas_main" width="1180" height="571"></canvas><canvas id="canvas_shapes" class="ab" width="1180" height="571"></canvas><canvas id="canvas_cross" class="ab" width="1180" height="571"></canvas><div id="chart_info">时间2017-07-06 15:30&nbsp;&nbsp;&nbsp;开18111&nbsp;&nbsp;&nbsp;高18260&nbsp;&nbsp;&nbsp;低18070&nbsp;&nbsp;&nbsp;收18260&nbsp;&nbsp;&nbsp;涨幅+0.82 %&nbsp;&nbsp;&nbsp;振幅1.05 %&nbsp;&nbsp;&nbsp;量378.65</div></div>
</div>


<div id="footer_outer"><div id="footer" style="display: block;"><!-- <ul class="horiz donate"><li>
</li><li id="now"></li></ul> --></div></div>

<div id="assist" style="display:none"></div>


<div id="settings">
	<h2>技术指标参数设定</h2>
	<table>
		<tbody><tr id="indicator_price_mas">
			<th>
EMA / MA
				<abbr title="Up to 4 different indicators
Set field blank to remove one of the indicators.">?</abbr>
			</th>
			<td>
				<input name="price_mas">
				<input name="price_mas">
				<input name="price_mas">
				<input name="price_mas">
			</td>
			<td><button>默认值</button></td>
		</tr>
		<tr id="indicator_macd">
			<th>
MACD
				<abbr title="Short, Long, Move">?</abbr>
			</th>
			<td>
				<input name="macd">
				<input name="macd">
				<input name="macd">
			</td>
			<td><button>默认值</button></td>
		</tr>
		<tr id="indicator_kdj">
			<th>
KDJ
				<abbr title="rsv, k, d">?</abbr>
			</th>
			<td>
				<input name="kdj">
				<input name="kdj">
				<input name="kdj">
			</td>
			<td><button>默认值</button></td>
		</tr>
		<tr id="indicator_stoch_rsi">
			<th>
Stoch RSI
				<abbr title="Params: Stochastic Length, RSI Length, K, D">?</abbr>
			</th>
			<td>
				<input name="stoch_rsi">
				<input name="stoch_rsi">
				<input name="stoch_rsi">
				<input name="stoch_rsi">
			</td>
			<td><button>默认值</button></td>
		</tr>
	</tbody></table>

	<div id="close_settings"><a>[ 关闭    ]</a></div>
</div>
<input type="hidden" id="hostHidden" value="https://www.sdb.com"> 
<input type="hidden" id="kline2_loadingString" value="加载中...">
<input type="hidden" id="kline2_1w" value="1周">
<input type="hidden" id="kline2_3d" value="3天">
<input type="hidden" id="kline2_1d" value="1天">
<input type="hidden" id="kline2_12h" value="12小时">
<input type="hidden" id="kline2_6h" value="6小时">
<input type="hidden" id="kline2_4h" value="4小时">
<input type="hidden" id="kline2_2h" value="2小时">
<input type="hidden" id="kline2_1h" value="1小时">
<input type="hidden" id="kline2_30m" value="30分">
<input type="hidden" id="kline2_15m" value="15分">
<input type="hidden" id="kline2_5m" value="5分">
<input type="hidden" id="kline2_3m" value="3分">
<input type="hidden" id="kline2_1m" value="1分">
<input type="hidden" id="kline2_date" value="时间">
<input type="hidden" id="kline2_open" value="开">
<input type="hidden" id="kline2_high" value="高">
<input type="hidden" id="kline2_low" value="低">
<input type="hidden" id="kline2_close" value="收">
<input type="hidden" id="kline2_changeRate" value="涨幅">
<input type="hidden" id="kline2_ampltede" value="振幅">
<input type="hidden" id="kline2_volume" value="量">
<input type="hidden" id="kline2_buy" value="买">
<input type="hidden" id="kline2_sell" value="卖">
<input type="hidden" id="kline2_willReceive" value="将会收到">
<input type="hidden" id="kline2_allAverage" value="花费和收入的平均价为">
<input type="hidden" id="kline2_loadFailure" value="加载失败, 5秒后重新刷新.">
<input type="hidden" id="kline2_cost" value="需要花费">
<input type="hidden" id="kline2_priceTo" value="价格将会达到">
<input type="hidden" id="site_flag" value="1">
<input type="hidden" id="mainType" value="-1">
<input type="hidden" id="coinUrl" value="https://www.sdb.com">
<script type="text/javascript">
	(function() {
	  window.$sid = "0c8aa717";

	  window.$time_fix = 1389883410 * 1000 - Date.now();

  var hostHidden = document.getElementById("hostHidden").value;
	  
	  window.$host = hostHidden;

	  window.$test = false;

	  window.$symbol = "okcoinbtccny";

	  window.$hsymbol = "OKCoin BTC\/CNY";

	  window.$theme_name = "dark";

	  window.$debug = false;

	  window.$settings = {"main_lines":{"id":"main_lines","name":"Main Indicator","default":"mas","options":{"MA":"mas","EMA":"emas","None":"none"}},"stick_style":{"id":"stick_style","name":"Chart Style","options":{"CandleStick":"candle_stick","CandleStickHLC":"candle_stick_hlc","OHLC":"ohlc","Line":"line","Line-o":"line_o","None":"none"}},"line_style":{"id":"ls","name":"Line Style","options":{"Close":"c","Median Price":"m"}},"indicator":{"id":"indicator","name":"Indicator","default":"none","options":{"MACD":"macd","KDJ":"kdj","StochRSI":"stoch_rsi","None":"none"}},"scale":{"id":"scale","name":"Scale","options":{"Normal":"normal","Logarithmic":"logarithmic"}},"theme":{"id":"theme","name":"Theme","options":{"Dark":"dark","Light":"light"},"refresh":true}};

	  window.$p = false;

	  window.$c_usdcny = 6.0463;

	  setTimeout(function() {
	    if (!window.$script_loaded) {
	      return document.getElementById('loading').innerHTML = "<div class=inner>正在加载脚本，请稍后...</div>";
	    }
	  }, 1000);

	}).call(this);
</script>
<script type="text/javascript">
	function showDepth(flag){
		if(flag==0){
			document.getElementById("sidebar_outer").style.display="";
			document.getElementById("sidebar").style.display="";
			document.getElementById("before_trades").style.display="";
		}else{
			document.getElementById("sidebar_outer").style.display="none";
			document.getElementById("sidebar").style.display="none";
			document.getElementById("before_trades").style.display="none";
		}
		
	}
	
</script>
<script type="text/javascript" src="__PUBLIC__/home/kline1.js"></script>
<script type="text/javascript" src="__PUBLIC__/home/gunzip.js"></script>
<script type="text/javascript" src="__PUBLIC__/home/kline3.js"></script>
	
</body></html>
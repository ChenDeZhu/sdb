<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:57:"D:\wamp64\www\sdb/application/index\view\login\index.html";i:1499306952;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
1
	<div id="login_container"></div>
</body>
<script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
<script type="text/javascript">
	var obj = new WxLogin({
          id:"login_container", 
          appid: "wxf3713c3951d84eb4", 
          scope: "snsapi_login", 
          redirect_uri: "<?php echo $url; ?>",
          //用于保持请求和回调的状态，授权请求后原样带回给第三方。该参数可用于防止csrf攻击（跨站请求伪造攻击），建议第三方带上该参数，可设置为简单的随机数加session进行校验
          state: "1",
          style: "black",
          href: ""
        });
</script>
</html>
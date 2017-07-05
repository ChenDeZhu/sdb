<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:57:"D:\wamp64\www\sdb/application/admin\view\index\index.html";i:1495791748;s:59:"D:\wamp64\www\sdb/application/admin\view\public\header.html";i:1498266664;}*/ ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/main.css"/>
    <script type="text/javascript" src="__PUBLIC__/admin/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/admin/js/common.js"></script>
    <link href="__PUBLIC__/ueditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/umeditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/umeditor.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/uploadify/uploadify.css"/>
    <script type="text/javascript" src="__PUBLIC__/uploadify/jquery.uploadify.min.js"></script>
   <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/time/jquery.datetimepicker.css"/ >
    <script src="__PUBLIC__/admin/time/jquery.datetimepicker.js"></script>
</head>
<body>
<div class="topbar-wrap white">
    <div class="topbar-inner clearfix">
        <div class="topbar-logo-wrap clearfix">
            <h1 class="topbar-logo"><a href="<?php echo url('Index/index'); ?>" class="navbar-brand">后台管理</a></h1>
            <ul class="navbar-list clearfix">
                <li><a class="on" href="__ROOT__" target="_blank">网站首页</a></li>
            </ul>
        </div>
        <div class="top-info-wrap">
            <ul class="top-info-list clearfix">
                <li><a href="<?php echo url('Common/cache'); ?>">清理缓存</a></li>
                <li><a href="<?php echo url('Admin/password'); ?>">修改密码</a></li>
                <li><a href="<?php echo url('Common/logout'); ?>">退出</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="container clearfix">
    <div class="sidebar-wrap">
        <div class="sidebar-title">
            <h1>菜单</h1>
        </div>
        <div class="sidebar-content">
            <ul class="sidebar-list">
                <li>
                    <a href="#"><i class="icon-font">&#xe003;</i>常用操作</a>
                    <ul class="sub-menu">
                        <li><a href="<?php echo url('Article/index'); ?>"><i class="icon-font">&#xe005;</i>文章管理</a></li>
                        <li><a href="<?php echo url('Field/index'); ?>"><i class="icon-font">&#xe005;</i>字段管理</a></li>
                        <li><a href="<?php echo url('Category/index'); ?>"><i class="icon-font">&#xe005;</i>栏目管理</a></li>
                        <li><a href="<?php echo url('Tag/index'); ?>"><i class="icon-font">&#xe005;</i>TAG管理</a></li>
                        <li><a href="<?php echo url('Flink/index'); ?>"><i class="icon-font">&#xe005;</i>友情链接</a></li>
                        <li><a href="<?php echo url('Guestbook/index'); ?>"><i class="icon-font">&#xe005;</i>留言管理</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="icon-font">&#xe018;</i>系统管理</a>
                    <ul class="sub-menu">
                        <li><a href="<?php echo url('System/set'); ?>"><i class="icon-font">&#xe017;</i>系统设置</a></li>
                        
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="icon-font">&#xe018;</i>栏目</a>
                    <ul class="sub-menu">
                        <li><a href="<?php echo url('System/set'); ?>"><i class="icon-font">&#xe005;</i>管理员管理</a></li>
                        <li><a href="<?php echo url('Recruitment/index'); ?>"><i class="icon-font">&#xe005;</i>招聘管理</a></li>
                        <li><a href="<?php echo url('Currency/index'); ?>"><i class="icon-font">&#xe005;</i>币种管理</a></li>
                        <li><a href="<?php echo url('User/index'); ?>"><i class="icon-font">&#xe005;</i>用户资金管理</a></li>
                        <li><a href="<?php echo url('Infomation/index'); ?>"><i class="icon-font">&#xe005;</i>资讯管理</a></li>
                        <li><a href="<?php echo url('Question/index'); ?>"><i class="icon-font">&#xe005;</i>客服管理</a></li>
                        <li><a href="<?php echo url('Page/index'); ?>"><i class="icon-font">&#xe005;</i>单页管理</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!--/sidebar-->


    <div class="main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font">&#xe06b;</i><span>你好，<?php echo \think\Session::get('fivecms_admin_username'); ?>。上次登陆时间：<?php echo date("Y-m-d H:i:s",\think\Session::get('fivecms_admin_lasttime')); ?>。上次登陆IP：<?php echo \think\Session::get('fivecms_admin_lastip'); ?></span></div>
        </div>
       
        <div class="result-wrap">
            <div class="result-title">
                <h1>系统基本信息</h1>
            </div>
            <div class="result-content">
                <ul class="sys-info-list">
                 <?php if(is_array($server_info) || $server_info instanceof \think\Collection || $server_info instanceof \think\Paginator): $i = 0; $__LIST__ = $server_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <li>
                        <label class="res-lab"><?php echo $key; ?></label><span class="res-info"><?php echo $vo; ?></span>
                    </li>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
       
    </div>
    <!--/main-->
</div>
</body>
</html>
<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:57:"D:\wamp64\www\sdb/application/admin\view\article\add.html";i:1498272001;s:59:"D:\wamp64\www\sdb/application/admin\view\public\header.html";i:1498266664;}*/ ?>
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
            <div class="crumb-list"><i class="icon-font"></i><a href="<?php echo url('Index/index'); ?>">首页</a><span class="crumb-step">&gt;</span><a class="crumb-name" href="<?php echo url('Article/index'); ?>">文章管理</a><span class="crumb-step">&gt;</span><span>添加文章</span></div>
        </div>
        <div class="result-wrap">
            <div class="result-content">
                <form action="<?php echo url('Article/add'); ?>" method="post" id="myform" name="myform" enctype="multipart/form-data">
                   
                    <table class="insert-tab" width="100%" border="1">
                        <tbody>
                       
                            <tr>
                                <th width="80">栏目：</th>
                                <td>
                                    <select name="catid">
                                    <option value="0">选择栏目</option>
                                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $vo['catid']; ?>" <?php if($vo['catid'] == $catid): ?>selected="selected"<?php endif; ?>><?php echo str_repeat("&nbsp;└─&nbsp;",$vo['level']); ?><?php echo $vo['catname']; ?></option>
                                     <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </td>
                            </tr>
                            <?php if(is_array($forminfos) || $forminfos instanceof \think\Collection || $forminfos instanceof \think\Paginator): $i = 0; $__LIST__ = $forminfos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <tr>
                                <th><?php echo $vo['name']; ?>：</th>
                                <td><?php echo $vo['form']; ?><?php echo $vo['tips']; ?></td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                           
                           <tr>
                                <td colspan="2">
                                    <input class="btn btn-primary  mr10"  value="保存后自动关闭" name="dosubmit"  type="submit">
                                    <input class="btn btn-primary  mr10"  value="保存并继续发表" name="dosubmit_continue" type="submit">
                                    <input class="btn" onclick="history.go(-1)" value="返回" type="button">
                                </td>
                            </tr>
                        </tbody></table>
                   
                </form>
            </div>
        </div>

    </div>
    <!--/main-->
</div>
</body>
</html>

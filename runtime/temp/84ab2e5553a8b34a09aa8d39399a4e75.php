<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:56:"D:\wamp64\www\sdb/application/admin\view\system\set.html";i:1497257113;s:59:"D:\wamp64\www\sdb/application/admin\view\public\header.html";i:1498266664;}*/ ?>
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
            <div class="crumb-list"><i class="icon-font"></i><a href="<?php echo url('Index/index'); ?>">首页</a><span class="crumb-step">&gt;</span><span>系统设置</span></div>
        </div>
        <div class="result-wrap">
            <div class="result-content">
                <form action="<?php echo url('System/set'); ?>" method="post" id="myform" name="myform" enctype="multipart/form-data">
                    <table class="insert-tab" width="100%">
                        <tbody>
                         
                            <tr>
                                <th>网站标题：</th>
                                <td>
                                    <input class="common-text" id="title" name="title" style=" width:800px;" value="<?php echo $detail['title']; ?>" type="text">
                                </td>
                            </tr>
                           <tr>
                                <th>网站关键词：</th>
                                <td>
                                    <input class="common-text" id="keywords" name="keywords" style=" width:800px;" value="<?php echo $detail['keywords']; ?>" type="text">
                                </td>
                            </tr>
                           
                            <tr>
                                <th>网站描述：</th>
                                <td><textarea name="description" class="common-textarea" id="description" style="width:800px; height:200px" ><?php echo $detail['description']; ?></textarea></td>
                            </tr>
                            <tr>
                            <th>网站模板：</th>
                            <td>
                                <select name="template_pc">
                                    <?php if(is_array($pc_template_list) || $pc_template_list instanceof \think\Collection || $pc_template_list instanceof \think\Paginator): $i = 0; $__LIST__ = $pc_template_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $vo['dirname']; ?>" <?php if($vo['dirname'] == $detail['template_pc']): ?>selected="selected"<?php endif; ?>><?php echo $vo['name']; ?> ( <?php echo $vo['dirname']; ?> )</option>
                                     <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </td>
                            </tr>
                            <tr>
                            <th>手机模板：</th>
                            <td>
                                <select name="template_wap">
                                    <?php if(is_array($wap_template_list) || $wap_template_list instanceof \think\Collection || $wap_template_list instanceof \think\Paginator): $i = 0; $__LIST__ = $wap_template_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $vo['dirname']; ?>" <?php if($vo['dirname'] == $detail['template_wap']): ?>selected="selected"<?php endif; ?>><?php echo $vo['name']; ?> ( <?php echo $vo['dirname']; ?> )</option>
                                     <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </td>
                            </tr>
                             <tr>
                                <th>生成缩略图：</th>
                                <td><input type="checkbox" name="isthumb" value="1" <?php if($detail['isthumb'] == 1): ?>checked="checked"<?php endif; ?> />开启&nbsp;宽度：<input type="text" class="common-text" name="width" value="<?php echo $detail['width']; ?>" style="width:100px;" />高度：<input type="text" class="common-text" name="height" value="<?php echo $detail['height']; ?>" style="width:100px; " /></td>
                            </tr>
                             <tr>
                                <th>开启水印：</th>
                                <td><input type="checkbox" name="iswater" value="1" <?php if($detail['iswater'] == 1): ?>checked="checked"<?php endif; ?> />开启 &nbsp;水印位置：<input type="radio" name="pwater" value="0" <?php if($detail['pwater'] == 0): ?>checked="checked"<?php endif; ?> />随机位置<table width="400" border="1">
                                  <tr>
                                    <td><input type="radio" name="pwater" value="1" <?php if($detail['pwater'] == 1): ?>checked="checked"<?php endif; ?>  />左上角</td>
                                    <td><input type="radio" name="pwater" value="2" <?php if($detail['pwater'] == 2): ?>checked="checked"<?php endif; ?> />上居中</td>
                                    <td><input type="radio" name="pwater" value="3" <?php if($detail['pwater'] == 3): ?>checked="checked"<?php endif; ?> />右上角</td>
                                  </tr>
                                  <tr>
                                    <td><input type="radio" name="pwater" value="4" <?php if($detail['pwater'] == 4): ?>checked="checked"<?php endif; ?> />左居中</td>
                                    <td><input type="radio" name="pwater" value="5" <?php if($detail['pwater'] == 5): ?>checked="checked"<?php endif; ?> />居中</td>
                                    <td><input type="radio" name="pwater" value="6"  <?php if($detail['pwater'] == 6): ?>checked="checked"<?php endif; ?> />右居中</td>
                                  </tr>
                                  <tr>
                                    <td><input type="radio" name="pwater" value="7" <?php if($detail['pwater'] == 7): ?>checked="checked"<?php endif; ?> />左下角</td>
                                    <td><input type="radio" name="pwater" value="8" <?php if($detail['pwater'] == 8): ?>checked="checked"<?php endif; ?> />下居中</td>
                                    <td><input type="radio" name="pwater" value="9" <?php if($detail['pwater'] == 9): ?>checked="checked"<?php endif; ?> />右下角</td>
                                  </tr>
                                  <tr><td colspan="3">水印图片：public/admin/water/water.png</td></tr>
                                </table>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <input class="btn btn-primary btn6 mr10"  value="提交" type="submit">
                                    <input class="btn btn6" onclick="history.go(-1)" value="返回" type="button">
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

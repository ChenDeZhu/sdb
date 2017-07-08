<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:57:"D:\wamp64\www\sdb/application/index\view\login\index.html";i:1499492171;s:61:"D:\wamp64\www\sdb/application/index\view\.\public\footer.html";i:1498450778;}*/ ?>
<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">

    <title>登录页面</title>

    <link rel="stylesheet" href="__PUBLIC__/index/css/style.css">
    <link rel="stylesheet" href="__PUBLIC__/index/css/iconfont.css">


</head>

<body>

<div class="login-nav fix">

    <ul class="f-r">

        <li><a href="#">首页</a></li>

        <li><a href="#">协同</a></li>

        <li><a href="#">应用</a></li>

        <li><a href="#">案例</a></li>

        <li><a href="#">开发者</a></li>

        <li><a href="#">企业版</a></li>

    </ul>

</div>

<div class="login-banner"></div>

<div class="login-box">

    <div class="box-con tran">
        <form id="form-signin" action="<?php echo url('Login/signin'); ?>" method="post">
            <div class="login-con f-l" id="signin">

                <div class="form-group">

                    <input name='account' type="text" placeholder="邮箱/手机号码"/>

                    <span class="error-notic">邮箱/手机号码不正确</span>

                </div>

                <div class="form-group">

                    <input name="password" type="password" placeholder="密码">

                    <span class="error-notic">密码不正确</span>

                </div>

                <div class="form-group">

                    <button type="submit" class="tran pr" >

                        <a href="javascript:;" class="tran">登录</a>

                        <img class="loading" src="images/loading.gif" style="display:block">

                    </button>

                </div>

                <div class="from-line"></div>

                <div class="form-group">

                    <a href="javascript:;" class="move-signup a-tag tran blue-border">还没有帐号？免费注册<i class="iconfont tran">&#xe606;</i></a>

                </div>

                <div class="form-group">

                    <a href="javascript:;" class="move-reset a-tag tran">忘记密码？重置 <i class="iconfont tran">&#xe606;</i></a>

                </div>

                <div class="form-group">

                    <a href="javascript:;" class="move-other a-tag tran">使用第三方帐号登录<i class="iconfont tran">&#xe606;</i></a>

                </div>

            </div>
        </form>>
        <!-- 登录 -->



        <div class="signup f-l">

            <div class="form-group">

                <div class="signup-form">

                    <input type="text" placeholder="邮箱" class="email-mobile" onblur="verify.verifyEmail(this)">

                    <a href="javascript:;" class="signup-select">手机注册</a>

                </div>

                <span class="error-notic">邮箱格式不正确</span>

            </div>

            <div class="signup-email">

                <div class="form-group">

                    <input type="text" placeholder="用户名">

                </div>

                <div class="form-group">

                    <input type="password" placeholder="密码（字母、数字，至少6位）" onblur="verify.PasswordLenght(this)">

                    <span class="error-notic">密码长度不够</span>

                </div>
                <div class="form-group">

                    <input type="password" placeholder="验证密码（字母、数字，至少6位）" onblur="verify.PasswordLenght(this)">

                    <span class="error-notic">两次密码输入不同</span>

                </div>

                <div class="form-group">

                    <button type="submit" class="tran pr">

                        <a href="javascript:;" class="tran">注册</a>

                        <img class="loading" src="images/loading.gif">

                    </button>

                </div>

                <p class="view-clause">点击注册，即同意我们的 <a href="#">用户隐私条款</a></p>

            </div><!-- 邮箱注册 -->

            <div class="signup-tel" style="display:none">

                <div class="signup-form" id="message-inf" style="display:none">

                    <input type="text" placeholder="短信验证码" style="width:180px;" onblur="verify.VerifyCount(this)">

                    <a href="javascript:;" class="reacquire">重新获取（59）</a>

                    <span class="error-notic">验证码输入错误</span>

                </div>

                <div class="form-group">

                    <button type="submit" class="tran get-message pr">

                        <a href="javascript:;" class="tran">获取短信验证码</a>

                        <img class="loading" src="images/loading.gif">

                    </button>

                </div>

            </div><!-- 手机号码注册 -->

            <div class="from-line"></div>

            <div class="form-group">

                <a href="javascript:;" class="move-login a-tag tran blue-border">已有帐号？登录<i class="iconfont tran">&#xe606;</i></a>

            </div>

            <div class="form-group">

                <a href="javascript:;" class="move-other a-tag tran">使用第三方帐号登录<i class="iconfont tran">&#xe606;</i></a>

            </div>

        </div>

        <!-- 注册 -->



        <div class="other-way f-l">

            <div class="form-group">

                <button type="submit" class="tran pr">

                    <a href="javascript:;" class="tran">QQ帐号登录</a>

                    <img class="loading" src="images/loading.gif">

                </button>

            </div>

            <div class="form-group">

                <button type="submit" class="tran pr">

                    <a href="javascript:;" class="tran">新浪微博帐号登录</a>

                    <img class="loading" src="images/loading.gif">

                </button>

            </div>

            <div class="form-group">

                <button type="submit" class="tran pr">

                    <a href="javascript:;" class="tran">微信帐号登录</a>

                    <img class="loading" src="images/loading.gif">

                </button>

            </div>

            <div class="form-group">

                <button type="submit" class="tran pr">

                    <a href="javascript:;" class="tran">网易帐号登录</a>

                    <img class="loading" src="images/loading.gif">

                </button>

            </div>

            <div class="from-line"></div>

            <div class="form-group">

                <a href="javascript:;" class="move-signup a-tag tran blue-border">还没有帐号？免费注册<i class="iconfont tran">&#xe606;</i></a>

            </div>

            <div class="form-group">

                <a href="javascript:;" class="move-login a-tag tran">已有帐号？登录<i class="iconfont tran">&#xe606;</i></a>

            </div>

        </div>

        <!-- 第三方登录 -->



        <div class="mimachongzhi f-l">

            <div class="form-group">

                <input type="text" placeholder="请输入您的邮箱地址">

                <span class="error-notic">邮箱格式不正确</span>

            </div>

            <div class="form-group">

                <button type="submit" class="tran pr">

                    <a href="javascript:;" class="tran">发送重置密码邮件</a>

                    <img class="loading" src="images/loading.gif">

                </button>

            </div>

            <div class="from-line"></div>

            <div class="form-group">

                <a href="javascript:;" class="move-signup	a-tag tran blue-border">还没有帐号？免费注册<i class="iconfont tran">&#xe606;</i></a>

            </div>

            <div class="form-group">

                <a href="javascript:;" class="move-login a-tag tran">已有帐号？登录<i class="iconfont tran">&#xe606;</i></a>

            </div>

        </div>

        <!-- 密码重置 -->



        <div class="mobile-success f-l">

            <p>手机号 <span>186****7580</span> 验证成功</p>

            <p>请完善您的账号信息，您也可以<a href="#">绑定现有账号</a></p>

            <div class="form-group">

                <input type="text" placeholder="邮箱" class="email-mobile" onblur="verify.verifyEmail(this)"/>

                <span class="error-notic">邮箱格式不正确</span>

            </div>

            <div class="form-group">

                <input type="text" placeholder="您的名字">

            </div>

            <div class="form-group">

                <input type="password" placeholder="密码（字母、数字，至少6位）" onblur="verify.PasswordLenght(this)"/>

                <span class="error-notic">密码长度不够</span>

            </div>

            <div class="form-group">

                <button type="submit" class="tran pr">

                    <a href="javascript:;" class="tran">注册</a>

                    <img class="loading" src="images/loading.gif">

                </button>

            </div>

            <p class="view-clause">点击注册，即同意我们的 <a href="#">用户隐私条款</a></p>

        </div>

        <!-- 手机注册成功添补信息 -->

    </div>

</div>




</body>
</html>
<script>

    var _handle='';//储存电话是否填写正确

    $(function(){
        //登录

        $('#signin div:eq(2)').click(function(){
            var account=$('#signin div:eq(0) input').val();
            var password=$('#signin div:eq(1) input').val();
            console.log(account+'  '+password);
            if(account.length==0||password.length==0||account==null||password==null){
                var notext="<p style='color:red'>账号或密码不能为空</p>"
                    $('#signin div:eq(1)').after(notext);
            }else{
               $('#form-signin').submit();
            }

        });

        $(".signup-form input").on("focus",function(){

            $(this).parent().addClass("border");

        });

        $(".signup-form input").on("blur",function(){

            $(this).parent().removeClass("border");

        })

        //注册方式切换

        $(".signup-select").on("click",function(){

            var _text=$(this).text();

            var $_input=$(this).prev();

            $_input.val('');

            if(_text=="手机注册"){

                $(".signup-tel").fadeIn(200);

                $(".signup-email").fadeOut(180);

                $(this).text("邮箱注册");

                $_input.attr("placeholder","手机号码");

                $_input.attr("onblur","verify.verifyMobile(this)");

                $(this).parents(".form-group").find(".error-notic").text("手机号码格式不正确")



            }

            if(_text=="邮箱注册"){

                $(".signup-tel").fadeOut(180);

                $(".signup-email").fadeIn(200);

                $(this).text("手机注册");

                $_input.attr("placeholder","邮箱");

                $_input.attr("onblur","verify.verifyEmail(this)");

                $(this).parents(".form-group").find(".error-notic").text("邮箱格式不正确")

            }

        });

        //步骤切换

        var _boxCon=$(".box-con");

        $(".move-login").on("click",function(){

            $(_boxCon).css({

                'marginLeft':0

            })

        });

        $(".move-signup").on("click",function(){

            $(_boxCon).css({

                'marginLeft':-320

            })

        });

        $(".move-other").on("click",function(){

            $(_boxCon).css({

                'marginLeft':-640

            })

        });

        $(".move-reset").on("click",function(){

            $(_boxCon).css({

                'marginLeft':-960

            })

        });

        $("body").on("click",".move-addinf",function(){

            $(_boxCon).css({

                'marginLeft':-1280

            })

        });



        //获取短信验证码

        var messageVerify=function (){

            $(".get-message").eq(1).on("click",function(){

                if(_handle){

                    $("#message-inf").eq(1).fadeIn(100)
                    $.ajax({

                        type: 'POST',

                        url: "<?php echo url('Login/verify'); ?>" ,

                        data:{'phone': $('.email-mobile').val()},
                        success:function(data){
                            alert(data);
                        }
                    });
                    $(this).html('<a href="javascript:;">下一步</a><img class="loading" src="images/loading.gif">').addClass("move-addinf");

                }

            });

        }();

        //获取邮箱验证码

        var emailVerify=function (){

            $(".get-message").eq(0).on("click",function(){

                if(_handle){

                    $("#message-inf").eq(0).fadeIn(100)
                    $.ajax({

                        type: 'POST',

                        url: "<?php echo url('Login/verify'); ?>" ,

                        data:{'phone': $('.email-mobile').val()},
                        success:function(data){
                            alert(data);
                        }
                    });
                    $(this).html('<a href="javascript:;">下一步</a><img class="loading" src="images/loading.gif">').addClass("move-addinf");

                }

            });

        }();

    });



    //表单验证

    function showNotic(_this){

        $(_this).parents(".form-group").find(".error-notic").fadeIn(100);

       // $(_this).focus();

    }//错误提示显示

    function hideNotic(_this){

        $(_this).parents(".form-group").find(".error-notic").fadeOut(100);

    }//错误提示隐藏

    var verify={

        verifyEmail:function(_this){

            var validateReg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            var _value=$(_this).val();

            if(!validateReg.test(_value)){

                showNotic(_this)

            }else{

                hideNotic(_this)

            }

        },//验证邮箱

        verifyMobile:function(_this){

            var validateReg = /^((\+?86)|(\(\+86\)))?1\d{10}$/;

            var _value=$(_this).val();

            if(!validateReg.test(_value)){

                showNotic(_this);

                _handle=false;

            }else{

                hideNotic(_this);

                _handle=true;

            }

            return _handle

        },//验证手机号码

        PasswordLenght:function(_this){

            var _length=$(_this).val().length;

            if(_length<6){

                showNotic(_this)

            }else{

                hideNotic(_this)

            }

        },//验证设置密码长度
        VerifyPassword:function(_this){
            //if($(_this).val()!=$(_this).)
        },//验证两次密码是否相同
        VerifyCount:function(_this){

            var _count="123456";

            var _value=$(_this).val();

            console.log(_value)

            if(_value!=_count){

                showNotic(_this)

            }else{

                hideNotic(_this)

            }

        }//验证验证码

    }

</script>



</body>

</html>
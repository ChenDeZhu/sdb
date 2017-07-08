<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use app\index\model\ServerAPI;
use \DefaultProfile;
/*资金管理*/
class Login extends Common{
    public function index(){

        return $this->fetch();
    }

    public function lin(){
        $where=array('price'=>0.01);
        dump(db('test')->where('price',0.01)->find());
    }

    //注册模块
    public function login(){

    }
    //不同模式的登录判断
    private function signinjudge($type,$account){
        if ($data = db('user')->where($type, $account)->find()) {

            if ($data['id_status'] == -1) {
                $this->error('账号状态异常，请与客服人员联系');
            } else {
                if ($data['login_pwd'] == md5($_POST['password'])) {
                    $this->success('登录成功', index / index);
                    session('uid',$data['id']);
                    session('nickname',$data['nickname']);
                } else {
                    $this->error('密码错误');
                }
            }
        } else {
            $this->error('账号不存在或账号名输入错误');
        }
    }
    public function signin()
    {
        //dump($_POST); exit;
        $account = $_POST['account'];

        if (strpos($account, '@')) {
            //邮箱
            $this->signinjudge('email',$account);
        } else {
            //手机号
            $this->signinjudge('mobile',$account);
        }
    }

    //验证码
    public function verify(){
      
        require_once VENDOR_PATH.'alimsg/core/Config.php';
        require_once VENDOR_PATH.'alimsg/Dysmsapi/Request/V20170525/SendSmsRequest.php';
        require_once VENDOR_PATH.'alimsg/Dysmsapi/Request/V20170525/QuerySendDetailsRequest.php';
        //此处需要替换成自己的AK信息
        $accessKeyId = "LTAIwEAzcciiZu8t";//参考本文档步骤2
        $accessKeySecret = "QDAax6tEiOGZKJQZEmqzUSU1fiSvIR";//参考本文档步骤2
        //短信API产品名
        $product = "Dysmsapi";
        //短信API产品域名
        $domain = "dysmsapi.aliyuncs.com";
        //暂时不支持多Region
        $region = "cn-hangzhou";
        //初始化访问的acsCleint
       
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        $acsClient= new \DefaultAcsClient($profile);
        $request = new \Dysmsapi\Request\V20170525\SendSmsRequest;
        //必填-短信接收号码。支持以逗号分隔的形式进行批量调用，批量上限为20个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
        $request->setPhoneNumbers("15068636372");
        //必填-短信签名
        $request->setSignName("驼驼网络");
        //必填-短信模板Code
        $request->setTemplateCode("SMS_75930162");
        //选填-假如模板中存在变量需要替换则为必填(JSON格式)
        // $request->setTemplateParam("{\"code\":\"12345\",\"product\":\"云通信服务\"}");
        //选填-发送短信流水号
        $request->setOutId("1234");
        //发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);
        var_dump($acsResponse);
    }
    public function ve(){
        require_once VENDOR_PATH.'alimsg/Alisms.class.php';
        $accessKeyId = "LTAIwEAzcciiZu8t";//参考本文档步骤2
        $accessKeySecret = "QDAax6tEiOGZKJQZEmqzUSU1fiSvIR";//参考本文档步骤2
        $alisms = new Alisms($accessKeyId,$accessKeySecret);
        $mobile = '18805813155'; //目标手机号，多个手机号可以逗号分隔
        $code = 'SMS_36225243'; //短信模板的模板CODE
        $paramString = '{"code":"344556"}'; //短信模板中的变量；,参数格式{"no": "123456"}
        $re = $alisms->smsend($mobile,$code,$paramString);
        print_r($re);
    }
    public function verify2(){
        // dump($_POST);
        $msg=db('company')->find();
        dump($msg);
//网易云信分配的账号，请替换你在管理后台应用下申请的Appkey
        $AppKey = $msg['m_AppKey'];
//网易云信分配的账号，请替换你在管理后台应用下申请的appSecret
        $AppSecret = $msg['m_AppSecret'];

        $p = new ServerAPI($AppKey,$AppSecret);     //fsockopen伪造请求

//发送模板短信
        dump( $p->sendSMSTemplate($msg['m_templateid'],array('15068636372'),['0571']));
    }

    public function sendmail(){
        
        sendmails(0,'420021436@qq.com','aaa');
    }




}
<?php
include APP_PATH.'function.php';

function trim_script($str) {
	if(is_array($str)){
		foreach ($str as $key => $val){
			$str[$key] = trim_script($val);
		}
 	}else{
 		$str = preg_replace ( '/\<([\/]?)script([^\>]*?)\>/si', '&lt;\\1script\\2&gt;', $str );
		$str = preg_replace ( '/\<([\/]?)iframe([^\>]*?)\>/si', '&lt;\\1iframe\\2&gt;', $str );
		$str = preg_replace ( '/\<([\/]?)frame([^\>]*?)\>/si', '&lt;\\1frame\\2&gt;', $str );
		$str = str_replace ( 'javascript:', 'javascript：', $str );
 	}
	return $str;
}

function remove_xss($string) { 
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

    $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

    $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

    $parm = array_merge($parm1, $parm2); 

	for ($i = 0; $i < sizeof($parm); $i++) { 
		$pattern = '/'; 
		for ($j = 0; $j < strlen($parm[$i]); $j++) { 
			if ($j > 0) { 
				$pattern .= '('; 
				$pattern .= '(&#[x|X]0([9][a][b]);?)?'; 
				$pattern .= '|(&#0([9][10][13]);?)?'; 
				$pattern .= ')?'; 
			}
			$pattern .= $parm[$i][$j]; 
		}
		$pattern .= '/i';
		$string = preg_replace($pattern, ' ', $string); 
	}
	return $string;
}
/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string)
{
    if (!is_array($string)) {
        return addslashes($string);
    }
    foreach ($string as $key => $val) {
        $string[$key] = new_addslashes($val);
    }
    return $string;
}
/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string)
{
    if (!is_array($string)) {
        return stripslashes($string);
    }
    foreach ($string as $key => $val) {
        $string[$key] = new_stripslashes($val);
    }
    return $string;
}
/**
 * 返回经htmlspecialchars处理过的字符串或数组
 * @param $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_html_special_chars($string)
{
    $encoding = 'utf-8';
    if (!is_array($string)) {
        return htmlspecialchars($string, ENT_QUOTES, $encoding);
    }
    foreach ($string as $key => $val) {
        $string[$key] = new_html_special_chars($val);
    }
    return $string;
}
function new_html_entity_decode($string)
{
    $encoding = 'utf-8';
    if (!is_array($string)) {
        return html_entity_decode($string, ENT_QUOTES, $encoding);
    }
    foreach ($string as $key => $val) {
        $string[$key] = new_html_entity_decode($val);
    }
    return $string;
}
function new_htmlentities($string)
{
    $encoding = 'utf-8';
    return htmlentities($string, ENT_QUOTES, $encoding);
}
function safe_replace($string)
{
    $string = str_replace('%20', '', $string);
    $string = str_replace('%27', '', $string);
    $string = str_replace('%2527', '', $string);
    $string = str_replace('*', '', $string);
    $string = str_replace('"', '&quot;', $string);
    $string = str_replace("'", '', $string);
    $string = str_replace('"', '', $string);
    $string = str_replace(';', '', $string);
    $string = str_replace('<', '&lt;', $string);
    $string = str_replace('>', '&gt;', $string);
    $string = str_replace("{", '', $string);
    $string = str_replace('}', '', $string);
    $string = str_replace('\\', '', $string);
    return $string;
}
function delDirAndFile($path, $delDir = FALSE)
{
    $message = "";
    $handle = opendir($path);
    if ($handle) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("{$path}/{$item}")) {
                    $msg = delDirAndFile("{$path}/{$item}", $delDir);
                    if ($msg) {
                        $message .= $msg;
                    }
                } else {
                    $message .= "删除文件" . $item;
                    if (unlink("{$path}/{$item}")) {
                        $message .= "成功<br />";
                    } else {
                        $message .= "失败<br />";
                    }
                }
            }
        }
        closedir($handle);
        if ($delDir) {
            if (rmdir($path)) {
                $message .= "删除目录" . dirname($path) . "<br />";
            } else {
                $message .= "删除目录" . dirname($path) . "失败<br />";
            }
        }
    } else {
        if (file_exists($path)) {
            if (unlink($path)) {
                $message .= "删除文件" . basename($path) . "<br />";
            } else {
                $message .= "删除文件" + basename($path) . "失败<br />";
            }
        } else {
            $message .= "文件" + basename($path) . "不存在<br />";
        }
    }
    return $message;
}
function str_cut($string, $length, $dot = '...')
{
    $strlen = strlen($string);
    if ($strlen <= $length) {
        return $string;
    }
    $string = str_replace(array(' ', '&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵', ' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    $length = intval($length - strlen($dot) - $length / 3);
    $n = $tn = $noc = 0;
    while ($n < strlen($string)) {
        $t = ord($string[$n]);
        if ($t == 9 || $t == 10 || 32 <= $t && $t <= 126) {
            $tn = 1;
            $n++;
            $noc++;
        } elseif (194 <= $t && $t <= 223) {
            $tn = 2;
            $n += 2;
            $noc += 2;
        } elseif (224 <= $t && $t <= 239) {
            $tn = 3;
            $n += 3;
            $noc += 2;
        } elseif (240 <= $t && $t <= 247) {
            $tn = 4;
            $n += 4;
            $noc += 2;
        } elseif (248 <= $t && $t <= 251) {
            $tn = 5;
            $n += 5;
            $noc += 2;
        } elseif ($t == 252 || $t == 253) {
            $tn = 6;
            $n += 6;
            $noc += 2;
        } else {
            $n++;
        }
        if ($noc >= $length) {
            break;
        }
    }
    if ($noc > $length) {
        $n -= $tn;
    }
    $strcut = substr($string, 0, $n);
    $strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
    return $strcut . $dot;
}
function dir_path($path)
{
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/') {
        $path = $path . '/';
    }
    return $path;
}
function dir_create($path, $mode = 0777)
{
    if (is_dir($path)) {
        return TRUE;
    }
    $ftp_enable = 0;
    $path = dir_path($path);
    $temp = explode('/', $path);
    $cur_dir = '';
    $max = count($temp) - 1;
    for ($i = 0; $i < $max; $i++) {
        $cur_dir .= $temp[$i] . '/';
        if (@is_dir($cur_dir)) {
            continue;
        }
        @mkdir($cur_dir, 0777, true);
        @chmod($cur_dir, 0777);
    }
    return is_dir($path);
}
//获取某分类的直接子分类
function getSons($categorys, $catid = 0)
{
    $sons = array();
    foreach ($categorys as $item) {
        if ($item['pid'] == $catid) {
            $sons[] = $item;
        }
    }
    return $sons;
}
//获取某个分类的所有子分类
function getSubs($categorys, $catid = 0, $level = 1)
{
    $subs = array();
    foreach ($categorys as $item) {
        if ($item['pid'] == $catid) {
            $item['level'] = $level;
            $subs[] = $item;
            $subs = array_merge($subs, getSubs($categorys, $item['catid'], $level + 1));
        }
    }
    return $subs;
}
//获取某个分类的所有父分类
//方法一，递归
function getParents($categorys, $catid)
{
    $tree = array();
    foreach ($categorys as $item) {
        if ($item['catid'] == $catid) {
            if ($item['pid'] > 0) {
                $tree = array_merge($tree, getParents($categorys, $item['pid']));
            }
            $tree[] = $item;
            break;
        }
    }
    return $tree;
}


function five_random($length, $chars = '0123456789')
{
    $hash = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}
function five_random_str($lenth = 6)
{
    return five_random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}
function five_password($password, $encrypt = '')
{
    $pwd = array();
    $pwd['encrypt'] = $encrypt ? $encrypt : five_random_str();
    $pwd['password'] = md5(md5(trim($password)) . $pwd['encrypt']);
    return $encrypt ? $pwd['password'] : $pwd;
}
function pathConvert($path)
{
    $path = str_replace('./', '/', $path);
    $path = str_replace('\\', '/', $path);
    return str_replace('//', '/', $path);
}

function kw_to_array($str)
{
    $result = array();
    $array = array();
    $str = str_replace('，', ',', $str);
    $str = str_replace("n", ',', $str);
    $str = str_replace("rn", ',', $str);
    $str = str_replace(' ', ',', $str);
    $array = explode(',', $str);
    foreach ($array as $key => $value) {
        if ('' != ($value = trim($value))) {
            $result[] = $value;
        }
    }
    return $result;
}
function five_random_color()
{
    mt_srand((double) microtime() * 1000000);
    $c = '';
    while (strlen($c) < 6) {
        $c .= sprintf("%02X", mt_rand(0, 255));
    }
    return $c;
}

function string2array($data)
{
    $data = trim($data);
    if ($data == '') {
        return array();
    }
    if (strpos($data, 'array') === 0) {
        @eval("\$array = {$data};");
    } else {
        if (strpos($data, '{\\') === 0) {
            $data = stripslashes($data);
        }
        $array = json_decode($data, true);
    }
    return $array;
}
function array2string($data, $isformdata = 1)
{
    if ($data == '' || empty($data)) {
        return '';
    }
    if ($isformdata) {
        $data = new_stripslashes($data);
    }
    if (version_compare(PHP_VERSION, '5.3.0', '<')) {
        return addslashes(json_encode($data));
    } else {
        return addslashes(json_encode($data, JSON_FORCE_OBJECT));
    }
}

function auto_save_image($body)
    {
        $body = new_stripslashes($body);
        if (!preg_match_all('/<img.*?src="(.*?)".*?>/is', $body, $img_array)) {
            return $body;
        }
        $img_array = array_unique($img_array[1]);
        set_time_limit(0);
        $imgPath = 'uploads/' . date("Ymd");
        $milliSecond = date("YmdHis");
        dir_create($imgPath);
        foreach ($img_array as $key => $value) {
            if (preg_match("#" . "http://" . $_SERVER["HTTP_HOST"] . "#i", $value)) {
                continue;
            }
            if (!preg_match("#^http:\\/\\/#i", $value)) {
                continue;
            }
            $value = trim($value);
            $imgAttr = get_headers($value, true);
            switch ($imgAttr['Content-Type']) {
                case 'image/png':
                    $ext = 'png';
                    break;
                case 'image/jpeg':
                    $ext = 'jpg';
                    break;
                case 'image/gif':
                    $ext = 'gif';
                    break;
                default:
                    $ext = 'jpg';
            }
            $get_file = @file_get_contents($value);
            $filename = mt_rand(100000, 999999) . $milliSecond . $key . '.' . $ext;
            $rndFileName = $imgPath . '/' . $filename;
            if ($get_file) {
                $fp = @fopen($rndFileName, "w");
                @fwrite($fp, $get_file);
                @fclose($fp);
                $webconfig = db('system')->find(1);
                if ($webconfig['isthumb']) {
                    $image = \org\Image::open('./' . $rndFileName);
                    $image->thumb($webconfig['width'], $webconfig['height'])->save('./' . $rndFileName);
                }
                if ($webconfig['iswater']) {
                    $image = \org\Image::open('./' . $rndFileName);
                    if ($webconfig['pwater'] == 0) {
                        $webconfig['pwater'] = rand(1, 9);
                    }
                    $image->water('./public/admin/water/water.png', $webconfig['pwater'])->save('./' . $rndFileName);
                }
            }
            $body = str_replace($value, __ROOT__ . '/' . $rndFileName, $body);
        }
        return $body;
    }
function form_image($field,$value=''){
    $str='<input type="file" id="'.$field.'">';
    if($value){
        $str .='<img src="'.$value.'" style="max-width:300px; max-height:100px" />';
        }
    $str .="<script type=\"text/javascript\">
            $(\"#".$field."\").uploadify({
                queueSizeLimit : 1,
                height          : 30,
                swf             : '__PUBLIC__/uploadify/uploadify.swf',
                fileObjName     : 'file',
                buttonText      : '上传图片',
                uploader        : '__ROOT__/api.php/upload/upimg.html',
                width           : 120,
                removeTimeout     : 1,
                fileTypeExts      : '*.jpg; *.png; *.gif;',
                fileSizeLimit   :2048,
                onUploadSuccess : uploadPicture,
                onFallback : function() {
                    alert('未检测到兼容版本的Flash.');
                }
            });
            function uploadPicture(file, data){
                var data = \$.parseJSON(data);
                if(data.status){            
                            var html = '<span>'+ '<img style=\"max-width:300px; max-height:100px;\" src=\"'+data.url+'\">' ;
                            html += '<a href=\"javascript:void(0)\" onclick=\"delete_attachment(this);\">&nbsp;&nbsp;删除</a>';
                            html += '<input type=\"hidden\" name=".$field." value=\"'+data.url+'\" /></span>';
                            \$('#".$field."').after(html);
                } else {
                    alert('上传出错，请稍后再试');
                    return false;
                }
            }
            </script>";
    
    
    return $str;
    }
function form_images($field,$value=''){
    
    $str='<input type="file" id="'.$field.'">';
    if($value){
        $value=string2array($value);
        foreach($value as $v){
        $str .='<span><img style="max-width:300px; max-height:100px;" src='.$v.' />' ;
        $str .='<a href="javascript:void(0)" onclick="delete_attachment(this);">&nbsp;删除&nbsp;</a>';
        $str .='<input type="hidden" name='.$field.'[] value='.$v.' /></span>';
        }
        }
    $str .="<script type=\"text/javascript\">
            $(\"#".$field."\").uploadify({
                queueSizeLimit : 20,
                height          : 30,
                swf             : '__PUBLIC__/uploadify/uploadify.swf',
                fileObjName     : 'file',
                buttonText      : '上传图片',
                uploader        : '__ROOT__/api.php/upload/upimg.html',
                width           : 120,
                removeTimeout     : 1,
                fileTypeExts      : '*.jpg; *.png; *.gif;',
                fileSizeLimit   :2048,
                onUploadSuccess : uploadPicture,
                onFallback : function() {
                    alert('未检测到兼容版本的Flash.');
                }
            });
            function uploadPicture(file, data){
                var data = \$.parseJSON(data);
                if(data.status){            
                            var html = '<span>'+ '<img style=\"max-width:300px; max-height:100px;\" src=\"'+data.url+'\" />' ;
                            html += '<a href=\"javascript:void(0)\" onclick=\"delete_attachment(this);\">&nbsp;删除&nbsp;</a>';
                            html += '<input type=\"hidden\" name=".$field."[] value=\"'+data.url+'\" /></span>';
                            \$('#".$field."').after(html);
                } else {
                    alert('上传出错，请稍后再试');
                    return false;
                }
            }
            </script>";
    
    
    return $str;
    }
function form_editor($field, $value='')
    {
       
        $str = '<script type="text/plain" id="' . $field . '"  style="width:805px;height:200px;">' . new_html_entity_decode($value) .'</script>';
        $str .= "<script type=\"text/javascript\">\r\n";
        $str .= "var um = UM.getEditor(\"{$field}\",{";
        $str .= 'UEDITOR_HOME_URL: "__PUBLIC__/ueditor/",';
        $str .= 'imageUrl: "__ROOT__/api.php/upload/ueditor",';
        $str .= 'imagePath: "__ROOT__/",';
        $str .= "textarea: '{$field}' });";
        $str .= '</script>';
        return $str;
    }

/**
 * [getPart 截取部分问题描述]
 * @param  [string] $value [问题描述]
 * @return [string]        [截取完的问题描述]
 */
function getPart($value){
    $value = mb_substr($value,0,10,'utf-8');
    return $value;
}
/**
 * [getType 获取交易类型]
 * @param  [int] $value []
 * @return [string]        [对应的内容]
 */
function getDType($value){
    $status = ['1'=>'充值','2'=>'提现','3'=>'交易支出','4'=>'交易收入'];
    return $status[$value];
}


//发送验证邮件
/**
 * [sendmails 发送邮箱]
 * @param  [type] $type            [提示类型]
 * @param  [type] $receiveraddress [收件邮箱]
 * @param  [type] $receivername    [收件人姓名]
 * @return [type]                  [验证码]
 */
function sendmails($type,$receiveraddress,$receivername){//返回验证码
    //vendor()

    require_once ROOT_PATH.'public/email/verify.php';
    $data=db('company')->find();
    $e=[
        'port'=>$data['e_port'],
        'host'=>$data['e_host'],
        'send'=>$data['e_send'],
        'auth_code'=>$data['e_auth_code'],
        'sendpwd'=>$data['e_sendpwd'],
        'sendname'=>$data['e_sendname'],
        'subject'=>$data['e_subject'],
        'code'=>rand(100000,999999),
        'receiveraddress'=>'15068636372@163.com',
        'receivername'=>'林俊旭'
    ];
    switch($type){
        case 0://注册
            $e['content']='您注册账号的验证码如下';break;
        default://其他
            $e['content']='您的验证码如下';
    }
    $msg=emailbody($receiveraddress,$receivername,$e['content'],$e['code']);
    require_once VENDOR_PATH.'/phpmailer/class.phpmailer.php';
    require_once VENDOR_PATH.'/phpmailer/class.smtp.php';
    //vendor('class.phpmailer.php');
    //vendor("class.smtp.php");
    $mail  = new \app\index\controller\PHPMailer();

    $mail->CharSet    ="UTF-8";                 //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
    $mail->IsSMTP();                            // 设定使用SMTP服务
    $mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
    $mail->SMTPSecure = "ssl";                  // SMTP 安全协议
    $mail->Host       = $e['host'];       // SMTP 服务器
    $mail->Port       = $e['port'];                    // SMTP服务器的端口号
    $mail->Username   = $e['send'];  // SMTP服务器用户名
    $mail->Password   = $e['auth_code'];        // SMTP服务器密码
    $mail->SetFrom($e['send'], $e['sendname']);    // 设置发件人地址和名称
    $mail->AddReplyTo($e['send'], $e['sendname']);
    // 设置邮件回复人地址和名称
    $mail->Subject    = $e['subject'];                     // 设置邮件标题
    $mail->AltBody    = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
    // 可选项，向下兼容考虑
    $mail->MsgHTML($msg);                         // 设置邮件内容
    $mail->AddAddress($receiveraddress);
//$mail->AddAttachment("images/phpmailer.gif"); // 附件
    //dump($mail);
    if(!$mail->Send()) {
        echo "发送失败：".$mail->ErrorInfo;
    } else {
        echo "恭喜，邮件发送成功！";
    }
    return $e['code'];
}
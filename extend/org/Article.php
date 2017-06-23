<?php
namespace org;
class Article
{
    public $fields;
    public function __construct()
    {
        $this->fields = $this->get_fields();
    }
    public function get_fields()
    {
        $field_array = array();
        $fields = db('field')->where(array('disabled' => 0))->order("listorder asc, id asc")->select();
        foreach ($fields as $_value) {
            $field_array[$_value['field']] = $_value;
        }
        return $field_array;
    }
    public function get($data = array())
    {   
        $info = array();
        foreach ($this->fields as $field => $v) {
            $func = $v['formtype'];
            $value = isset($data[$field]) ? new_html_special_chars($data[$field], ENT_QUOTES) : '';
            if (!method_exists($this, $func)) {
                continue;
            }
            $form = $this->$func($field, $value, $v);
            if ($form !== false) {
                $info[$field] = array('name' => $v['name'], 'tips' => $v['tips'], 'form' => $form, 'formtype' => $v['formtype']);
            }
        }
        return $info;
    }
    public function text($field, $value, $fieldinfo)
    {
        extract($fieldinfo);
        if (!$value) {
            $value = $defaultvalue;
        }
        if ($field == 'title') {
            $size = 100;
			$kw="onBlur=\"$.post('".url('Content/get_keywords')."?number=3&sid='+Math.random()*5, {data:$('#title').val()}, function(data){if(data && $('#keywords').val()=='') $('#keywords').val(data); })\"";
		
        } else {
            $size = 50;
			$kw='';
        }
		
		return '<input name="' . $field . '" id="' . $field . '"   class="common-text" '.$kw.' size="' . $size . '"  value="' . $value . '"  type="text" >'.'';
    }
    public function textarea($field, $value, $fieldinfo)
    {
        extract($fieldinfo);
        if (!$value) {
            $value = $defaultvalue;
        }
        return '<textarea name="' . $field . '" class="common-textarea" id="' . $field . '"  style="width:98%; height:100px" >' . addslashes($value) . '</textarea>';
    }
    public function editor($field, $value, $fieldinfo)
    {
        extract($fieldinfo);
        if (!$value) {
            $value = $defaultvalue;
        }
        $str = '<script type="text/plain" id="' . $field . '"  style=" width:98%;height:200px;">' . new_html_entity_decode($value) .'</script>';
        $str .= "<script type=\"text/javascript\">\r\n";
        $str .= "var um = UM.getEditor(\"{$field}\",{";
        $str .= 'UEDITOR_HOME_URL: "__PUBLIC__/ueditor/",';
        $str .= "imageUrl: \"{:url('Upload/ueditor')}\",";
        $str .= 'imagePath: "__ROOT__/",';
        $str .= "textarea: '{$field}' });";
        $str .= '</script>';
        return $str;
    }
   
	public function image($field, $value , $fieldinfo)
    {   
	    
	    extract($fieldinfo);
	    $imgtype='gif,png,jpg,zip,rar,tar';
        $_type = explode(',',$imgtype );
        $txt = '上传';
		$type='';
        foreach ($_type as $t) {
            $type .= '*.' . $t . ';';
        }
		
        $size = 2 * 1024;
        $js = "<script type=\"text/javascript\">
        $(function() {
        var Button=$(\"#button_" . $field . "\");
	    Button.uploadify({
		height : '22',
		width : '68',
		swf           : '".__ROOT__."/public/uploadify/uploadify.swf?ver='+ Math.random(),
		uploader      : '".url('Upload/upfile')."',
 		method   : 'post',
		formData : { 'submit' : '1',
		                    'session_id' : '" . session_id() . "'
						},
        fileTypeExts: '" . $type . "',  
        fileObjName:'file',
		buttonText: '" . $txt . "',
        queueSizeLimit: 1,
		fileSizeLimit   : '" . $size . "', 
		onUploadSuccess :function(file,data,response){
		$(\"#" . $field . "\").val(data);
		},
		'onUploadProgress':function(file,bytesUploaded,bytesTotal,totalBytesUploaded,totalBytesTotal){
			var num = Math.round(bytesUploaded / bytesTotal * 10000) / 100.00 + \"%\";
			Button.uploadify('settings','buttonText','上传：' + num);
		},
		'onQueueComplete' : function(queueData) {
			Button.uploadify('disable', false);
			Button.uploadify('settings','buttonText','上传');
			Button.uploadify('cancel','*');
		}
	   });
	   });</script>";
      
	   $preview = 'onmouseover="showImg(this)"  onmouseout="hideImg(this)"';		   
       return $js . '
	   <input type="text" class="common-text"  size="50" value="' . $value . '" name="' . $field . '" id="' . $field . '" ' . $preview . '>
	   <input id="button_' . $field . '" type="file"  multiple="true">';
    }
 
	public  function images($field, $value, $fieldinfo)
	{  
        extract($fieldinfo);
		$preview = 'onmouseover="showImg(this)"  onmouseout="hideImg(this)"';
	    $imgtype='gif,png,jpg,zip,rar,tar';
        $_type = explode(',',$imgtype );
        $txt = '批量上传';
		$type='';
        foreach ($_type as $t) {
            $type .= '*.' . $t . ';';
        }
		
        $size = 2 * 1024;
        $js = "<script type=\"text/javascript\">
        $(function() {
        var Button=$(\"#button_" . $field . "\");
	    Button.uploadify({
		height : '22',
		width : '68',
		swf           : '".__ROOT__."/public/uploadify/uploadify.swf?ver='+ Math.random(),
		uploader      : '".url('Upload/upfile')."',
 		method   : 'post',
		formData : { 'submit' : '1',
		                    'session_id' : '" . session_id() . "'
						},
        fileTypeExts: '" . $type . "',  
        fileObjName:'file',
		buttonText: '" . $txt . "',
        queueSizeLimit: 10,
		fileSizeLimit   : '" . $size . "', 
		onUploadSuccess :function(file,data,response){
		htmlList('" . $field . "',data,file,'" . $preview . "');
		},
		'onUploadProgress':function(file,bytesUploaded,bytesTotal,totalBytesUploaded,totalBytesTotal){
			var num = Math.round(bytesUploaded / bytesTotal * 10000) / 100.00 + \"%\";
			Button.uploadify('settings','buttonText','上传：' + num);
		},
		'onQueueComplete' : function(queueData) {
			Button.uploadify('disable', false);
			Button.uploadify('settings','buttonText','上传');
			Button.uploadify('cancel','*');
		}
	    });
	    });</script>";
        $str = '
	    <fieldset>
        <legend>上传文件列表</legend>
        <div class="picList" id="list_' . $field . '_files"><ul id="' . $field . '-sort-items">';
        if ($value) {
            //$value = string2array($value);
			$value = string2array(new_html_entity_decode($value));
            $fileurl = $value['fileurl'];
            $filename = $value['filename'];
            if (is_array($fileurl) && !empty($fileurl)) {
                foreach ($fileurl as $id => $path) {
                    $str .= '<li id="files_999' . $id . '">';
                    $str .= '<input type="text" class="common-text" style="width:400px;" value="' . $fileurl[$id] . '" name="' . $field . '[fileurl][]"  id="' . $field . $id . '" ' . $preview . '>';
                    $str .= '<input type="text" class="common-text" style="width:160px;" value="' . $filename[$id] . '" name="' . $field . '[filename][]">';
                    $str .= '<a href="javascript:removediv(\'999' . $id . '\');">删除</a></li>';
                }
            }
        }
        $str .= '</ul></fieldset>
		<div style="clear: both;font-size: 1px;height: 0;line-height: 1px"></div>
		<input type="button"  value="添加地址" name="delete" onClick="add_null_file(\'' . $field . '\')" >&nbsp;
		<input id="button_' . $field . '" type="file" multiple="true">
		';
        return $js . $str;
    }
	
    
    public function number($field, $value, $fieldinfo)
    {
        extract($fieldinfo);
        if (!$value) {
            $value = intval($defaultvalue);
        }
        return '<input type="text" class="common-text" size="25" name="' . $field . '" id="' . $field . '"  value="' . $value . '"  >';
    }
    public function datetime($field, $value, $fieldinfo)
    {
        extract($fieldinfo);
        if (!$value&&$defaultvalue) {
            $value = $defaultvalue;
        }else{
			$value=date("Y-m-d H:i");
			}
        $str = '<input type="text" readonly="readonly" class="common-text" size="25" name="' . $field . '" id="' . $field . '"  value="' . $value. '"  >';
        $str .= '<script type="text/javascript">$("#' . $field . '").datetimepicker();</script>';
        return $str;
    }
}
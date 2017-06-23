<?php
namespace org;
class Content_form
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
			$kw="onBlur=\"$.post('".__ROOT__.'/api.php/keyword/get_keywords.html'."?number=3&sid='+Math.random()*5, {data:$('#title').val()}, function(data){if(data && $('#keywords').val()=='') $('#keywords').val(data); })\"";
		
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
        return '<textarea name="' . $field . '" class="common-textarea" id="' . $field . '"  style="width:96%; height:100px" >' . addslashes($value) . '</textarea>';
    }
    public function editor($field, $value, $fieldinfo)
    {
        extract($fieldinfo);
        if (!$value) {
            $value = $defaultvalue;
        }
        $str = '<script type="text/plain" id="' . $field . '"  style="width:98%;height:200px;">' . new_html_entity_decode($value) .'</script>';
        $str .= "<script type=\"text/javascript\">\r\n";
        $str .= "var um = UM.getEditor(\"{$field}\",{";
        $str .= 'UEDITOR_HOME_URL: "__PUBLIC__/ueditor/",';
        $str .= 'imageUrl: "__ROOT__/api.php/upload/ueditor",';
        $str .= 'imagePath: "__ROOT__/",';
        $str .= "textarea: '{$field}' });";
        $str .= '</script>';
        return $str;
    }
   
	public function image($field, $value , $fieldinfo)
    {   
	    
	    extract($fieldinfo);
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
				removeTimeout	  : 1,
				fileTypeExts	  : '*.jpg; *.png; *.gif;',
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
 
	public  function images($field, $value, $fieldinfo)
	{  
        extract($fieldinfo);
		$str='<input type="file" id="'.$field.'">';
	if($value){
		$value=string2array(new_html_entity_decode($value));
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
				removeTimeout	  : 1,
				fileTypeExts	  : '*.jpg; *.png; *.gif;',
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
        if($value){
		$value=date("Y-m-d H:i",$value);
	    }else{
        $value=date("Y-m-d H:i");
	    }
        $str = '<input type="text" readonly="readonly" class="common-text" size="25" name="' . $field . '" id="' . $field . '"  value="' . $value. '"  >';
        $str .= '<script type="text/javascript">$("#' . $field . '").datetimepicker();</script>';
        return $str;
    }
}
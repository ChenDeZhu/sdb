<?php
namespace org;
class Content_input
{
    var $fields, $tablepre;
    function __construct()
    {
        $this->fields = $this->get_fields();
    }
    function get_fields()
    {
        $field_array = array();
        $fields = db('field')->where(array('disabled' => 0))->order("listorder asc, id asc")->select();
        foreach ($fields as $_value) {
            $field_array[$_value['field']] = $_value;
        }
        return $field_array;
    }
    function get($data)
    {
        $data = trim_script($data);
        $info = array();
        foreach ($data as $field => $value) {
            if (!isset($this->fields[$field])) {
                continue;
            }
            $func = $this->fields[$field]['formtype'];
            if (method_exists($this, $func)) {
                $value = $this->{$func}($field, $value);
            }
            if ($this->fields[$field]['issystem']) {
                $info['a'][$field] = $value;
            } else {
                $info['b'][$field] = $value;
            }
        }
        return $info;
    }
    function datetime($field, $value)
    {
        $value = strtotime($value);
        return $value;
    }
    function editor($field, $value)
    {
        $value = trim_script($value);
        $value = auto_save_image($value);
        return $value;
    }
    function image($field, $value)
    {
        $value = remove_xss(str_replace(array("'", '"', '(', ')'), '', $value));
        $value = safe_replace($value);
        return trim($value);
    }
    function images($field, $value)
    {
        $array = $_POST[$field];
        $array = array2string($array);
        return $array;
    }
    function textarea($field, $value)
    {
        $value = strip_tags($value);
        return $value;
    }
    function text($field, $value)
    {
        $value = trim_script($value);
        return $value;
    }
}
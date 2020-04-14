<?php

class HtmlService{


    public static function ShowDate($time = null) {
        $text = "";
        if($time == null || empty($time )){

        }else{
            $text = date("Y-m-d",$time);
        }
        return $text;
    }
    public static function GenOption($list,$search_val) {
        $option_select = $search_val == NULL ? 'selected="selected"' : "";
        $content = "<option value='' {$option_select} >默认 </option>";
        foreach ($list as $_search_status_id => $_search_status_val){

            $option_select = ($_search_status_id) == ($search_val) && $search_val !== NULL ? 'selected="selected"' : "";
            $content .= "<option value=\"{$_search_status_id}\" {$option_select}>{$_search_status_val}</option>";
        }
        return $content;
    }

    public static function GenCheckboxSytleApp($list,$search_val,$input_name="") {
        $content = "";
        foreach ($list as $key => $val){
            $active = "";
            $checked = "";
            if(!empty($search_val) && in_array($key,$search_val)){
                $active = " active";
                $checked = " checked";
            }
            //if(is_array($search_val)){
            //}else{
            //if($key == $search_val){
            //    $active = " active";
            //    $checked = " checked";
            //}
            //}
            $content .= "
                <label class=\"btn btn-info {$active}\">
                <input type=\"checkbox\" name=\"{$input_name}[]\" value=\"{$key}\"  autocomplete=\"off\" {$checked}> {$val}
                </label>
                ";
        }
        return $content;
    }
    public static function GenCheckbox($list,$search_val,$input_name="") {
        //return self::gen_radio_checkbox("checkbox",$list,$search_val,$input_name);
        $content = "";
        foreach ($list as $key => $val){
            $active = "";
            $checked = "";
            if(is_array($search_val)){
                if(in_array($key,$search_val)){
                    $active = " active";
                    $checked = " checked";
                }

            }else{
                if($key === $search_val){
                    $active = " active";
                    $checked = " checked";
                }
            }
            $content .= "
                <label class=\"btn btn-info{$active}\">
                <input type=\"checkbox\" name=\"{$input_name}[]\" value=\"{$key}\"  autocomplete=\"off\" {$checked}> {$val}
                </label>
                ";
        }
        return $content;
    }
    public static function GenRadio($list,$search_val,$input_name="") {
        //$list =  array(
        //    '' => '默认',
        //) + $list;;
        //return self::gen_radio_checkbox("radio",$list,$search_val,$input_name);
        $content = "";
        $default_active = "";
        $default_checked = "";
        if(is_null($search_val)){
            $default_active = " active";
            $default_checked = " checked";
        }
        $content .= "
            <label class=\"btn btn-info{$default_active}\">
            <input type=\"radio\" name=\"{$input_name}\" value=\"\"  autocomplete=\"off\" {$default_checked}> 默认
            </label>
            ";
        foreach ($list as $key => $val){
            $active = "";
            $checked = "";
            if($key === $search_val){
                $active = " active";
                $checked = " checked";
            }
            $content .= "
                <label class=\"btn btn-info{$active}\">
                <input type=\"radio\" name=\"{$input_name}\" value=\"{$key}\"  autocomplete=\"off\" {$checked}> {$val}
                </label>
                ";
        }
        return $content;
    }

    public static function GenInput($search_val,$input_name=""){
        $content = "
            <input type=\"text\" class=\"form-control\" name=\"{$input_name}\" id=\"{$input_name}\" value=\"{$search_val}\">
            ";
        return $content;
    }

    public static function GenText($list,$search_val){
        $msg = "";
        if(IS_DEBUG == true){
            $msg = "GenText未匹配的：[{$search_val}]";
        }
        $content = isset($list[$search_val]) ? $list[$search_val] : $msg;
        $content = isset($list[$search_val]) ? $list[$search_val] : $msg;
        return $content;
    }
    //private static function gen_radio_checkbox($_type,$list,$search_val,$input_name) {
    //    $content = "";
    //    foreach ($list as $key => $val){
    //        $active = "";
    //        $checked = "";
    //        if(is_array($search_val)){
    //            if(in_array($key,$search_val)){
    //                $active = " active";
    //                $checked = " checked";
    //            }

    //        }else{
    //            if($key === $search_val){
    //                $active = " active";
    //                $checked = " checked";
    //            }
    //        }
    //        $content .= "
    //            <label class=\"btn btn-info{$active}\">
    //            <input type=\"{$_type}\" name=\"{$input_name}\" value=\"{$key}\"  autocomplete=\"off\" {$checked}> {$val}
    //            </label>
    //            ";
    //    }
    //    return $content;
    //}
}

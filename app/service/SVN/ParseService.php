<?php

namespace SVN;

class ParseService{

    
    public static function ParseInputDefault($list,$data,$default){
        foreach($list as $k => $v){
            if($k == $data){
                return $k;
            }
        }
        return $default;
    }

    public static function GetDataDimension(){
        return array(
            "year" => "年",
            "month" => "月",
            "week" => "周",
            "day" => "日",
        );
    }

    public static function GetSuffix(){
        return array(
            "java" => "java",
            "php" => "php",
            "js" => "js",
            "xml" => "xml",
            "py" => "py",
            "go" => "go",
            "phtml" => "phtml",
        );
    }
    public static function GetFilter(){
        return array(
            "on" => "开启",
            "off" => "关闭",
        );
    }

    public static function GetAction(){
        return array(
            "A" => "A-新增",
            "D" => "D-删除",
            "M" => "M-修改",
            "R" => "R-还原",
        );
    }

    public static function FormatAppsData($apps){
        $res = array();
        foreach($apps as &$a){
            $res[$a['en_name']] = "{$a['name']}<br>{$a['en_name']}";
        }
        return $res;
    }
    public static function FormatAuthorsData($apps){
        $res = array();
        foreach($apps as &$a){
            $res[$a] = $a;
        }
        return $res;
    }

}

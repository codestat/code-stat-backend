<?php
//
// codestat项目
//
// 通用类
// @Description: 
// @Version: V1.0.1
// @Author: simonsun
// @Mail: 4664919@qq.com
// @Last Modified: 2017-12-29 11:15
// @Filename: CommonService.php
//
// TODO LIST:
//
//

class CommonService {

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-29 11:15
    //
    public static function getDb() {
        return \Phalcon\DI::getDefault()->get('db');
    }

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-29 11:15
    //
    public static function getConfig() {
        return \Phalcon\DI::getDefault()->get('config');
    }

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-29 11:15
    //
    public static function setHeader() {
        header("Content-type:text/html;charset=utf-8");
    }

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-29 11:15
    //
    public static function getDbError($obj) {
        $return_html = '';
        foreach ($obj as $o) {
            $return_html .= $o->getMessage() . (IS_CLI ? "     \n" : "     <br/> \n");
        }
        return $return_html;
    }

    /**
     * 递归创建多级目录
     * @param type $filepath
     * @param type $mode
     * @return boolean
     */
    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-29 11:15
    //
    public static function mkdir($filepath, $mode = 0777) {
        $filepath = str_replace('\\', '/', $filepath);
        $root_dir = "";
        if (substr($filepath, 0, 1) == '/') {
            $root_dir = '/';
        }
        $dirArr = explode('/', $filepath);
        array_pop($dirArr);
        foreach ($dirArr as $key => $value) {
            if ($key == 0) {
                if ($root_dir == '/') {
                    $root_dir .= $value;
                } else {
                    $root_dir = $value;
                }
            } else {
                $root_dir .= '/' . $value;
            }
            if (is_file($root_dir)) {
                break;
            } elseif ( ! is_dir($root_dir)) {
                mkdir($root_dir, $mode);
            }
        }
        return true;
    }

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-29 11:15
    //
    public static function timeToDate($time, $type = 1) {
        //临时加的坑，如果不是数字先不进行转换
        if ( ! is_numeric($time)) {
            return $time;
        }
        $date = '';
        switch ($type)
        {
            case 2:
                $date = date('Y-m-d H:i:s', $time);
                break;
            case 1:
            default :
                $date = date('Y-m-d', $time);
                break;
        }
        return $date;
    }

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-29 11:15
    //
    public static function Day($time=null) {
        if($time == null){
            $time = time();
        }
        return date("Y-m-d",$time);
    }
    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-29 11:15
    //
    public static function dateToTime($date) {
        $true_date_str = str_replace(array(' ', ':'), array('-', '-'), $date);
        $true_date = explode('-', $true_date_str);
        $count = count($true_date);
        if ($count == 3) {
            $true_date[3] = '0';
            $true_date[4] = '0';
            $true_date[5] = '0';
        } elseif ($count != 6) {
            return 0;
//			throw new Phalcon\Forms\Exception('error:CommonService::dateToTime error,the date length must be 3 or 6 ,code 74');
//			exit();
        }

        return mktime($true_date[3], $true_date[4], $true_date[5], $true_date[1], $true_date[2], $true_date[0]);
    }

}

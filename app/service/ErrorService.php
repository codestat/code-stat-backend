<?php

class ErrorService {

    private static $err = array(
        //1000以内为内部错误码
        //11 => '测试,1:%s,2:%s', 
        10000 => '系统错误', 
        100000 => '系统错误,%s', 
    );

    public static function Data($data,$code){
        if(isset(self::$err[$code])){
            $param = func_get_args();
            array_shift($param);
            $param[0] = self::$err[$code];
            $msg = call_user_func_array("sprintf",$param);
            return array($code,$msg,$data); 
        }
        return array(100,"系统错误",$data);
    }

    public static function Get($code){
        if(isset(self::$err[$code])){
            $param = func_get_args();
            $param[0] = self::$err[$code];
            $msg = call_user_func_array("sprintf",$param);
            return array($code,$msg,array()); 
        }
        return array(100,"系统错误",array());
    }

    public static function CodeList(){
        return self::$err;
    }

}
//$a = ErrorService::Data(array(1,2,3,4),11,"sss","bb");
//$a = ErrorService::Get(11,"sss","bb");
//var_dump($a);
//print_r($a);


<?php

class ApiService {



    public static function curl($method,$args = array()){
        $api_address = \Phalcon\DI::getDefault()->get('config')->stat->api_address;
        $param = "";
        if(!empty($args)){
            $param = http_build_query($args);
        }
        $res = \SDK\Package\Curl::Get("{$api_address}{$method}?{$param}");
        \SDK\Package\Log::debug("curl '{$api_address}{$method}?{$param}' res:{$res}");
        if($res){
            $info = json_decode($res,1);
            if(isset($info['code']) && $info['code'] == 0){
                return array($info['data'],NULL);
            }
            return array(NULL,"接口{$method}请求失败，{$res}");
        }
        return array(NULL,"接口{$method}请求失败，".\SDK\Package\Curl::LastError());
    }

    public static function GetStatus($method){
        list($data,$status) = self::curl($method);
        $res = NULL;
        if($status === NULL){
            $res = $data['status'];
        }
        return array($res,$status);
    }

    public static function GetRevisionStatus(){
        return self::GetStatus("revision/job/get");
    }

    public static function GetRevisionPathStatus(){
        return self::GetStatus("revision_path/job/get");
    }

    public static function StartRevisionPath(){
        list($info,$status) = self::curl("revision_path/job/set",array(
            'status'=>true,
        ));
        return $status;
    }
    public static function StopRevisionPath(){
        list($info,$status) = self::curl("revision_path/job/set",array(
            //'status'=>false,
        ));
        return $status;
    }
    public static function StartRevision($reload_revision){
        list($info,$status) = self::curl("revision/job/set",array(
            'status'=>true,
            'reload'=>$reload_revision,
        ));
        return $status;
    }
    public static function StopRevision(){
        list($info,$status) = self::curl("revision/job/set",array(
            //'status'=>false,
        ));
        return $status;
    }



}

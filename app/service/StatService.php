<?php

//
// @date 2017-02-16 14:41:01
//
class StatService{

    public static $project_list = array();


    public static function GetRevisionPathSum($time){
        $where = "";
        if(is_object($time) && $time->begin > 0){
            $where .= " and r_time between {$time->begin} and $time->end";
        }
        $sql = "
            select sum(a_line) a_line,sum(d_line) d_line from revision_path where r_id in(
                select r_id from  revision where 1=1 {$where}
            ) and status>=0
            ";
        $res = \Revision::Fetch($sql);
        return $res;
    }

    //
    //return array(
    //   name="预警服务"
    //   en_name="go_alarm"
    //   projcet_path="/data/codestatproject/backend/Go/alarm/trunk/"
    //   svn_path="/backend/Go/alarm/trunk/"
    public static function GetProjectByName($name){
        if(empty(self::$project_list)){
            self::GetProject();
        }
        if (isset(self::$project_list[$name])){
            return self::$project_list[$name]; 
        }
        return array(
            'name' => $name,
            'en_name' => $name,
        );
    }
    public static function GetProject(){
        if ( !empty(self::$project_list)){
             return self::$project_list;
        }
        $config = \Phalcon\DI::getDefault()->get("config");
        list($info,$status) = \TomlService::ParseFile($config->stat->toml_path);
        if($status !== NULL){
            //todo
            //return;
        }
        //print_r($info);exit;
        self::$project_list = $info['apps'];
        return self::$project_list;
    }

    
    public static function GetAuthorLine($time,$author){

        $where = " and author='{$author}'";
        if(is_object($time) && $time->begin > 0){
            $where .= " and r_time between {$time->begin} and $time->end";
        }
        $sql = "
            select sum(a_line) a_line,sum(d_line) d_line from revision_path where r_id in(
                select r_id from  revision where 1=1 {$where}
            ) and and status>=0
            ";
        $res = \Revision::Fetch($sql);
        return $res;
    }

    public static function GetAuthor($time){

        $where = "";
        if(is_object($time) && $time->begin > 0){
            $where .= " and r_time between {$time->begin} and $time->end";
        }
        $sql = "select author from revision where 1=1 {$where} group by author";
        $res = \Revision::FetchAll($sql);
        $authors = array();
        foreach($res as $author){
            $authors[] = $author['author'];
        }
        return $authors;
    }

    public static function GetRevisionPathCount($time ,$app_name = ""){
        $where = " and status>=0";
        if(is_object($time) && $time->begin > 0){
            $where .= " and r_time between {$time->begin} and $time->end";
        }
        if($app_name){
            $where .= " and project_name='{$app_name}'";
        }

        $sql = "select sum(a_line) as a ,sum(d_line) as b from revision_path where 1=1 {$where}";
        $res = \Revision::Fetch($sql);

        return array($res['a'],$res['b']);
    }
    public static function GetRevisionCount($time ,$app_name = ""){

        $where = "";
        if(is_object($time) && $time->begin > 0){
            $where .= " and r_time between {$time->begin} and $time->end";
        }
        if($app_name){
            $where .= " and project_name='{$app_name}'";
        }

        $sql = "select count(r_id) from revision where 1=1 {$where}";
        $res = \Revision::Fetch($sql);
        $c = array_shift($res);
        return $c;
    }

}

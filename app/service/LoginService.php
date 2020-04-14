<?php

class LoginService{


    public static $ADMIN = NULL;
    public static $ADMIN_PWD = NULL;

    
    //
    // 计算权限，只加不减
    // @ date 2016-12-13 12:17:31
    // @ author simonsun
    //
    public static function CalculateOperate($group_ids,$unique_id){

        if ( ! empty($group_ids)){
            $time = time();
            //取出所有组 及其 父组
            $ids = implode(",",$group_ids);
            $sql = "select ppath from `group` where pkid in($ids)";
            $result = \Group::FetchAll($sql);
            $gids = array();
            foreach($result as $ppath){
                $ppath_ids = explode("-",$ppath['ppath']);
                foreach($ppath_ids as $gid){
                    if($gid == 0 ){
                        continue;
                    }
                    $gids[$gid] = $gid;
                }
            }
            $where = "";
            if (!empty($gids)){
                $gids_ids = implode(",",$gids);
                $where = "or gid in({$gids_ids})";
            }

            //todo 设置授权信息
            $sql = "
                insert into share_auth_detail (auth_id,unique_id,`type`,share_id,operate,`ctime`) 
                select auth_id,'{$unique_id}',`type`,share_id,operate,{$time} from share_group_detail where (gid in($ids) {$where} )";
            \ShareAuthDetail::QuerySql($sql);


            //$sql = "select `type`,share_id,operate from share_group where (gid in($ids) {$where} )";
            //$group_list = \ShareAuth::FetchAll($sql);
            //$list = array();
            //foreach($group_list as $group){
            //    $key = "{$group['share_id']}_{$group['type']}";
            //    if(isset($list[$key])){
            //        $list[$key]['operate'] = $list[$key]['operate'] | $group['operate'];
            //    }else{
            //        $list[$key] = $group;
            //    }
            //}
            //if( ! empty($list)){
            //    $time = time();
            //    $sql = "
            //        insert into share_auth (unique_id,`type`,share_id,operate,`ctime`) VALUES
            //        ";
            //    $sql_insert = array();
            //    foreach($list as $info){
            //        $sql_insert[] = "('{$unique_id}','{$info['type']}','{$info['share_id']}','{$info['operate']}',{$time})";
            //    }
            //    $sql .= implode(",",$sql_insert);
            //    \ShareAuth::QuerySql($sql);
            //}
        }
    }

    public static function GetLoginUserId(){
        $user = self::LoginInfo();
        if($user == NULL){
            return NULL;
        }
        return $user['unique_id']; 
    }

    public static function IsAdmin($unique_id){
        if($unique_id == \LoginService::$ADMIN){
            return true;
        } 
        return false;
    }

    public static function LoginInfo(){
        //
        // 首先 session 方式取用户信息
        //
        $session = \Phalcon\DI::getDefault()->get("session");
        $user = $session->get('user');
        if($user != NULL){
            if (\LoginService::IsAdmin($user['unique_id'])){
                return $user;
            }
            $u = \User::Get($user['unique_id']);
            if($u == NULL){
                $session->remove('user');
            }else{
                return $user;
            }
        }

        ////
        //// COOKIE 登录方式1期不做。
        //// cookie 方式取用户
        ////
        //$cookie = \Phalcon\DI::getDefault()->get("cookie");
        //$utoken = $cookie->get('utoken111');
        //$utoken = $utoken-> getValue('utoken111');
        ////解密
        //$crypt = \Phalcon\DI::getDefault()->get("crypt");
        //var_dump($utoken);exit;
        ////$user_info = $crypt->decryptBase64($utoken);
        //$user_info = $crypt->decrypt($utoken);
        //if($user_info == NULL){
        //    return NULL;
        //}
        //$user_info = json_decode($user_info,1);
        //if( ! empty($user_info) && isset($user_info['user_id']) && isset($user_info['pwd']) ){
        //    $user_id = $user_info['user_id'];
        //    $pwd = $user_info['pwd'];
        //}else{
        //    return NULL;
        //}

        //if($user_id != NULL && $pwd != NULL){
        //    $user = self::getUserByUnique($user);
        //    if($user == NULL){
        //        $cookie->delete('utoken');
        //        return NULL;
        //    }

        //    //检测密码
        //    if($user->pwd == $pwd){
        //        return $user; 
        //    }

        //    return NULL;
        //}

        return NULL;
    }

    //弃用
    // 管理员也走数据库
    //public static function InitAdminParams(){
    //    return array(
    //        'pkid' => 1,
    //        'unique_id' => \LoginService::$ADMIN,
    //        'nick' => '管理员',
    //        'name' => \LoginService::$ADMIN,
    //        'mail' => '',
    //        'desc' => '',
    //        'pwd' => \LoginService::$ADMIN_PWD,
    //        'inc_id' => '',
    //    );
    //}
    public static function UserLogin($user){
        //todo check $user 的合法性
        //
        //set session 
        $session = \Phalcon\DI::getDefault()->get("session");
        $session->set("user",$user);
        //set COOKIE
        //todo
        return true;
    }

    public static function PwdHash($pwd){
        $pwd = md5(md5($pwd)."jlMrBH");
        return $pwd;
    }

    public static function InitUserDisk($user){
        $uname = $user['unique_id'];
        //创建目录
        //根目录
        $dir = new \Dir();
        $dir->create_time = time();
        $dir->pid = \Disk\DirService::DIR_ROOT;
        $dir->name = "全部文件";
        $dir->name_ignore = $dir->name;
        $dir->ppath = \Disk\DirService::DIR_ROOT;
        $dir->depth = 0;
        $dir->uname = $uname;
        $is_insert = $dir->create();
        if ( ! $is_insert){
            $message = \BaseModel::GetErrorInfo($dir);
            return ("insert faild,message:{$message}"); 
        }
        //回收站
        $dir = new \Dir();
        $dir->create_time = time();
        $dir->pid = \Disk\DirService::DIR_TRASH;
        $dir->name = "回收站";
        $dir->name_ignore = $dir->name;
        $dir->ppath = \Disk\DirService::DIR_TRASH;
        $dir->depth = 0;
        $dir->uname = $uname;
        $is_insert = $dir->create();
        if ( ! $is_insert){
            $message = \BaseModel::GetErrorInfo($dir);
            return ("insert faild,message:{$message}"); 
        }
        //已删除
        $dir = new \Dir();
        $dir->create_time = time();
        $dir->pid = \Disk\DirService::DIR_DEL;
        $dir->name = "已删除";
        $dir->name_ignore = $dir->name;
        $dir->ppath = \Disk\DirService::DIR_DEL;
        $dir->depth = 0;
        $dir->uname = $uname;
        $is_insert = $dir->create();
        if ( ! $is_insert){
            $message = \BaseModel::GetErrorInfo($dir);
            return ("insert faild,message:{$message}"); 
        }
        //共享 － 回收站
        $dir = new \ShareDir();
        $dir->create_time = time();
        $dir->pid = \Disk\DirService::DIR_TRASH;
        $dir->name = "回收站";
        $dir->name_ignore = $dir->name;
        $dir->ppath = \Disk\DirService::DIR_TRASH;
        $dir->depth = 0;
        $dir->uname = $uname;
        $is_insert = $dir->create();
        if ( ! $is_insert){
            $message = \BaseModel::GetErrorInfo($dir);
            return ("insert faild,message:{$message}"); 
        }
        return NULL;
    }

    public static function GeneratePassword( $length = 8 ) {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ( $i = 0; $i < $length; $i++ ) 
        {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }

        return $password;
    }
}

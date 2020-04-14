<?php

// 
// manage project
// user 用户 controller
// @date 2017-12-28
//
class UserController extends \BaseController {

    //
    // list content
    // @date 2017-12-28
    //
    public function indexAction(){

        $search_pkid = $this->request->get('search_pkid');
        if(is_numeric($search_pkid)){
            $search_pkid= intval($search_pkid);
        }else{
            $search_pkid = NULL;
        }
        $search_unique_id = $this->request->get('search_unique_id');
        $search_nick = $this->request->get('search_nick');
        $search_mail = $this->request->get('search_mail');
        $search_desc = $this->request->get('search_desc');
        $search_pwd = $this->request->get('search_pwd');
        $search_ctime = $this->request->get('search_ctime');
        if(is_numeric($search_ctime)){
            $search_ctime= intval($search_ctime);
        }else{
            $search_ctime = NULL;
        }
        $search_modify = $this->request->get('search_modify');
        $search_avatar = $this->request->get('search_avatar');
        $search_status = $this->request->get('search_status');
        if(is_numeric($search_status)){
            $search_status= intval($search_status);
        }else{
            $search_status = NULL;
        }
        $page = max(1,(int)$this->request->get('page'));
        $limit = intval($this->request->get("limit"));
        $order = $this->request->get("order");
        $sort = $this->request->get("sort");

        $status_list = \AliasService::GetUserStatus();
        $this->view->setVar('status_list', $status_list);


        if($limit == 0){
            $limit = 20;
        }
        switch($order){

        case "unique_id" :
            $order_sql = "unique_id";
            break;
        case "nick" :
            $order_sql = "nick";
            break;
        case "mail" :
            $order_sql = "mail";
            break;
        case "desc" :
            $order_sql = "desc";
            break;
        case "pwd" :
            $order_sql = "pwd";
            break;
        case "ctime" :
            $order_sql = "ctime";
            break;
        case "modify" :
            $order_sql = "modify";
            break;
        case "avatar" :
            $order_sql = "avatar";
            break;
        case "status" :
            $order_sql = "status";
            break;
        case "pkid":
        default:
            $order_sql = "pkid";
            break;
        }
        $order = $order_sql;
        $sort = $sort == "asc" ? "asc" : "desc";
        $order_sql .= " $sort";

        $where = array(
            'condition' => '',
            'args' => array(),
        );

        if($search_pkid !== NULL){
            $where['condition'] .= " and `pkid` = ?";
            $where['args'][] = $search_pkid;
        }
        if($search_unique_id){
            $where['condition'] .= " and `unique_id` like '%{$search_unique_id}%'";
        }
        if($search_nick){
            $where['condition'] .= " and `nick` like '%{$search_nick}%'";
        }
        if($search_mail){
            $where['condition'] .= " and `mail` like '%{$search_mail}%'";
        }
        if($search_desc){
            $where['condition'] .= " and `desc` like '%{$search_desc}%'";
        }
        if($search_pwd){
            $where['condition'] .= " and `pwd` like '%{$search_pwd}%'";
        }
        if($search_ctime !== NULL){
            $where['condition'] .= " and `ctime` = ?";
            $where['args'][] = $search_ctime;
        }
        if($search_modify !== NULL){
            $where['condition'] .= " and `modify` = ?";
            $where['args'][] = $search_modify;
        }
        if($search_avatar){
            $where['condition'] .= " and `avatar` like '%{$search_avatar}%'";
        }
        if($search_status !== NULL){
            $where['condition'] .= " and `status` = ?";
            $where['args'][] = $search_status;
        }

        $count = \User::Fetch("select count(`pkid`) from `user` where 1=1 {$where['condition']}",$where['args']);
        $total = array_shift($count);
        $offset = $limit * ($page - 1);
        $sql = "
            select * from user
            where 1=1 {$where['condition']} order by {$order_sql} limit {$offset}, {$limit}
            ";
        $content = \User::FetchAll($sql,$where['args']);

        $this->ShowPage($total,$page,$limit);

        $this->view->setVars(array(
        	'search_pkid' => $search_pkid,
        	'search_unique_id' => $search_unique_id,
        	'search_nick' => $search_nick,
        	'search_mail' => $search_mail,
        	'search_desc' => $search_desc,
        	'search_pwd' => $search_pwd,
        	'search_ctime' => $search_ctime,
        	'search_modify' => $search_modify,
        	'search_avatar' => $search_avatar,
        	'search_status' => $search_status,
));
        $this->view->setVar('content', $content);  
        $this->view->setVar('total', $total);  
        $this->view->setVar('sort', $sort);  
        $this->view->setVar('order', $order);  
        $this->LoadJs("manage_list");
        if(IS_DEBUG){
            ob_start();
        }

    }


    //
    // create user
    //
    public function createAction(){
        if(IS_DEBUG){
            ob_start();
        }

        $pkid= $this->request->get('pkid');
        $unique_id= $this->request->get('unique_id');
        $nick= $this->request->get('nick');
        $mail= $this->request->get('mail');
        $desc= $this->request->get('desc');
        $pwd= $this->request->get('pwd');
        $ctime= $this->request->get('ctime');
        $modify= $this->request->get('modify');
        $avatar= $this->request->get('avatar');
        $status= $this->request->get('status');

$user = new \User();
        $user->pkid = $pkid;
        $user->unique_id = $unique_id;
        $user->nick = $nick;
        $user->mail = $mail;
        $user->desc = $desc;
        $user->pwd = $pwd;
        $user->ctime = $ctime;
        $user->modify = $modify;
        $user->avatar = $avatar;
        $user->status = $status;

        if($user->create()){
            return $this->Res();
        }
        $error_msg = (\User::GetErrorInfo($user));
        return $this->ResError(\ErrorService::Data($error_msg,100000,"创建失败"));

    }


    //
    // update user
    // @date 2017-12-28
    //
    public function updateAction(){
        if(IS_DEBUG){
            ob_start();
        }

        $pkid = intval($this->request->get('pkid'));
        if( ! $pkid){
            return $this->ResError(\ErrorService::Get(100000,"pkid:pkid必填"));
        }
        $user = \User::findFirst($pkid);
        if( ! $user){
            return $this->ResError(\ErrorService::Get(100000,"user pkid:pkid not found"));
        }
        $pkid= $this->request->get('pkid');
        $unique_id= $this->request->get('unique_id');
        $nick= $this->request->get('nick');
        $mail= $this->request->get('mail');
        $desc= $this->request->get('desc');
        $pwd= $this->request->get('pwd');
        $ctime= $this->request->get('ctime');
        $modify= $this->request->get('modify');
        $avatar= $this->request->get('avatar');
        $status= $this->request->get('status');

        $user->pkid = $pkid;
        $user->unique_id = $unique_id;
        $user->nick = $nick;
        $user->mail = $mail;
        $user->desc = $desc;
        $user->pwd = $pwd;
        $user->ctime = $ctime;
        $user->modify = $modify;
        $user->avatar = $avatar;
        $user->status = $status;

        if($user->save()){
            return $this->Res();
        }
        $error_msg = (\User::GetErrorInfo($user));
        return $this->ResError(\ErrorService::Data($error_msg,100000,"保存失败"));
    }


    //
    // update and create user view
    // @date 2017-12-28
    //
    public function viewAction(){

        $pkid = intval($this->request->get('pkid'));
        if($pkid){
            $user = \User::findFirst($pkid);
        }else{
            $user = new \User();
        }
        $status_list = \AliasService::GetUserStatus();
        $this->view->setVar('status_list', $status_list);

        $this->view->setVar ('pkid', $pkid);
        $this->view->setVar ('user', $user);
        $this->LoadJs("manage_info");
        if(IS_DEBUG){
            ob_start();
        }

    }




    //
    // info user view
    // @date 2017-12-28
    //
    public function infoAction(){

        $pkid = intval($this->request->get('pkid'));
        if($pkid){
            $user = \User::findFirst($pkid);
        }else{
            return $this->ResError(\ErrorService::Get(100000,"user pkid:pkid not found"));
        }
        $status_list = \AliasService::GetUserStatus();
        $this->view->setVar('status_list', $status_list);

        $this->view->setVar ('pkid', $pkid);
        $this->view->setVar ('user', $user);
        $this->LoadJs("manage_info");
        if(IS_DEBUG){
            ob_start();
        }

    }

}

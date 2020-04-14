<?php

// 
// manage project
// revision 版本号 controller
// @date 2017-12-26
//
class RevisionController extends \BaseController {

    //
    // list content
    // @date 2017-12-26
    //
    public function indexAction(){

        CommonService::setHeader();
        $search_pkid = $this->request->get('search_pkid');
        if(is_numeric($search_pkid)){
            $search_pkid= intval($search_pkid);
        }else{
            $search_pkid = NULL;
        }
        $search_r_id = $this->request->get('search_r_id');
        if(is_numeric($search_r_id)){
            $search_r_id= intval($search_r_id);
        }else{
            $search_r_id = NULL;
        }
        $search_project_name = $this->request->get('search_project_name');
        $search_author = $this->request->get('search_author');
        $search_r_time = $this->request->get('search_r_time');
        if(is_numeric($search_r_time)){
            $search_r_time= intval($search_r_time);
        }else{
            $search_r_time = NULL;
        }
        $search_modify = $this->request->get('search_modify');
        $page = max(1,(int)$this->request->get('page'));
        $limit = intval($this->request->get("limit"));
        $order = $this->request->get("order");
        $sort = $this->request->get("sort");



        if($limit == 0){
            $limit = 20;
        }
        switch($order){

        case "r_id" :
            $order_sql = "r_id";
            break;
        case "project_name" :
            $order_sql = "project_name";
            break;
        case "author" :
            $order_sql = "author";
            break;
        case "r_time" :
            $order_sql = "r_time";
            break;
        case "modify" :
            $order_sql = "modify";
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
        if($search_r_id !== NULL){
            $where['condition'] .= " and `r_id` = ?";
            $where['args'][] = $search_r_id;
        }
        if($search_project_name){
            $where['condition'] .= " and `project_name` like '%{$search_project_name}%'";
        }
        if($search_author){
            $where['condition'] .= " and `author` like '%{$search_author}%'";
        }
        if($search_r_time !== NULL){
            $where['condition'] .= " and `r_time` = ?";
            $where['args'][] = $search_r_time;
        }
        if($search_modify !== NULL){
            $where['condition'] .= " and `modify` = ?";
            $where['args'][] = $search_modify;
        }

        $count = \Revision::Fetch("select count(`pkid`) from `revision` where 1=1 {$where['condition']}",$where['args']);
        $total = array_shift($count);
        $offset = $limit * ($page - 1);
        $sql = "
            select * from revision
            where 1=1 {$where['condition']} order by {$order_sql} limit {$offset}, {$limit}
            ";
        $content = \Revision::FetchAll($sql,$where['args']);

        $this->ShowPage($total,$page,$limit);

        $this->view->setVars(array(
        	'search_pkid' => $search_pkid,
        	'search_r_id' => $search_r_id,
        	'search_project_name' => $search_project_name,
        	'search_author' => $search_author,
        	'search_r_time' => $search_r_time,
        	'search_modify' => $search_modify,
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
    // update and create revision view
    // @date 2017-12-26
    //
    public function viewAction(){
        CommonService::setHeader();

        $pkid = intval($this->request->get('pkid'));
        if($pkid){
            $revision = \Revision::findFirst($pkid);
        }else{
            $revision = new \Revision();
        }

        $this->view->setVar ('pkid', $pkid);
        $this->view->setVar ('revision', $revision);
        $this->LoadJs("manage_info");
        if(IS_DEBUG){
            ob_start();
        }

    }




    //
    // info revision view
    // @date 2017-12-26
    //
    public function infoAction(){
        CommonService::setHeader();

        $pkid = intval($this->request->get('pkid'));
        if($pkid){
            $revision = \Revision::findFirst($pkid);
        }else{
            return $this->ResError(\ErrorService::Get(100000,"revision pkid:pkid not found"));
        }

        $this->view->setVar ('pkid', $pkid);
        $this->view->setVar ('revision', $revision);
        $this->LoadJs("manage_info");
        if(IS_DEBUG){
            ob_start();
        }

    }

}

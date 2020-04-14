<?php

// 
// manage project
// revision_path 版本号具体的提交路径 controller
// @date 2017-12-26
//
class RevisionPathController extends \BaseController {

    public function svn_infoAction(){
        CommonService::setHeader();

        $pkid = intval($this->request->get('pkid'));
        if($pkid){
            $revision_path = \RevisionPath::findFirst($pkid);
        }else{
            return $this->ResError(\ErrorService::Get(100000,"revision_path pkid:pkid not found"));
        }
        $msg = exec($revision_path->cmd,$out);
        //p1("exec:",$revision_path->cmd);
        //p1("res:",$out);

        $kind_list = \AliasService::GetRevisionPathKind();
        $this->view->setVar('kind_list', $kind_list);
        $action_list = \AliasService::GetRevisionPathAction();
        $this->view->setVar('action_list', $action_list);
        $status_list = \AliasService::GetRevisionPathStatus();
        $this->view->setVar('status_list', $status_list);
        $this->view->setVar ('cmd', $out);
        $this->view->setVar ('msg', $msg);
        $this->view->setVar ('pkid', $pkid);
        $this->view->setVar ('revision_path', $revision_path);
        $this->LoadJs("manage_info");
        if(IS_DEBUG){
            ob_start();
        }


    }

    //
    // list content
    // @date 2017-12-26
    //
    public function indexAction(){
        CommonService::setHeader();

        $begin_date = $this->request->get('begin_date');
        $end_date = $this->request->get('end_date');
        list($time,$err) = \TimeService::NewTimeRange($begin_date,$end_date);
        $entity_time  = null;
        if(is_object($time) && $time->begin > 0){
            $entity_time = $time;
        }else{
        }


        $search_author = $this->request->get('search_author');
        $search_project_name = $this->request->get('search_project_name');
        $search_path = $this->request->get('search_path');
        $search_r_id = $this->request->get('search_r_id');
        if(is_numeric($search_r_id)){
            $search_r_id= intval($search_r_id);
        }else{
            $search_r_id = NULL;
        }
        $search_a_line = $this->request->get('search_a_line');
        if(is_numeric($search_a_line)){
            $search_a_line= intval($search_a_line);
        }else{
            $search_a_line = NULL;
        }
        $search_d_line = $this->request->get('search_d_line');
        if(is_numeric($search_d_line)){
            $search_d_line= intval($search_d_line);
        }else{
            $search_d_line = NULL;
        }
        $search_kind = $this->request->get('search_kind');
        $search_status = $this->request->get('search_status');
        $status = [];
        if(!empty($search_status)){
            foreach($search_status as $_status){
                 $status[] = intval($_status);
            }
        }
        $search_action = $this->request->get('search_action');
        $action = [];
        if(!empty($search_action)){
            foreach($search_action as $_action){
                 $action[] = $_action;
            }
        }
        $page = max(1,(int)$this->request->get('page'));
        $limit = intval($this->request->get("limit"));
        $order = $this->request->get("order");
        $sort = $this->request->get("sort");

        $kind_list = \AliasService::GetRevisionPathKind();
        $this->view->setVar('kind_list', $kind_list);
        $action_list = \AliasService::GetRevisionPathAction();
        $this->view->setVar('action_list', $action_list);
        $status_list = \AliasService::GetRevisionPathStatus();
        $this->view->setVar('status_list', $status_list);


        if($limit == 0){
            $limit = 20;
        }
        switch($order){

        case "r_id" :
            $order_sql = "r_id";
            break;
        case "modify" :
            $order_sql = "modify";
            break;
        case "kind" :
            $order_sql = "kind";
            break;
        case "action" :
            $order_sql = "action";
            break;
        case "path" :
            $order_sql = "path";
            break;
        case "author" :
            $order_sql = "author";
            break;
        case "project_name" :
            $order_sql = "project_name";
            break;
        case "r_time" :
            $order_sql = "r_time";
            break;
        case "a_line" :
            $order_sql = "a_line";
            break;
        case "d_line" :
            $order_sql = "d_line";
            break;
        case "cmd" :
            $order_sql = "cmd";
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

        if($search_author){
            $where['condition'] .= " and `author` like '%{$search_author}%'";
        }
        if(! is_null($entity_time)){
            $where['condition'] .= "and r_time between {$entity_time->begin} and {$entity_time->end}";
        }

        if($search_project_name){
            $where['condition'] .= " and `project_name` like '%{$search_project_name}%'";
        }
        if($search_path){
            $where['condition'] .= " and `path` like '%{$search_path}%'";
        }
        if($search_r_id !== NULL){
            $where['condition'] .= " and `r_id` = ?";
            $where['args'][] = $search_r_id;
        }
        if($search_a_line !== NULL){
            $where['condition'] .= " and `a_line` = ?";
            $where['args'][] = $search_a_line;
        }
        if($search_d_line !== NULL){
            $where['condition'] .= " and `d_line` = ?";
            $where['args'][] = $search_d_line;
        }
        if($search_kind !== NULL){
            $where['condition'] .= " and `kind` = ?";
            $where['args'][] = $search_kind;
        }
        if( ! empty($status)){

            $where['condition'] .= " and `status` in (".str_repeat('?,', count($status) - 1) . '?'.")";
            //$where['args'] += $status;
            $where['args'] = array_merge($where['args'],$status);
             //array_push()
        }
        if( ! empty($action)){

            $where['condition'] .= " and `action` in (".str_repeat('?,', count($action) - 1) . '?'.")";
            $where['args'] = array_merge($where['args'],$action);
        }

        $count = \RevisionPath::Fetch("select count(`pkid`) from `revision_path` where 1=1 {$where['condition']}",$where['args']);
        $total = array_shift($count);
        $offset = $limit * ($page - 1);
        $sql = "
            select * from revision_path
            where 1=1 {$where['condition']} order by {$order_sql} limit {$offset}, {$limit}
            ";
        $content = \RevisionPath::FetchAll($sql,$where['args']);

        $this->ShowPage($total,$page,$limit);

        $this->view->setVars(array(
        	'search_author' => $search_author,
        	'search_project_name' => $search_project_name,
        	'search_path' => $search_path,
        	'search_r_id' => $search_r_id,
        	'search_a_line' => $search_a_line,
        	'search_d_line' => $search_d_line,
        	'search_kind' => $search_kind,
        	'search_status' => $search_status,
        	'search_action' => $search_action,
));
        $this->view->setVar('content', $content);  
        $this->view->setVar('total', $total);  
        $this->view->setVar('sort', $sort);  
        $this->view->setVar('time', $entity_time);  
        $this->view->setVar('order', $order);  
        $this->LoadJs("manage_list");
        if(IS_DEBUG){
            ob_start();
        }

    }






    //
    // update and create revision_path view
    // @date 2017-12-26
    //
    public function viewAction(){
        CommonService::setHeader();

        $pkid = intval($this->request->get('pkid'));
        if($pkid){
            $revision_path = \RevisionPath::findFirst($pkid);
        }else{
            $revision_path = new \RevisionPath();
        }
        $kind_list = \AliasService::GetRevisionPathKind();
        $this->view->setVar('kind_list', $kind_list);
        $action_list = \AliasService::GetRevisionPathAction();
        $this->view->setVar('action_list', $action_list);
        $status_list = \AliasService::GetRevisionPathStatus();
        $this->view->setVar('status_list', $status_list);

        $this->view->setVar ('pkid', $pkid);
        $this->view->setVar ('revision_path', $revision_path);
        $this->LoadJs("manage_info");
        if(IS_DEBUG){
            ob_start();
        }

    }




    //
    // info revision_path view
    // @date 2017-12-26
    //
    public function infoAction(){
        CommonService::setHeader();

        $pkid = intval($this->request->get('pkid'));
        if($pkid){
            $revision_path = \RevisionPath::findFirst($pkid);
        }else{
            return $this->ResError(\ErrorService::Get(100000,"revision_path pkid:pkid not found"));
        }
        $kind_list = \AliasService::GetRevisionPathKind();
        $this->view->setVar('kind_list', $kind_list);
        $action_list = \AliasService::GetRevisionPathAction();
        $this->view->setVar('action_list', $action_list);
        $status_list = \AliasService::GetRevisionPathStatus();
        $this->view->setVar('status_list', $status_list);

        $this->view->setVar ('pkid', $pkid);
        $this->view->setVar ('revision_path', $revision_path);
        $this->LoadJs("manage_info");
        if(IS_DEBUG){
            ob_start();
        }

    }

}

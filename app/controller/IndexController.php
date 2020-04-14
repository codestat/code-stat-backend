<?php
//
// codestat项目
//
// codestat项目
// @Description: 
// @Version: V1.0.1
// @Author: simonsun
// @Mail: 4664919@qq.com
// @Last Modified: 2017-12-27 19:47
// @Filename: IndexController.php
//
// TODO LIST:
//
//

class IndexController extends BaseController {

    //
    // 重载
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:47
    //
    public function reload_regexpAction(){
        $is_re        = (bool)($this->request->get('is_refresh_all'));
        $p            = $this->request->get('project','string');
        $project_list = \Phalcon\DI::getDefault()->get("config")->project;
        $filter = \Phalcon\DI::getDefault()->get("config")->stat->filter_suffix;

        if($is_re && !isset($p)){
            p1("重载所有项目过滤状态");
            
            //
            // 全部置为显示
            // @Author: simonsun
            // @Last Modified: 2017-12-27 17:49
            //
            $sql = "update revision_path set status=1";
            \RevisionPath::QuerySql($sql);
            if(!empty($filter)){
                $filter = explode(",",$filter);
                $filter = implode("','",$filter);
                $sql = "update revision_path set status=-1 where substr(path,-4,4) in('{$filter}')";
                \RevisionPath::QuerySql($sql);
            }
        }
        $count = count($project_list);
        foreach($project_list as $project => $regexp){
            if(isset($p) &&
                $project !== $p
            
            ){
                continue;
            }
            p1("开始处理项目，正则:",$project,$regexp);
            $sql = "update revision_path set status=-1 where project_name='{$project}' and status>=0 and path REGEXP '{$regexp}'";
            \RevisionPath::QuerySql($sql);
            $sql = "update revision_path set status=1 where project_name='{$project}' and status=0";
            \RevisionPath::QuerySql($sql);
        }
        p1("全部处理完毕{$count}个");
        $this->Res();

    }
    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:47
    //
    public function parseAction(){
        $begin_date     = $this->request->get('begin_date');
        $end_date       = $this->request->get('end_date');
        $data_dimension = $this->request->get('data_dimension');
        $app_name       = $this->request->get('app_name');
        $author_name    = $this->request->get('author_name');
        $filter_name    = $this->request->get('filter_name');
        $action_name    = $this->request->get('action_name');
        list($time,$err) = \TimeService::NewTimeRange($begin_date,$end_date);
        if($err !== NULL){
            //echo $err;
        }
        $data_dimension_list = \SVN\ParseService::GetDataDimension();
        $filter_list = \SVN\ParseService::GetFilter();
        $action_list = \SVN\ParseService::GetAction();
        if(!$data_dimension){
            $data_dimension = \SVN\ParseService::ParseInputDefault($data_dimension_list,$data_dimension,'year');//默认是年
        }
        if(!$filter_name){
            $filter_name = \SVN\ParseService::ParseInputDefault($filter_list,$filter_name,'off');//默认是关闭的
        }
        //取所有项目
        $apps = \StatService::GetProject();
        $app_list = \SVN\ParseService::FormatAppsData($apps);

        //取所有用户
        $authors = \StatService::GetAuthor($time);
        $author_list = \SVN\ParseService::FormatAuthorsData($authors);

        //$data = \SVN\ParseService::GetData();
        $data = array();
        if(is_object($time) && $time->begin > 0){
            $entity_time = $time;
        }else{

            //list($entity_time,$err) = \TimeService::NewTimeRange("2014-01-01",date("Y-m-d"));
            list($entity_time,$err) = \TimeService::NewTime(date("Y-m-d",time()-86400*30),"month");
            if ($err !== NULL){
                p1("---- err:",$err);
            }
        }
        $data = \SVN\TimeRangeService::Parse($entity_time,$data_dimension);
        $total = \SVN\TimeRangeService::CreateLineChartTotal($entity_time);
        \SVN\TimeRangeService::CreateLineChart($data,$total);
        //p($total);

        $this->view->setVar('data', $data);  
        $this->view->setVar('total', $total);  
        $this->view->setVar('time', $time);  
        $this->view->setVar('data_dimension', $data_dimension);
        $this->view->setVar('data_dimension_list', $data_dimension_list);
        $this->view->setVar('author_name', $author_name);
        $this->view->setVar('app_name', $app_name);
        $this->view->setVar('author_list', $author_list);
        $this->view->setVar('app_list', $app_list);
        $this->view->setVar('filter_name', $filter_name);
        $this->view->setVar('action_name', $action_name);
        $this->view->setVar('filter_list', $filter_list);
        $this->view->setVar('action_list', $action_list);
    }

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:47
    //
    public function indexAction(){
        $sql = "select sum(a_line) as a_line ,sum(d_line) as d_line, (sum(a_line) +sum(d_line) ) total from revision_path
where status=1";
        $res = \BaseModel::Fetch($sql);

        $date = date("Y",time());
        $time = strtotime("{$date}-01-01");
        $sql = "select sum(a_line) as a_line ,sum(d_line) as d_line, (sum(a_line) +sum(d_line) ) total from revision_path
where 
status=1 and
r_time >= '{$time}' ";
        $res_this_year = \BaseModel::Fetch($sql);
        foreach($res as &$r){
            $r = number_format($r,0);
        }
        foreach($res_this_year as &$r){
            $r = number_format($r,0);
        }
        
        $apps    = \StatService::GetProject();//取所有项目
        $authors = \StatService::GetAuthor($time);//取所有用户
        $this->view->setVar('apps'             , $apps);
        $this->view->setVar('authors'          , $authors);
        $this->view->setVar('a_line'           , $res['a_line']);
        $this->view->setVar('d_line'           , $res['d_line']);
        $this->view->setVar('total'            , $res['total']);
        $this->view->setVar('this_year_a_line' , $res_this_year['a_line']);
        $this->view->setVar('this_year_d_line' , $res_this_year['d_line']);
        $this->view->setVar('this_year_total'  , $res_this_year['total']);
    }
    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:47
    //
    public function overviewAction(){

        $begin_date = $this->request->get('begin_date');//起始时间
        $end_date = $this->request->get('end_date');//结束时间
        list($time,$err) = \TimeService::NewTimeRange($begin_date,$end_date);

        if($err !== NULL){
            //echo $err;
            list($time,$err) = \TimeService::NewTime(date("Y-m-d",strtotime(date('Y-m-01')) - 10),"month");
        }

        //取 ci 次数
        $r_total_count = \StatService::GetRevisionCount($time);
        $rp_count      = \StatService::GetRevisionPathSum($time);

        //取所有项目
        $apps          = \StatService::GetProject();

        //取所有用户
        $authors       = \StatService::GetAuthor($time);

        //取 每个项目的ci次数
        foreach($apps as &$app){
            $app['r_count'] = \StatService::GetRevisionCount($time,$app['en_name']);
            list($app['a_line'],$app['d_line']) = \StatService::GetRevisionPathCount($time,$app['en_name']);
        }
        //p($apps);
        $this->view->setVar('time'          , $time);
        $this->view->setVar('apps'          , $apps);
        $this->view->setVar('authors'       , $authors);
        $this->view->setVar('rp_count'      , $rp_count);
        $this->view->setVar('r_total_count' , $r_total_count);
    }
    
    //
    // 项目管理
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:47
    //
    public function projectAction(){


    }
    
    //
    // 用户管理
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:47
    //
    public function userAction(){
    }
    
    //
    // 404页面
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:47
    //
    public function notfoundAction(){
        $module = strtolower($this->router->getModuleName());
        $controller = strtolower($this->router->getControllerName());
        $action = strtolower($this->router->getActionName());
        //echo "module:{$module},\ncontroller:{$controller};action:{$action}";
        p("module\t\t:{$module}","controller\t\t:{$controller}","action\t\t:{$action}");
        $this->view->setMainView('layout/404');
    }

    
    //
    // excel 导出
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:47
    //
    public function total_excel_author_projectAction(){

        $begin_date      = $this->request->get('begin_date');
        $end_date        = $this->request->get('end_date');
        $filter_name     = $this->request->get('filter_name');
        list($time,$err) = \TimeService::NewTimeRange($begin_date,$end_date);
        if($err !== NULL){
        }
        $filter_list = \SVN\ParseService::GetFilter();
        if(!$filter_name){
            $filter_name = \SVN\ParseService::ParseInputDefault($filter_list,$filter_name,'on');//默认是关闭的
        }

        $data = array();
        if(is_object($time) && $time->begin > 0){
            $entity_time = $time;
        }else{
            list($entity_time,$err) = \TimeService::NewTime(date("Y-m-d",strtotime(date('Y-m-01')) - 10),"month");
            if ($err !== NULL){
                p1("---- err:",$err);
            }
        }
        $total = \SVN\TotalService::CreateLineChartTotal($entity_time,$filter_name);
        \SDK\Package\Excel::init();
        $header = array(
            '序号',
            '项目',
            '作者',
            '提交文件数',
            '新增行数',
            '删除行数',
            '操作行数',
        );

        $data = array();
        
        $key=0;

        foreach ($total['project_author_list'] as  $project_name => $p_data){
            $key++;
            $array = array(
                $key ,
                \StatService::GetProjectByName($project_name)['name']."[{$project_name}]",
            );
            foreach ($p_data as $_k => $d){
                if($_k == 0){
                     $_a = $array;
                }else{
                     $_a = array("","");
                }
                $_a[] = $d['author'];
                $_a[] = $d['r_id'];
                $_a[] = $d['a_line'];
                $_a[] = $d['d_line'];
                $_a[] = $d['line'];
                $data[] = $_a;
            }
        }
        \Excel::exportExcel("分析项目作者{$entity_time->begin_date}-{$entity_time->end_date}",$header,$data);
        exit;
    }
    
    //
    // excel 导出
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:47
    //
    public function total_excel_authorAction(){
        list($entity_time,$err) = $this->GetDefaultTime();
        $total = $this->totalCommon();

        \SDK\Package\Excel::init();
        $header = array(
            '序号',
            '作者',
            '提交文件数',
            '新增行数',
            '删除行数',
            '操作行数',
        );
        $data = array();
        foreach ($total['author_list'] as  $key => $d){
            $data[] = array(
                $key+1 ,
                $d['author'],
                $d['r_id'],
                $d['a_line'],
                $d['d_line'],
                $d['line'],
            );
        }
        \Excel::exportExcel("分析作者{$entity_time->begin_date}-{$entity_time->end_date}",$header,$data);
        exit;
    }

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:47
    //
    public function total_excel_projectAction(){
        list($entity_time,$err) = $this->GetDefaultTime();
        $total = $this->totalCommon();
        \SDK\Package\Excel::init();
        $header = array(
            '序号',
            '项目',
            '提交文件数',
            '新增行数',
            '删除行数',
            '操作行数',
        );
        $data = array();
        foreach ($total['project_list'] as  $key => $d){
            $data[] = array(
                $key+1 ,
                \StatService::GetProjectByName($d['project_name'])['name']."[{$d['project_name']}]",
                $d['r_id'],
                $d['a_line'],
                $d['d_line'],
                $d['line'],
            );
        }
        \Excel::exportExcel("分析项目{$entity_time->begin_date}-{$entity_time->end_date}",$header,$data);
        exit;
    }

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:47
    //
    public function totalAction(){
        $total = $this->totalCommon();

        $get = $_GET;
        if(isset($get['_url'])){
            unset($get['_url']);
        }
        $this->view->setVar('total'         , $total);


        $this->view->setVar('exce_url'      , http_build_query($get));
    }

    private function totalCommon(){

        $begin_date = $this->request->get('begin_date');
        $end_date = $this->request->get('end_date');
        $filter_name = $this->request->get('filter_name');
        $suffix = $this->request->get('suffix');
        $custom_suffix = $this->request->get('custom_suffix');
        list($time,$err) = \TimeService::NewTimeRange($begin_date,$end_date);
        if($err !== NULL){
            //echo $err;
        }
        $filter_list = \SVN\ParseService::GetFilter();
        $suffix_list = \SVN\ParseService::GetSuffix();

        //
        // 增加后缀筛选
        // @Author: simonsun
        // @Last Modified: 2017-12-01 16:26
        //
        //
        $where = '';
        if(!empty($custom_suffix)){
            $_custom_suffix = explode(',',$custom_suffix);
            if(empty($suffix)){
                $suffix = $_custom_suffix;
            }else{
                $suffix = $suffix + $_custom_suffix;
            }
        }
        if( !empty($suffix) && is_array($suffix)){
            $_suffix = \SVN\TotalService::FilterSuffix($suffix);
            if(!empty($_suffix)){
                $s = implode("','",$_suffix);
                $where .= " and (
                    path REGEXP '\\.([a-z]+)$' and
                    SUBSTRING_INDEX(path,'.',-1) in ('{$s}')
                )
";
//        "
//select 
//SUBSTRING_INDEX(path,'.',-1) as a
//from revision_path 
//where kind='file'
//and path REGEXP '\\.([a-z]+)$'
//group by a
//-- limit 10
//;
//
//"
            }

        }
        if(!$filter_name){
            $filter_name = \SVN\ParseService::ParseInputDefault($filter_list,$filter_name,'on');//默认是关闭的
        }

        $data = array();
        if(is_object($time) && $time->begin > 0){
            $entity_time = $time;
        }else{
            //list($entity_time,$err) = \TimeService::NewTimeRange("2014-01-01",date("Y-m-d"));
            list($entity_time,$err) = \TimeService::NewTime(date("Y-m-d",strtotime(date('Y-m-01')) - 10),"month");
            if ($err !== NULL){
                p1("---- err:",$err);
            }
        }
        $total = \SVN\TotalService::CreateLineChartTotal($entity_time,$filter_name,$where);
        //p($total);


        $this->view->setVar('data'          , $data);
        $this->view->setVar('time'          , $entity_time);
        $this->view->setVar('filter_name'   , $filter_name);
        $this->view->setVar('filter_list'   , $filter_list);
        $this->view->setVar('suffix_list'   , $suffix_list);
        $this->view->setVar('suffix_name'   , $suffix);
        $this->view->setVar('custom_suffix' , $custom_suffix);
        return $total;
    }
}

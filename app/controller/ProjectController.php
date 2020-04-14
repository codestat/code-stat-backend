<?php
//
// codestat项目
//
// codestat项目
// @Description: 
// @Version: V1.0.1
// @Author: simonsun
// @Mail: 4664919@qq.com
// @Last Modified: 2017-12-27 20:03
// @Filename: ProjectController.php
//
// TODO LIST:
//
//


//
// 项目管理控制器
// @Last Modified: 2017-12-27 20:03
//
class ProjectController extends BaseController {

    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 20:03
    //
    public function indexAction(){

        $project_name = $this->request->get('project','string');
        if(empty($project_name)){
            return $this->ResError(\ErrorService::Get(100000,"project错误"));
        }
        $project = \StatService::GetProjectByName($project_name);
        if(empty($project)){
            return $this->ResError(\ErrorService::Get(100000,"project错误"));
        }
        $sql = "
select sum(a_line) as a_line ,sum(d_line) as d_line, (sum(a_line) +sum(d_line) ) total from revision_path as rp
left join revision as r on r.r_id=rp.r_id
where status=1 and 
r.project_name = ?
";
        $res = \BaseModel::Fetch($sql, array($project['en_name']));

        $date = date("Y",time());
        $time = strtotime("{$date}-01-01");
        $sql = "
select sum(a_line) as a_line ,sum(d_line) as d_line, (sum(a_line) +sum(d_line) ) total from revision_path rp
left join revision as r on r.r_id=rp.r_id
where 
status=1 and
rp.r_time >= '{$time}' 
and 
r.project_name = ?
";
        $res_this_year = \BaseModel::Fetch($sql, array($project['en_name']));
        foreach($res as &$r){
            $r = number_format($r,0);
        }
        foreach($res_this_year as &$r){
            $r = number_format($r,0);
        }

        $this->view->setVar('a_line'           , $res['a_line']);
        $this->view->setVar('d_line'           , $res['d_line']);
        $this->view->setVar('total'            , $res['total']);
        $this->view->setVar('project'          , $project);
        $this->view->setVar('this_year_a_line' , $res_this_year['a_line']);
        $this->view->setVar('this_year_d_line' , $res_this_year['d_line']);
        $this->view->setVar('this_year_total'  , $res_this_year['total']);




    }
}


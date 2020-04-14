<?php
//
// codestat项目
//
// codestat项目
// @Description: 
// @Version: V1.0.1
// @Author: simonsun
// @Mail: 4664919@qq.com
// @Last Modified: 2017-12-29 11:24
// @Filename: BackendController.php
//
// TODO LIST:
//
//


class BackendController extends BaseController {

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-29 11:24
    //
    public function system_statusAction(){
        $reload_revision = $this->request->get('reload_revision','int');

        //wait_complete
        $sql = "select count(*) from revision_path where a_line=0 and d_line=0  and kind='file' and status>=0";
        $res = \RevisionPath::Fetch($sql);
        $wait_complete = array_shift($res);

        //error_count
        $sql = "select count(*) from revision_path where a_line=0 and d_line=0  and kind='file' and status<=-10000";
        $res = \RevisionPath::Fetch($sql);
        $error_count = array_shift($res);

        list($revision_status,$status) = \ApiService::GetRevisionStatus();
        if($status != NULL){
            return $this->ResError(\ErrorService::Get(100000,"\ApiService::GetRevisionStatus()faild，{$status}"));
        }
        list($revision_path_status,$status) =\ApiService::GetRevisionPathStatus();
        if($status != NULL){
            return $this->ResError(\ErrorService::Get(100000,"\ApiService::GetRevisionPathStatus()faild，{$status}"));
        }

        $this->view->setVar ('revision_status', $revision_status);
        $this->view->setVar ('revision_path_status', $revision_path_status);

        $this->view->setVar ('revision_msg', $revision_status == true ? "允许启动" : "禁止启动");
        $this->view->setVar ('revision_path_msg', $revision_path_status == true ? "允许启动" : "禁止启动");

        $this->view->setVar ('revision_button', $revision_status == true ? "停止并禁用任务" : "启动并允许启动");
        $this->view->setVar ('revision_path_button', $revision_path_status == true ? "停止并禁用任务" : "启动并允许启动");

        $this->view->setVar ('revision_class', $revision_status == true ? "on" : "off");
        $this->view->setVar ('revision_path_class', $revision_path_status == true ? "on" : "off");

        $this->view->setVar ('reload_revision', $reload_revision);
        $this->view->setVar ('wait_complete', number_format($wait_complete));
        $this->view->setVar ('error_count', number_format($error_count));

    }

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-29 11:24
    //
    public function system_status_setAction(){
        $reload_revision = (boolean)$this->request->get('reload_revision');
        $job_status = (bool)$this->request->get('status');
        $type = $this->request->get('type');
        $status = NULL;

        if ($type == "revision_path"){
            if($job_status){
                $status = \ApiService::StartRevisionPath();
            }else{
                $status = \ApiService::StopRevisionPath();
            }
        }else{
            if($job_status){
                $status = \ApiService::StartRevision($reload_revision);
            }else{
                $status = \ApiService::StopRevision();
            }
        }
        if($status != NULL){
            return $this->ResError(\ErrorService::Get(100000,"\ApiService faild，{$status}"));
        }
        return $this->Redirect("/backend/system_status");
    }
}

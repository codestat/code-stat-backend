<?php
//
// codestat项目
//
// codestat项目
// @Description: 
// @Version: V1.0.1
// @Author: simonsun
// @Mail: 4664919@qq.com
// @Last Modified: 2017-12-27 19:43
// @Filename: AuthorController.php
//
// TODO LIST:
//
//


class AuthorController extends BaseController {


    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:43
    //
    public function indexAction(){

        $author = $this->request->get('author','string');

        // 取author
        $author_info = \Author\StrategyService::GetAuthor($author);
        //p($author_info);
        if(! $author_info){
            return $this->ResError(\ErrorService::Get(100000,"author 作者未找到"));
        }
        
        $main_count     = \Author\StrategyService::GetMainProject($author);// 核心开发项目数量
        $count_commit   = \Author\StrategyService::CountCommit($author);// 提交过于频繁
        $count_content  = \Author\StrategyService::CountContent($author);// 提交内容相同
        $count_file     = \Author\StrategyService::CountFile($author);// 提交文件重复 
        $this->view->setVar('author_info'   , $author_info);
        $this->view->setVar('main_count'    , $main_count);
        $this->view->setVar('count_content' , $count_content);
        $this->view->setVar('count_commit'  , $count_commit);
        $this->view->setVar('count_file'    , $count_file);
    }


    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:43
    //
    public function totalAction(){
        $begin_date = $this->request->get('begin_date');
        $end_date = $this->request->get('end_date');
        list($time,$err) = \TimeService::NewTimeRange($begin_date,$end_date);
        if($err !== NULL){
            //echo $err;
        }

        $apps           = \StatService::GetProject();//取所有项目
        $authors        = \StatService::GetAuthor($time);//取所有用户
        $top_commit     = \Author\TotalService::TopCommit();//作者 － 提交行数最多
        $top_main_count = \Author\TotalService::TopMainCount($authors);//作者 - 核心开发数最多
        $top_project    = \Author\TotalService::TopProject();//作者 - 参与项目最多
        //p1($top_commit );
        //p1($top_project);
        //p1($top_main_count);
        $this->view->setVar('top_commit'     , $top_commit);
        $this->view->setVar('top_project'    , $top_project);
        $this->view->setVar('top_main_count' , $top_main_count);
    }
}

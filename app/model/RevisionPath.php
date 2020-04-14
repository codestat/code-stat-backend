<?php

//
// 版本号具体的提交路径
// @date 2017-12-26
//
class RevisionPath extends \BaseModel{

    public function initialize(){
        parent::initialize();
        $this->skipAttributes(array('modify'));
    }

    public function getSource() {
        return "revision_path";
    }

    public static function GetFirstCmd($r_id){
        $sql = "
select * from revision_path where r_id=?

";
        $res = RevisionPath::Fetch($sql,array(
            $r_id
        ));
        if(!empty($res) && isset($res['cmd'])){
            return $res['cmd'];
        }
        return null;
    }

    //
    // 主键ID
    // @length 11
    // @type   uint
    //
    public $pkid = 0;

    //
    // 版本号ID
    // @length 11
    // @type   uint64
    //
    public $r_id = 0;

    //
    // 修改时间
    // @length 11
    // @type   time
    //
    public $modify = '';

    //
    // 路径的类型
    // @length 16
    // @type   string
    //
    public $kind = '';

    //
    // 提交的类型
    // @length 16
    // @type   string
    //
    public $action = '';

    //
    // 路径
    // @length 250
    // @type   string
    //
    public $path = '';

    //
    // 提交svn的作者
    // @length 128
    // @type   string
    //
    public $author = '';

    //
    // 项目名称
    // @length 128
    // @type   string
    //
    public $project_name = '';

    //
    // 版本提交时间
    // @length 11
    // @type   time_unix
    //
    public $r_time = 0;

    //
    // 增加行数
    // @length 11
    // @type   uint
    //
    public $a_line = 0;

    //
    // 减少行数
    // @length 11
    // @type   uint
    //
    public $d_line = 0;

    //
    // 执行的diff命令
    // @length 512
    // @type   string
    //
    public $cmd = '';

    //
    // 状态
    // @length 11
    // @type   int
    //
    public $status = 0;



    public function ToArr(){

        $revision_path = array(
            'pkid' => $this->pkid,
            'r_id' => $this->r_id,
            'modify' => $this->modify,
            'kind' => $this->kind,
            'action' => $this->action,
            'path' => $this->path,
            'author' => $this->author,
            'project_name' => $this->project_name,
            'r_time' => $this->r_time,
            'a_line' => $this->a_line,
            'd_line' => $this->d_line,
            'cmd' => $this->cmd,
            'status' => $this->status,
            
        );
        return $revision_path;
    }

    public static function ToObj($array){

        $revision_path = new \EntityRevisionPath();
        $revision_path->pkid = isset($array['pkid']) ? $array['pkid'] : 0 ;
        $revision_path->r_id = isset($array['r_id']) ? $array['r_id'] : 0 ;
        $revision_path->modify = isset($array['modify']) ? $array['modify'] : '' ;
        $revision_path->kind = isset($array['kind']) ? $array['kind'] : '' ;
        $revision_path->action = isset($array['action']) ? $array['action'] : '' ;
        $revision_path->path = isset($array['path']) ? $array['path'] : '' ;
        $revision_path->author = isset($array['author']) ? $array['author'] : '' ;
        $revision_path->project_name = isset($array['project_name']) ? $array['project_name'] : '' ;
        $revision_path->r_time = isset($array['r_time']) ? $array['r_time'] : 0 ;
        $revision_path->a_line = isset($array['a_line']) ? $array['a_line'] : 0 ;
        $revision_path->d_line = isset($array['d_line']) ? $array['d_line'] : 0 ;
        $revision_path->cmd = isset($array['cmd']) ? $array['cmd'] : '' ;
        $revision_path->status = isset($array['status']) ? $array['status'] : 0 ;

        return $revision_path;
    }

}

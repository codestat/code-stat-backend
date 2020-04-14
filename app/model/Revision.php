<?php

//
// 版本号
// @date 2017-12-26
//
class Revision extends \BaseModel{

    public function initialize(){
        //$this->setConnectionService( 'db' );
        //$this->setReadConnectionService( 'db' );
        //$this->setWriteConnectionService( 'db' );
        parent::initialize();
        $this->skipAttributes(array('modify'));
    }

    public function getSource() {
        return "revision";
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
    // 项目名称
    // @length 128
    // @type   string
    //
    public $project_name = '';

    //
    // 提交svn的作者
    // @length 128
    // @type   string
    //
    public $author = '';

    //
    // 版本提交时间
    // @length 11
    // @type   time_unix
    //
    public $r_time = 0;

    //
    // 修改时间
    // @length 11
    // @type   time
    //
    public $modify = '';



    public function ToArr(){

        $revision = array(
            'pkid' => $this->pkid,
            'r_id' => $this->r_id,
            'project_name' => $this->project_name,
            'author' => $this->author,
            'r_time' => $this->r_time,
            'modify' => $this->modify,
            
        );
        return $revision;
    }

    public static function ToObj($array){

        $revision = new \EntityRevision();
        $revision->pkid = isset($array['pkid']) ? $array['pkid'] : 0 ;
        $revision->r_id = isset($array['r_id']) ? $array['r_id'] : 0 ;
        $revision->project_name = isset($array['project_name']) ? $array['project_name'] : '' ;
        $revision->author = isset($array['author']) ? $array['author'] : '' ;
        $revision->r_time = isset($array['r_time']) ? $array['r_time'] : 0 ;
        $revision->modify = isset($array['modify']) ? $array['modify'] : '' ;

        return $revision;
    }

    public static function GetTotal(&$entity_time,$filter_name = 'off',$w = ''){
        $res = array(
            'author_list' => array(),
            'project_list' => array(),
            'project_author_list' => array(),
            'authors' => array(),
            'projects' => array(),
        );
        $where = "1=1";
        if($filter_name !== 'off'){
            $where .= ' and status>=0';
        }
        if(!empty($w)){
            $where .= " {$w}";
        }
        $sql = "select author,count(r_id) as r_id,sum(a_line) a_line,sum(d_line) d_line,sum(a_line)+sum(d_line) as line  from revision_path where {$where} and r_time between {$entity_time->begin} and {$entity_time->end} group by author order by line desc";
        $info = \RevisionPath::FetchAll($sql);
        foreach($info as $d){
            $res['authors'][] = $d['author'];
            $res['author_list'][] = $d;
            //$res['author_list'][] = array(
            //    'author' => $d['author'],
            //    'r_id' => $d['r_id'],
            //    'a_line' => $d['a_line'],
            //    'd_line' => $d['d_line'],
            //);
        }
        $sql = "select project_name,sum(a_line) a_line,count(r_id) as r_id,sum(d_line) d_line,sum(a_line)+sum(d_line) as line  from revision_path where {$where} and r_time between {$entity_time->begin} and {$entity_time->end} group by project_name order by line desc";
        $info = \RevisionPath::FetchAll($sql);
        foreach($info as $d){
            $res['projects'][] = $d['project_name'];
            $res['project_list'][] = $d;
        }
        $sql = "select author,project_name,sum(a_line) a_line,count(r_id) as r_id,sum(d_line) d_line,sum(a_line)+sum(d_line) as line  from revision_path where {$where} and r_time between {$entity_time->begin} and {$entity_time->end} group by project_name,author order by project_name asc";
        $info = \RevisionPath::FetchAll($sql);

        //$res['project_author_list'] = $info;
        foreach($info as $d){
            $res['project_author_list'][$d['project_name']][] = $d;
        }

        return $res;


    }
    public static function TopCommit(){

        $sql = "
select count(DISTINCT r_id) as r_id,sum(a_line) a_line,sum(d_line) d_line,sum(a_line)+sum(d_line)as line,project_name,author  from revision_path 
where 1=1 and status>=0  group by author order by line desc
            ";
        $res = \Revision::FetchAll($sql);
        return $res;

    }

    public static function GetProject(){
        $sql = "
select count(DISTINCT project_name) as cp,project_name,author  from revision
where 1=1   group by author order by cp desc
            ";
        $res = \Revision::FetchAll($sql);
        return $res;

    }

    public static function GetMain($authors){
        $res = array();
        foreach($authors as $author){
            $main_count = \Author\StrategyService::GetMainProject($author);
            $res[] = array(
                'count' => $main_count,
                'author' => $author,
            );
        }
        $res = self::array_sort($res, 'count', 'desc');
        return $res;
    }




}

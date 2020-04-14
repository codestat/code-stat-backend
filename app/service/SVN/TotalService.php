<?php
// codestat项目
//
// codestat项目
// @Description: 
// @Version: V1.0.1
// @Author: simonsun
// @Mail: 4664919@qq.com
// @Last Modified: 2017-12-27 19:38
// @Filename: index.phtml
//
//

namespace SVN;

class TotalService {

    static $suffix = array(
        'back',
        'bak',
        'beam',
        'bowerrc',
        'buildpath',
        'c',
        'cc',
        'cer',
        'cfg',
        'coffee',
        'crt',
        'css',
        'csv',
        'cur',
        'd',
        'dat',
        'db',
        'deflate',
        'del',
        'dist',
        'dntrc',
        'doc',
        'docx',
        'editorconfig',
        'eot',
        'eps',
        'erl',
        'err',
        'exe',
        'fdf',
        'ghtml',
        'GIF',
        'git',
        'gitattributes',
        'gitignore',
        'gitkeep',
        'gitmodules',
        'gnumeric',
        'go',
        'gyp',
        'gypi',
        'gzip',
        'h',
        'htaccess',
        'htm',
        'html',
        'icc',
        'ico',
        'idx',
        'ignore',
        'iml',
        'in',
        'inc',
        'info',
        'ini',
        'jamignore',
        'jpeg',
        'jpg',
        'js',
        'jshintrc',
        'json',
        'less',
        'lock',
        'log',
        'Makefile',
        'map',
        'markdown',
        'md',
        'mdown',
        'mk',
        'node',
        'npmignore',
        'ods',
        'otf',
        'out',
        'pack',
        'pdf',
        'peg',
        'pem',
        'pfx',
        'phar',
        'php',
        'phpt',
        'phtml',
        'pl',
        'png',
        'prefs',
        'project',
        'properties',
        'proto',
        'psd',
        'py',
        'rp',
        'rs',
        'rst',
        's',
        'sample',
        'scss',
        'sh',
        'slk',
        'spmignore',
        'sql',
        'svg',
        'swf',
        'swo',
        'swp',
        'toml',
        'tpl',
        'tree',
        'tsv',
        'ttf',
        'twig',
        'txt',
        'volt',
        'watchr',
        'woff',
        'wsdl',
        'xls',
        'xlsx',
        'xml',
        'xsd',
        'xsl',
        'yaml',
        'yml',
        'z',
        'zip',

    );

    
    //
    //    
    // 过滤后缀
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:41
    //
    public static function FilterSuffix( array $suffix){
        $res = [];
        foreach($suffix as &$s){

    //
    // 关掉白名单
    // @Author: simonsun
    // @Last Modified: 2017-12-01 17:03
    //
            if(preg_match("/^([0-9a-zA-Z]+)$/",$s)){
                    $res[] = $s;

            }
            //foreach(self::$suffix as &$suf){
            //    if($s == $suf){
            //        $res[] = $suf;
            //    }
            //}

        }
        return $res;

    }

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:41
    //
    private static function increase_array(&$arr,$element,$data = array()){
        if(! isset($arr[$element])){
            $arr[$element] = $data;
        }
    }

    
    //
    //    
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-27 19:41
    //
    public static function CreateLineChartTotal(&$entity_time,$filter_name = 'off',$w = ''){
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
            //$res['project_list'][] = array(
            //    'project_name' => $d['project_name'],
            //    'a_line' => $d['a_line'],
            //    'r_id' => $d['r_id'],
            //    'd_line' => $d['d_line'],
            //);
        }
        $sql = "select author,project_name,sum(a_line) a_line,count(r_id) as r_id,sum(d_line) d_line,sum(a_line)+sum(d_line) as line  from revision_path where {$where} and r_time between {$entity_time->begin} and {$entity_time->end} group by project_name,author order by project_name asc";
        $info = \RevisionPath::FetchAll($sql);

        //$res['project_author_list'] = $info;
        foreach($info as $d){
            $res['project_author_list'][$d['project_name']][] = $d;
        }

        return $res;


    }

//
//    
// @Description: 
// @Author: simonsun
// @Mail: 4664919@qq.com
// @Last Modified: 2017-12-27 19:41
//
//    public static function IncreaseDataContent(&$date_list){
//        //$res = array(
//        //    'range_chart_time' => array(),
//        //    'project_list' => array(),
//        //);
//        foreach($date_list as $data_key => &$data){
//            $sql = "select author,project_name,sum(a_line) a_line,sum(d_line) d_line from revision_path where r_time between {$data->begin} and {$data->end} group by author,project_name";
//            $info = \RevisionPath::FetchAll($sql);
//            $data->data = $info;
//            foreach($info as $d){
//                //$res['range_chart_time'][] = $data_key;
//                //self::increase_array($res['project_list'],$d['project_name'],array(
//                //    'project_name' => $d['project_name'],
//                //    'author_list' => array(),
//                //    'author_val' => array(),
//                //));
//                //self::increase_array($res['project_list'][$d['project_name']]['author_list'],$d['author'],$d['author']);
//                //self::increase_array($res['project_list'][$d['project_name']]['author_val'],$d['author'],$d['a_line']);
//                //self::increase_array($res['project_list'][$d['project_name']],111);
//                //self::increase_array($res['project_list'][$d['project_name']],$d['author'],array(
//                //    'author_list' => array(),
//                //    'author_val' => array(),
//                //));
//                //$res['project_list'][$d['project_name']] = array(
//                //    'author_list' => array(),
//                //    'author_val' => array(),
//                //);
//                //$res[]
//
//            }
//            //foreach($info as $d){
//            //    if( !isset($data->project)){
//            //        $data->project = new \StdClass();
//            //    }
//            //    if( !isset($data->project->$d['project_name'])){
//            //        $data->project->$d['project_name'] = new \StdClass();
//            //    }
//            //    $data->project->$d['project_name']->$d['author'] = array(
//            //    //$data['project'][$d['project_name']][$d['author']] = array(
//            //        'a_line' => $d['a_line'],
//            //        'd_line' => $d['d_line'],
//            //    );
//            //}
//            //----copy from index controller
//            //$project_list = array();
//            //$project_total = array();
//            //$range_chart = array();
//            //foreach($data as $k => $d){
//            //    $range_chart[] = $k;
//            //    foreach($d->project $p_name as $p){
//
//            //        $project_list[$p_name] = array(
//            //             
//            //        );
//            //        foreach($author $a_name as $a){
//            //            if(!isset($project_list[$p_name][$a_name])){
//            //                $project_list[$p_name][$a_name] = array(
//            //                    'a_line' => 0,
//            //                    'd_line' => 0,
//            //                );
//            //            }
//            //        }
//
//            //    }
//            //}
//        }
//        //p($res);
//    }


}

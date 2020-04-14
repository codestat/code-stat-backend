<?php

namespace SVN;

class TimeRangeService {

    // 到这里的data_dimension一定是处理好的了
    // time 默认是14年到现在
    public static function Parse($entity_time,$data_dimension){
        p1("entity_time:",$entity_time);
        $time_list = array();
        list($t,$err) = \TimeService::NewTime($entity_time->begin_date,$data_dimension);
        $time_list["{$t->begin_date}-{$t->end_date}"] = $t;
        for ($i=0;$i < 365;$i++){
            //p1("第",$i,"次循环");
            //switch($data_dimension){
            //case "year":

            //    break;
            //case "month" :
            //    list($t,$err ) = \TimeService::NewTime($time->begin_date,'month');
            //    break;
            //case "week" :
            //    list($t,$err ) = \TimeService::NewTime($time->begin_date,'week');
            //    break;
            //case "day" :
            //    list($t,$err ) = \TimeService::NewTime($time->begin_date,'day');
            //    break;
            //}
            list($t,$err ) = \TimeService::NewTime(date("Y-m-d",$t->end+3600),$data_dimension);
            $time_list["{$t->begin_date}-{$t->end_date}"] = $t;
            if($t->end > $entity_time->end){
                //p1("$t->end > $entity_time->end break,[{$t->end_date} > {$entity_time->end_date}]");
                break;
            }
        }
        return $time_list;
    }

    private static function increase_array(&$arr,$element,$data = array()){
        if(! isset($arr[$element])){
            $arr[$element] = $data;
        }
    }

    public static function CreateLineChartTotal(&$entity_time){
        $res = array(
            'author_list' => array(),
            'project_list' => array(),
            'project_author_list' => array(),
            'authors' => array(),
            'projects' => array(),
        );
        $sql = "select author,count(r_id) as r_id,sum(a_line) a_line,sum(d_line) d_line,sum(a_line)+sum(d_line) as line  from revision_path where status>=0 and r_time between {$entity_time->begin} and {$entity_time->end} group by author order by line desc";
        $info = \RevisionPath::FetchAll($sql);
        foreach($info as $d){
            $res['authors'][] = $d['author'];
            $res['author_list'] = $info;
            //$res['author_list'][] = array(
            //    'author' => $d['author'],
            //    'r_id' => $d['r_id'],
            //    'a_line' => $d['a_line'],
            //    'd_line' => $d['d_line'],
            //);
        }
        $sql = "select project_name,sum(a_line) a_line,count(r_id) as r_id,sum(d_line) d_line,sum(a_line)+sum(d_line) as line  from revision_path where status>=0 and r_time between {$entity_time->begin} and {$entity_time->end} group by project_name order by line desc";
        $info = \RevisionPath::FetchAll($sql);
        foreach($info as $d){
            $res['projects'][] = $d['project_name'];
            $res['project_list'] = $info;
            //$res['project_list'][] = array(
            //    'project_name' => $d['project_name'],
            //    'a_line' => $d['a_line'],
            //    'r_id' => $d['r_id'],
            //    'd_line' => $d['d_line'],
            //);
        }
        $sql = "select author,project_name,sum(a_line) a_line,count(r_id) as r_id,sum(d_line) d_line,sum(a_line)+sum(d_line) as line  from revision_path where status>=0 and r_time between {$entity_time->begin} and {$entity_time->end} group by project_name,author order by line desc";
        $info = \RevisionPath::FetchAll($sql);
        $res['project_author_list'] = $info;
        return $res;


    }
    public static function CreateLineChart(&$date_list,&$total){
        //$total = array(
        //    'range_chart_time' => array(),
        //);
        $total['project_chart'] = array();
        foreach($date_list as $data_key => &$data){
            $sql = "select project_name,sum(a_line) a_line,sum(d_line) d_line from revision_path where status>=0 and r_time between {$data->begin} and {$data->end} group by author,project_name";
            $info = \RevisionPath::FetchAll($sql);
            //$res->data = $info;
        }

    }
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

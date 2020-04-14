<?php

namespace Author;

class TotalService{


    //作者 － 提交行数最多
    public static function TopCommit(){

        $sql = "
select count(DISTINCT r_id) as r_id,sum(a_line) a_line,sum(d_line) d_line,sum(a_line)+sum(d_line)as line,project_name,author  from revision_path 
where 1=1 and status>=0  group by author order by line desc
            ";
        $res = \Revision::FetchAll($sql);
        return $res;

    }

    //作者 - 参与项目最多
    public static function TopProject(){
        $sql = "
select count(DISTINCT project_name) as cp,project_name,author  from revision
where 1=1   group by author order by cp desc
            ";
        $res = \Revision::FetchAll($sql);
        return $res;

    }

    //作者 - 核心开发数最多
    public static function TopMainCount($authors){
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




    public static function array_sort($array, $on, $order='asc'){
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
            case 'asc':
                asort($sortable_array);
                break;
            case 'desc':
                arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                array_push($new_array, $array[$k]);
            }
        }

        return $new_array;
    }


}

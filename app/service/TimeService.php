<?php
/**
 *
 * codestat项目 
 * @author simonsun
 * @email 4664919@qq.com
 */

/**
 * 转化时间的类
 */
class TimeService{

    const TIME_WEEK = 'week';
    const TIME_YEAR= 'year';
    const TIME_MONTH= 'month';
    const TIME_DAY = 'day';
    const TIME_WEEK_ID = 1;
    const TIME_MONTH_ID = 2;
    const TIME_DAY_ID = 3;
    const TIME_YEAR_ID = 4;
    public $begin_date;
    public $end_date;
    public $begin;
    public $end;
    public $type;
    public $type_id;
    private function __construct(){

    }
    public static function NewTimeFromParams($params){
        $type = self::TIME_WEEK;
        $date = date("Y-m-d",time() - 7*86400);
        if(is_array($params) && isset($params[0])){
            $type = self::GetType($params[0]);
        }
        if(is_array($params) && isset($params[1])){
            $date = $params[1];
        }
        return self::NewTime($date,$type);
    }


    public static function NewTimeRange($begin_date,$end_date){
        $err = NULL;
        $t = new TimeService();
        $t->begin=  strtotime($begin_date);
        $t->end =  strtotime($end_date);
        if ($t->begin == 0 || $t->end == 0){
            $err = "convert {$begin_date},{$end_date} to time faild";
            return array(NULL,$err);
        }
        $t->end += 86400-1;
        $t->begin_date = date("Y-m-d",$t->begin);
        $t->end_date=  date("Y-m-d",$t->end);
        return array($t,$err);
    }
    public static function NewTime($date,$type){
        $err = NULL;
        $time = strtotime($date);
        if ($time == 0 ){
            $err = "convert {$date} to time faild";
            return array(NULL,$err);
        }
        $t = new TimeService();
        $t->type = self::GetType($type);
        $t->type_id = self::GetTypeId($t->type);
        if ($t->type == self::TIME_WEEK){
            $now_week = date('w',$time);
            $tmp_time = $time;
            $day = 86400;
            //echo "now week:";
            //var_dump($now_week);
            switch($now_week){
                case 0:
                    $tmp_time -= $day * 6;
                    break;
                case 1:
                    break;
                case 2:
                    $tmp_time -= $day;
                    break;
                case 3:
                    $tmp_time -= $day * 2;
                    break;
                case 4:
                    $tmp_time -= $day * 3;
                    break;
                case 5:
                    $tmp_time -= $day * 4;
                    break;
                case 6:
                    $tmp_time -= $day * 5;
                    break;
            }
            $date = date("Y-m-d",$tmp_time);
        }elseif($t->type == self::TIME_DAY){
            $date = date("Y-m-d H:i:s",$time);
        }elseif($t->type == self::TIME_YEAR){
            $date = (date('Y',$time) . "-01-01");
        }else{
            $date = (date('Y-m-',$time) . "01");
        }
        $t->begin=  strtotime($date);
        if ($t->type == self::TIME_WEEK){
            $t->end = $t->begin + 86400*7-1;
        }elseif($t->type == self::TIME_DAY){
            $t->end = $t->begin + 86400-1;
        }elseif($t->type == self::TIME_YEAR){
            //取年的结束，由于年是不定长（365-366天）
            $leap_year = intval(date('L',$t->begin));
            $t->end= $t->begin + 86400 * (365 + $leap_year) -1;
        }else{
            //取月的结束，由于月是不定长（28-31天）
            $days = intval(date('t',$t->begin));
            $t->end= $t->begin + 86400*$days-1;
        }
        $t->begin_date =date("Y-m-d",$t->begin);
        $t->end_date= date("Y-m-d",$t->end);
        return array($t,$err);
    }
    public static function GetTypeId($type){
        if ($type === self::TIME_WEEK){
            return self::TIME_WEEK_ID;
        }
        if ($type === self::TIME_DAY){
            return self::TIME_DAY_ID;
        }
        if ($type === self::TIME_YEAR){
            return self::TIME_YEAR_ID;
        }
        return self::TIME_MONTH_ID;
    }
    public static function GetType($range_type){
        if ($range_type === self::TIME_WEEK){
            return self::TIME_WEEK;
        }
        if ($range_type === self::TIME_DAY){
            return self::TIME_DAY;
        }
        if ($range_type === self::TIME_YEAR){
            return self::TIME_YEAR;
        }
        return self::TIME_MONTH;
    }
}


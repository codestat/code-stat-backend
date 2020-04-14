<?php
header("Content-Type: text/html; charset=UTF-8");
date_default_timezone_set("PRC");
if(isset($_GET['simon_debug']) && $_GET['simon_debug'] == 65535 || isset($_GET['s']) ){
    define("IS_DEBUG",true);
    error_reporting(-1);
    ini_set('display_errors','On');
}else{
    define("IS_DEBUG",false);
}

try {
    $ini= "";
    define('ROOT_PATH', realpath(__DIR__ . '/../'));
    define('APP_PATH', ROOT_PATH . '/app/');

    $config = include APP_PATH . "/config/config.php";
    include APP_PATH . "/config/services.php";

    define('DOMAIN', $config->system->domain);
    define('DOMAIN_STATIC', $config->system->domain_static);

    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();
} catch (\Exception $e) {

    // 这里先不处理
    if (IS_DEBUG == false && false){
        header("Location: /notice.html");
        $route = \Phalcon\DI::getDefault()->get("router");
        $log = \Phalcon\DI::getDefault()->get("config")->system->file_log_path;
        $controller = strtolower($route->getControllerName());
        $action = strtolower($route->getActionName());
        mkdir("{$log}/500/");
        $session = \Phalcon\DI::getDefault()->get("session");
        $u = $session->get('user');
        $req = $_REQUEST;
        unset($req["_url"]);
        $req["gold_user"] = isset($u['unique_id']) ? $u['unique_id'] : "nobody";
        $req["gold_token"] = "simon_debug_ece3db145bc2804d776d3bbf8c21fae13382306c23596b9010e01c135a743dcdb";
        \SDK\Package\Log::debug("curl  ".DOMAIN."/{$controller}/{$action}?".http_build_query($req));
        $msg = ("curl  ".DOMAIN."/{$controller}/{$action}/?".http_build_query($req));
        file_put_contents("{$log}/500/{$controller}-".date('Ymd').'.log', "-------------------\n".date('Y-m-d H:i:s')." :{$msg} \n\n".$e->getMessage() . "\n".print_r($e,1)."\n\n\n\n", FILE_APPEND);
        exit;
    }else{
        echo "<hr>\n\n";
	print_r($e);
        echo $e->getMessage();
        echo "<hr>\n\n<pre>";
        //print_r($e);
        $i = 50;
        foreach($e as $key => $_e){
            $i--;
            print_r($key);
            print_r($_e);
            if ($i<0){
                break;
            }
        }
        echo "</pre>";
    }
}

function pp($res){
    echo "<pre>";
    $num = func_num_args();
    if($num > 1){
        echo "共DEBUG {$num} 个参数，分别为\n";
        $res = func_get_args();
    }
    print_r($res);
    exit;
}

function p($res){
    if (IS_DEBUG == true){
        echo "<pre>";
        $num = func_num_args();
        if($num > 1){
            echo "共DEBUG {$num} 个参数，分别为\n";
            $res = func_get_args();
        }
        print_r($res);
        exit;
    }
}
function p1($res){
    if (IS_DEBUG == true){
        echo "<pre>";
        $num = func_num_args();
        if($num > 1){
            echo "共DEBUG {$num} 个参数，分别为\n";
            $res = func_get_args();
        }
        print_r($res);
    }
}

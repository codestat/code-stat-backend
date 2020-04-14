<?php

$di = new Phalcon\DI\FactoryDefault();
$di->set('config', $config);
$di->set('profiler', function() {
        return new \Phalcon\Db\Profiler();
        }, true);

$di->set('db', function() use ($config, $di) {
        $connection = new Phalcon\Db\Adapter\Pdo\Mysql(array(
                'host' => $config->database->server,
                'username' => $config->database->user,
                'password' => $config->database->passwd,
                'port' => $config->database->port,
                'dbname' => $config->database->dbname,
                'charset' => 'utf8',
                ));
        return $connection;
}, 1);

$di->set('view', function() {
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir(APP_PATH . 'view/');
        return $view;
});

$di->set('session', function () {
        $session = new \Phalcon\Session\Adapter\Files();
        $session->start();
        return $session;
}, true);
$di->set('cookie', function() {
        $cookies = new Phalcon\Http\Response\Cookies();
        $cookies->useEncryption(true);
        return $cookies;
}, true);
$di->set('router', function() use ($di) {
    $router = new Phalcon\Mvc\Router();
    $router->handle();
    return $router;
},true);


$di->setShared('dispatcher', function() {
    $eventsManager = new Phalcon\Events\Manager();
    $eventsManager->attach("dispatch", function($event, $dispatcher, $exception) {
        if ($event->getType() == 'beforeException') {
            switch ($exception->getCode()) {
            case Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            case Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                $dispatcher->forward(array(
                    'controller' => 'index',
                    'action' => 'notfound'
                ));
                return false;
            }
        }
    });
    $dispatcher = new Phalcon\Mvc\Dispatcher();
    $dispatcher->setEventsManager($eventsManager);
    return $dispatcher;
});


$di->setShared('crypt', function() use($config,$di) {
    $crypt = new \Phalcon\Crypt();
    $crypt->setKey($config->salt->key);
    return $crypt;
});

$di->setShared('redis', function() use ($config,$di) {
    $redis = new \Redis();
    $redis->connect($config->redis->host, $config->redis->port);
    return $redis;
});



$di->set('sdk', function() use ($config, $di) {
    //$config = new \Phalcon\Config\Adapter\Ini('/etc/codestat/config.ini');
    if( ! isset($config->system->sdk_path)){
        //return NULL;
        throw new \Exception("sdk path must be set");
    }
    $path = $config->system->sdk_path;
    if( ! file_exists($path)){ 
        throw new \Exception("sdk file can not be loaded");
        //return NULL;
    }
    include_once($path);
    return "\SDK\SDK";
},true);
$sdk = $di->get('sdk');

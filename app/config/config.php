<?php

//
// 关于配置，先读项目目录config下的ini
// ini不存在去读etc下的ini
// 都不存或不可读，在直接退出程序
//
$config_dev_path = ROOT_PATH."/app/config/svn_stat.ini";
$config_path = '/etc/codestat/svn_stat.ini';
$path = "";

$path = file_exists($config_dev_path) ? $config_dev_path : "";
if ($path === ""){
    $path = file_exists($config_path) ? $config_path : "";
}

if($path === ""){
    pp("配置文件不可读,{$config_dev_path},{$config_path}");
}

$config = new Phalcon\Config\Adapter\Ini($path);
$conf = array(
    'app' => array(
        'dir_controller' => APP_PATH . 'controller/',
        'dir_plugin' => APP_PATH . 'plugin/',
        'dir_view' => APP_PATH . 'view/',
        'dir_service' => APP_PATH. 'service/',
        'dir_model' => APP_PATH . 'model/',
    ),
);
$config->merge($conf);

//loader
$loader = new \Phalcon\Loader();
$loader->registerDirs(
        array(
            $config->app->dir_controller,
            $config->app->dir_plugin,
            $config->app->dir_model,
            $config->app->dir_service,
            )
        )->setExtensions(array('php', 'class.php'))->register();
$loader->registerNamespaces(
        array(
            )
        );
$loader->register();

return $config;

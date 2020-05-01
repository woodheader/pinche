<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2019/1/31
 * Time: 10:35
 */
error_reporting(E_ALL);
define('ROOT_PATH', __DIR__.'/../');
define('LOG_PATH', ROOT_PATH.'logs');
ini_set('display_errors', 0); //禁止把错误输出到页面
ini_set('log_errors', 1); //设置错误信息输出到文件
ini_set("error_log", LOG_PATH . '/msg.log'); //指定错误日志文件名，文件并不需要真实存在，只要路径正确即可

header("Server: NLSJ/1.0.0");
header("X-Powered-By: PLSJ/1.0.0");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

define('SERVER_HOST', 'https://uri.wiki/');
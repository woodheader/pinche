<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2019/1/31
 * Time: 10:35
 */
error_reporting(E_ALL);
define('ROOT_PATH', __DIR__.'/logs');
ini_set('display_errors', 0); //禁止把错误输出到页面
ini_set('log_errors', 1); //设置错误信息输出到文件
ini_set("error_log", ROOT_PATH . '/msg.log'); //指定错误日志文件名，文件并不需要真实存在，只要路径正确即可

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

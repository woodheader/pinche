<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/4/26
 * Time: 11:08
 */

header("Access-Control-Allow-Origin: *");
header("X-Powered-By: lsj/1.0.0");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");

$k = $_REQUEST['k'];
file_put_contents('./abc.txt', $k);
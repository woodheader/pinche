<?php

require_once('../log.php');
require_once('../tools.php');

$token = $_GET['token'];

$isWx = help::isFromWechat();
$userAgent = help::getUserAgent();
$clientIp = help::getip();

if (!$isWx && $token != 'lsj') {
	echo '<p>请使用微信访问。</p>';
	die;
}

file_put_contents(ROOT_PATH . '/visiter-miyun.log',
    date('Y-m-d H:i:s').
    '---'.$isWx ? '是' : '否'.
    '---'.$clientIp.
    '---'.$userAgent.
    "\r\n", FILE_APPEND);

die('<span style="font-size: 42px;">此版本已经关闭，请访问新版本地址：<a href="https://uri.wiki/pinche/v2/show.php?area=miyun">点击进入</a></span>');

//echo file_get_contents(ROOT_PATH.'/../data-miyun.html');

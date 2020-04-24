<?php

require_once('log.php');
require_once('tools.php');

$referer = help::getReferer();
$userAgent = help::getUserAgent();
$clientIp = help::getip();

file_put_contents(ROOT_PATH . '/data-miyun.log',
    date('Y-m-d H:i:s').
    '---'.$referer.
    '---'.$clientIp.
    '---'.$userAgent.
    "\r\n", FILE_APPEND);


$data = $_POST['data'];

if (empty($data) || strlen($data) < 50) {
    echo '服务端没有收到任何消息...';
    return;
}

$data = initHtml($data);

file_put_contents(ROOT_PATH . '/../data-miyun.html', $data);

echo '成功';

function initHtml($data) {
	return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>密云拼车</title>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="bookmark" href="/favicon.ico" />
    <link rel="apple-touch-icon-precomposed" sizes="64x64" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" href="../css/viewer.css">
    <script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/clipboard.js/2.0.6/clipboard.min.js"></script>
		<script src="../js/viewer.js"></script>
		<script src="../js/common.js"></script>
    <meta name="keywords" content="帮你拼车,拼车,pinche,找拼车,长途拼车">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <style>
    	.mybutton {
				width: 60px;
			  height: 20px;
			  text-align: center;
			  border: 2px solid;
			  border-radius: 5px;
			  border-color: #FF9800;
			  background-color: #FF9800;
			  font-size: 12px;
			  display: inline-block;
			}
			
			.mybutton a {
				text-decoration: none;
				color: #ffffff;
			}
			
			.mybutton-blue {
				width: 60px;
			  height: 20px;
			  text-align: center;
			  border: 2px solid;
			  border-radius: 5px;
			  border-color: #2196f3;
			  background-color: #2196f3;
			  font-size: 12px;
			  display: inline-block;
			}
			
			.mybutton-blue a {
				text-decoration: none;
				color: #ffffff;
			}
			
			.mynote {
				color:red;
				font-weight:bold;	
				font-size:14px;
			}
    </style>
</head>
<body>
<span style="color:gray;font-size:12px;">
为密云小伙伴们提供一个汇总微信群拼车消息地方。<br/>

在公众号里发布拼车信息，比在群里更方便，发布后的消息还可以在列表中查看，需要拼车的同学可以更直观的看到信息。<br/>

我是纯为大家方便做的这么一个东西，永远免费！！！<br/>

发布行程的方法：公众号内回复：我是车主<br/>

查看车主的方法：公众号内回复：我是乘客<br/>
</span>
<span class="mynote">
*注意事项：
车主每天最多可以发布3次行程，每次发布将会随机获得0.1元 ~ 0.5元的红包！
红包活动截止到2020年10月1日0点
</span><br/>
<hr />
<div style="width:100%;padding-bottom: 10px;">
<div style="text-align:left;width:50%;display:inline-block;"><span style="font-size:12px;">更新时间: '.date('Y-m-d H:i:s', strtotime('+8 hours')).'</span></div>
<div style="text-align:right;width:50%;display:inline-block;float:right;">
<span class="mybutton-blue"><a href="http://rrd.me/gCTsw">发布行程</a></span>&nbsp;&nbsp;
<span class="mybutton"><a href="https://uri.wiki/pinche/v1/index.php">河北拼车</a></span>
</div>
</div>
'.urldecode($data).'</body>
</html>';	
}
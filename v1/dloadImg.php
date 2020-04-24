<?php
require_once('log.php');
require_once('tools.php');

$mobile = $_POST['m'];
$imgSrc = empty($_POST['src']) ? '' : $_POST['src'];

if (empty($imgSrc)) {
	echo '图片地址不能为空';
	die;
}

if ($imgSrc == 'undefined') {
	die('https://uri.wiki/pinche/img/qrcode.png');
}

$target = './img/userqr/'.$mobile.'.png';

// 验证手机号对应的图片是否存在
if (file_exists($target)) {
	echo 'https://uri.wiki/pinche/img/userqr/'.$mobile.'.png';
	return;
}

$img = file_get_contents($imgSrc);
file_put_contents($target, $img);

echo 'https://uri.wiki/pinche/img/userqr/'.$mobile.'.png';
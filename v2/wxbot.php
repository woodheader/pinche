<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/4/17
 * Time: 16:03
 */
require_once('../core/init.config.php');
require_once(ROOT_PATH . 'model/MessageModel.php');
require_once('./const/const.php');

$robotid = $_REQUEST['robotid'];
$gid = $_REQUEST['gid'];
$skw = $_REQUEST['skw'];
$area = empty($_REQUEST['area']) ? 'miyun' : $_REQUEST['area'];

$isWx = help::isFromWechat();
$userAgent = help::getUserAgent();
$clientIp = help::getip();

file_put_contents(LOG_PATH . '/wxrobot.log',
    date('Y-m-d H:i:s').
    '---'.$isWx ? '是' : '否'.
        '---'.$robotid.'---'.$gid.'---'.$skw.
        '---'.$clientIp.
        '---'.$userAgent.
        "\r\n", FILE_APPEND);

$msgObj = new MessageModel();
$msgResult = $msgObj->getMessageList($areaTypeList[$area], date('Y-m-d H:i:s'), date('Y-m-d H:i:s', strtotime('-8 hours')), $channelMapping[$area]);

$robotMsg = [
    'rs' => 1,
    'content' => '',
    'sendtime' => strtotime(date('Y-m-d H:i:s', strtotime('-1 minute'))),
    'end' => 1
];

$contentList = [];
if (!empty($msgResult)) {
    foreach ($msgResult as $msgObj) {
        $goto = $gotoMapping[$msgObj['goto']];
        if (is_array($goto)) {
            $goto = $goto[$channelMapping[$area]];
        }
        $carPrice = $priceMapping[$msgObj['car_price']];
        $contentList[] = $msgTitle[0].' '.$msgObj['car_time'];
        $contentList[] = $msgTitle[1].' '.$goto;
        $contentList[] = $msgTitle[2].' '.$msgObj['car_line'];
        $contentList[] = $msgTitle[3].' '.$msgObj['car_seatnum'];
        $contentList[] = $msgTitle[4].' '.$carPrice;
        $contentList[] = $msgTitle[5].' '.$msgObj['car_tel'];
        $contentList[] = $msgTitle[6].' '.$msgObj['car_license_plate'];
        $contentList[] = '======================';
    }
    $contentList[] = '点击查看更多车主行程: '.($area=='hb' ? 'http://rrd.me/gDTF4' : 'http://rrd.me/gDTFw');
    if (!empty($contentList)) {
        $robotMsg['content'] = implode("\\n", $contentList);
        $robotMsg['sendtime'] = strtotime(date('Y-m-d H:i:s', strtotime('+20 seconds')));
    }
}
//echo json_encode($robotMsg);
echo '{"rs": 1,"content":"'.$robotMsg['content'].'","sendtime":"'.$robotMsg['sendtime'].'", "end":1}';

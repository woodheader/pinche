<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/4/15
 * Time: 15:56
 */
require_once('../core/init.config.php');
require_once(ROOT_PATH . 'core/help.class.php');
require_once(ROOT_PATH.'model/MessageModel.php');
require_once('./const/const.php');

$area = empty($_GET['area']) ? 'miyun' : $_GET['area'];
$token = $_GET['token'];

$isWx = help::isFromWechat();
$userAgent = help::getUserAgent();
$clientIp = help::getip();

if (!$isWx && $token != 'lsj') {
    echo '<p>请使用微信访问。</p>';
    die;
}

file_put_contents(LOG_PATH . '/visiter-'.$area.'-v2.log',
    date('Y-m-d H:i:s').
    '---'.$isWx ? '是' : '否'.
        '---'.$area.
        '---'.$clientIp.
        '---'.$userAgent.
        "\r\n", FILE_APPEND);



$messageList = (new MessageModel())->getMessageList($areaTypeList[$area], date('Y-m-d H:i:s'), '', $channelMapping[$area]);


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?=$areaArr[$area]?>拼车</title>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="bookmark" href="/favicon.ico" />
    <link rel="apple-touch-icon-precomposed" sizes="64x64" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" href="css/viewer.css">
    <link rel="stylesheet" href="css/jquery.ui.css">
    <script src="https://cdn.bootcss.com/jquery/3.5.0/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/clipboard.js/2.0.6/clipboard.min.js"></script>
    <script src="js/jquery.dialog.js"></script>
    <script src="js/viewer.js"></script>
    <script src="js/common.js"></script>
    <meta name="keywords" content="帮你拼车,<?=$areaArr[$area]?>拼车,pinche,找拼车,长途拼车">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>
<body>
    <span style="color:gray;font-size:12px;">
    为<?=$areaArr[$area]?>小伙伴们提供一个方便拼车的地方。<br/>

        在公众号里发布拼车信息，比在群里更方便，发布后的消息还可以在列表中查看，需要拼车的同学可以更直观的看到信息。<br/><b style="color: #FF9800;">长按识别公众号:</b> <img class="qrcode" src="./img/ddzt.png" width="34px" height="14px" alt="车主微信"><br/>

    纯为大家方便做的这么一个东西，别担心收费！<br/>
    <?php
    if ($area == 'miyun') {
        ?>
        发布行程的方法：公众号内回复：我是车主<br/>

        查看车主的方法：公众号内回复：我是乘客<br/>
        <?php
    }
    ?>

    <?php
    if ($area == 'hb') {
        ?>
        发布行程的方法：公众号内回复：车主（司机、车找人 也行）<br/>

        查看车主的方法：公众号内回复：乘客（拼车、找车、人找车 也行）<br/>
        <?php
    }
    ?>
    </span>
    <span class="mynote">
    *注意事项：
    车主每天最多可以发布3次行程，每次发布将会随机获得0.1元 ~ 0.5元的红包！
    红包活动截止到2020年10月1日0点
    </span><br/>
    <hr />
    <div style="width:100%;padding-bottom: 10px;">
        <div style="text-align:left;width:50%;display:inline-block;"><span style="font-size:12px;">更新时间: <?=date('Y-m-d H:i:s')?></span></div>
        <div style="text-align:right;width:50%;display:inline-block;float:right;">
            <span class="mybutton-blue"><a href="<?=$area=='hb' ? 'http://rrd.me/gEdXs' : 'http://rrd.me/gCTsw'?>">发布行程</a></span>&nbsp;&nbsp;
            <span class="mybutton"><a href="https://uri.wiki/pinche/v2/show.php?area=<?=$area=='hb' ? 'miyun' : 'hb'?>"><?=$area=='hb' ? '密云' : '河北'?>拼车</a></span>
        </div>
    </div>
    <table class="statistics-table">
        <thead><tr class="hd"><th class="seq">序号</th><th>车主行程<span style="font-size: 8px">（座位数可以点击修改）</span></th></tr></thead>
        <tbody>
    <?php
        $trHtml = '';
        foreach ($messageList as $msg) {
            $goto = $gotoMapping[$msg['goto']];
            if (is_array($goto)) {
                $goto = $goto[$channelMapping[$area]];
            }
            $color = $msg['goto'] == 1 ? 'green' : 'red';
            $carPrice = $priceMapping[$msg['car_price']];
            $trHtml .= '<tr><td>'.$msg['id'].'</td>';
            $trHtml .= '<td>';
            $trHtml .= '<div class="div-msg-left">日&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;期:</div><div class="div-msg-right">'.$msg['car_time'].'</div><br>';
            $trHtml .= '<div class="div-msg-left">方&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;向:</div><div class="div-msg-right"><span style="color:'.$color.';font-weight:bold;">'.$goto.'</span></div><br>';
            $trHtml .= '<div class="div-msg-left">行驶路线:</div><div class="div-msg-right">'.$msg['car_line'].'</div><br>';
            $trHtml .= '<div class="div-msg-left">座&nbsp;&nbsp;位&nbsp;&nbsp;数:</div><div class="div-msg-right allow-edit" contenteditable="true">'.$msg['car_seatnum'].'位</div><br>';
            $trHtml .= '<div class="div-msg-left">单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;价:</div><div class="div-msg-right">'.$carPrice.'</div><br>';
            $trHtml .= '<div class="div-msg-left">联系方式:</div><div class="div-msg-right"><a href="tel://'.$msg['car_tel'].'">'.help::replaceWithStar($msg['car_tel'],'****',3,4).'</a></div><br>';
            $trHtml .= empty($msg['car_wechat_img']) ? '' : '<div class="div-msg-left">车主微信:</div><div class="div-msg-right"><img class="qrcode" src="'.$msg['car_wechat_img'].'" width="24px" height="24px" alt="车主微信"></div><br>';
            $trHtml .= '<div class="div-msg-left">车牌信息:</div><div class="div-msg-right">'.$msg['car_license_plate'].'</div><br>';
            $trHtml .= '<button class="btnCopy" style="cursor:pointer;">复制</button><button class="btnCancel" style="cursor:pointer;">取消</button><button class="btnOrder" style="cursor:pointer;">预定</button>';
            $trHtml .= '</td></tr>';
        }
        echo $trHtml;
    ?>
        </tbody>
    </table>
    <div id="dialog-confirm-code" title="操作">
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
            输入验证码：<label for="code"></label><input type="text" id="code"/>
        </p>
    </div>
    <div id="dialog-confirm-msg" title="提示">
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
            <span id="message"></span>
        </p>
    </div>
</body>
</html>



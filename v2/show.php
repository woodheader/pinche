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
    纯为大家方便做的这么一个东西，不收费！<br/>
    <b style="color: #FF9800;">长按识别公众号:</b> <img class="qrcode" src="./img/ddzt.png" width="34px" height="14px" alt="车主微信"><br/>
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
    车主每天最多可以发布3次行程，每次发布将会随机获得最高0.5元的红包！<br/>
    乘客预订将百分百获得红包兑换码，可以在公众号内使用编码兑换！<br/>
    红包活动截止到2020年10月1日0点。
    </span><br/>
    <hr />
    <div style="width:100%;padding-bottom: 10px;">
        <div style="display:inline-block;"><span style="font-size:12px;">更新时间: <?=date('Y-m-d H:i:s')?></span></div>
        <div style="text-align:right;display:inline-block;float:right;">
            <span class="mybutton-blue"><a href="<?=$area=='hb' ? 'http://rrd.me/gEdXs' : 'http://rrd.me/gCTsw'?>">发布行程</a></span>&nbsp;&nbsp;
            <?php if ($area=='hb'): ?>
            <span class="mybutton"><a href="https://uri.wiki/pinche/v2/show.php?area=<?=$area=='hb' ? 'miyun' : 'hb'?>"><?=$area=='hb' ? '密云' : '河北'?>拼车</a></span>
            <?php endif; ?>
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
            $trHtml .= '<table class="inner-table" border="0" cellspacing="0" cellpadding="0">';
            $trHtml .= '<tr><td class="inner-td-first">日&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;期:</td><td class="inner-td-second">'.$msg['car_time'].'</td></tr>';
            $trHtml .= '<tr><td class="inner-td-first">方&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;向:</td><td class="inner-td-second"><span style="color:'.$color.';font-weight:bold;">'.$goto.'</span></td></tr>';
            $trHtml .= '<tr><td class="inner-td-first">行驶路线:</td><td class="inner-td-second">'.$msg['car_line'].'</td></tr>';
            $trHtml .= '<tr><td class="inner-td-first">座&nbsp;&nbsp;位&nbsp;数:</td><td class="inner-td-second"><div class="allow-edit" contenteditable="true">'.$msg['car_seatnum'].'位'.($msg['car_seatnum'] <= 0 ? '<b style="color: red;">(车满)</b>' : '').'</div></td></tr>';
            $trHtml .= '<tr><td class="inner-td-first">单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;价:</td><td class="inner-td-second">'.$carPrice.'</td></tr>';
            $trHtml .= '<tr><td class="inner-td-first">联系方式:</td><td class="inner-td-second"><a href="tel://'.$msg['car_tel'].'">'.help::replaceWithStar($msg['car_tel'],'****',3,4).'</a></td></tr>';
            $trHtml .= empty($msg['car_wechat_img']) ? '' : '<tr><td class="inner-td-first">车主微信:</td><td class="inner-td-second"><img class="qrcode" src="'.$msg['car_wechat_img'].'" width="24px" height="24px" alt="车主微信"></td></tr>';
            $trHtml .= '<tr><td class="inner-td-first">车牌信息:</td><td class="inner-td-second">'.$msg['car_license_plate'].'</td></tr>';
            $trHtml .= '<tr><td class="inner-td-colspan" colspan="2">
                                <button class="'.($msg['car_seatnum'] <= 0 ? 'btnOrderFull' : 'btnOrder').'" style="cursor:pointer;">预订</button>
                                <button class="btnCancel" style="cursor:pointer;">取消</button>
                                <button class="btnCopy" style="cursor:pointer;">复制</button>
                                </td></tr>';
            $trHtml .= '</table>';
            $trHtml .= '</td></tr>';
        }
        echo $trHtml;
    ?>
        </tbody>
    </table>
    <!--验证码输入弹框-->
    <div id="dialog-confirm-code" title="操作" style="display:none;>
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
            输入验证码：<label for="code"></label><input type="text" id="code"/>
        </p>
    </div>
    <!--提示消息弹框-->
    <div id="dialog-confirm-msg" title="提示" style="display:none;>
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
            <span id="message"></span>
        </p>
    </div>
    <!--预订弹框-->
    <div id="dialog-form-order" title="预订" style="display:none;">
        <p class="validateTips">填写预订信息</p>
        <form>
            <fieldset>
                <div>
                    <label for="orderMobile" class="form-label">手机号</label>
                    <input type="text" name="orderMobile" id="orderMobile" value="" class="text ui-widget-content ui-corner-all">
                    <button class="btnPassenger" style="cursor:pointer;font-size: 12px;">发送</button>
                    <span class="spanMsg">(验证码已发送到您手机)</span>
                </div>
                <label for="orderCode" class="form-label">验证码</label>
                <input type="text" name="orderCode" id="orderCode" value="" class="text ui-widget-content ui-corner-all">
                <label for="passengerNum" class="form-label">乘客数</label>
                <select name="passengerNum" id="passengerNum" class="ui-widget-content">
                    <option value="1">1位</option>
                    <option value="2">2位</option>
                    <option value="3">3位</option>
                    <option value="4">4位</option>
                    <option value="5">5位</option>
                    <option value="6">6位</option>
                </select>
                <label for="upCarAddr" class="form-label">上车地点</label>
                <input type="text" name="upCarAddr" id="upCarAddr" value="" class="text ui-widget-content ui-corner-all">
                <label for="downCarAddr" class="form-label">下车地点</label>
                <input type="text" name="downCarAddr" id="downCarAddr" value="" class="text ui-widget-content ui-corner-all">
                <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
            </fieldset>
        </form>
    </div>
</body>
</html>



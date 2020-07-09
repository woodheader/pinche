<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/4/25
 * Time: 12:37
 */
require_once('../core/init.config.php');
require_once(ROOT_PATH . 'core/common.php');
require_once(ROOT_PATH.'model/MessageModel.php');
require_once('./const/const.php');
require_once(ROOT_PATH . 'model/SmsModel.php');
require_once(ROOT_PATH . 'model/OrderModel.php');
require_once(ROOT_PATH . 'model/ShortUrlModel.php');
require_once(ROOT_PATH . 'model/CodelibModel.php');
require './SUBMAIL_PHP_SDK/app_config.php';
require_once('./SUBMAIL_PHP_SDK/SUBMAILAutoload.php');

$act = empty($_REQUEST['act']) ? 'default' : $_REQUEST['act'];
$id = empty($_REQUEST['id']) ? 0 : $_REQUEST['id'];
$mobile = empty($_REQUEST['mobile']) ? '' : $_REQUEST['mobile'];
$code = empty($_REQUEST['code']) ? '' : $_REQUEST['code'];
$passengerNum = empty($_REQUEST['passengerNum']) ? 1 : $_REQUEST['passengerNum'];
$upCarAddr = empty($_REQUEST['upCarAddr']) ? '' : trim($_REQUEST['upCarAddr']);
$downCarAddr = empty($_REQUEST['downCarAddr']) ? '' : trim($_REQUEST['downCarAddr']);
$driverMobile = empty($_REQUEST['driverMobile']) ? '' : trim($_REQUEST['driverMobile']);

if (empty($id)) {
    $returnArr = [
        'result' => 0,
        'msg' => '预订信息不合法！'
    ];
    die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
}
if (empty($mobile)) {
    $returnArr = [
        'result' => 0,
        'msg' => '手机号不能为空！'
    ];
    die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
}

switch ($act) {
    case 'default':
    default:
        $returnArr = [
            'result' => 0,
            'msg' => '无操作！'
        ];
        die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        break;
    case 'order':
        if (empty($code)) {
            $returnArr = [
                'result' => 0,
                'msg' => '验证码不能为空！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        if ($passengerNum <= 0 || $passengerNum > 6) {
            $returnArr = [
                'result' => 0,
                'msg' => '不合法的乘客数！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        if (empty($upCarAddr)) {
            $returnArr = [
                'result' => 0,
                'msg' => '上车地点不能为空！'
            ];
            die(json_encode($upCarAddr, JSON_UNESCAPED_UNICODE));
        }
        if (empty($downCarAddr)) {
            $returnArr = [
                'result' => 0,
                'msg' => '下车地点不能为空！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        $msgObj = new MessageModel();
        $msgResult = $msgObj->getMessageById($id);
        if (empty($msgResult)) {
            $returnArr = [
                'result' => 0,
                'msg' => '找不到行程信息！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        // 座位数是0的不允许预订
        if ($msgResult['car_seatnum'] <= 0) {
            $returnArr = [
                'result' => 0,
                'msg' => '车辆满员，预订失败！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        // 检查当前乘客是否属于重复预订
        $order = new OrderModel();
        $orderInfo = $order->getOrderInfo($id, $mobile);
        if (!empty($orderInfo)) {
            $returnArr = [
                'result' => 0,
                'msg' => '您已经预订过这辆车了！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        // 验证乘客数量和当前座位数
        if ($passengerNum > $msgResult['car_seatnum']) {
            $returnArr = [
                'result' => 0,
                'msg' => '车辆座位数不够，预订失败！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        // 检查验证码
        $sms = new SmsModel();
        $smsResult = $sms->getSmsByConditions($mobile, SmsModel::TYPE_PASSENGER);
        if (empty($smsResult) || $smsResult['code'] != $code) {
            $returnArr = [
                'result' => 0,
                'msg' => '错误的验证码！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        $formData = [
            'msg_id' => $id,
            'order_tel' => $mobile,
            'passenger_num' => $passengerNum,
            'up_address' => $upCarAddr,
            'down_address' => $downCarAddr,
            'status' => OrderModel::STATUS_VALID,
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s'),
        ];
        $order = new OrderModel();
        $order->load($formData);
        $order->save();
        $returnArr = [
            'result' => 1,
            'msg' => '预订成功！'
        ];
        // 司机座位数-1
        $msgObj->setCarSeatnum($msgResult['car_seatnum'] - 1);
        $msgObj->setUpdateTime(getLocalDateTime());
        $msgObj->addWhere(['id' => $id]);
        $msgObj->update();
        // 预订成功后给司机发送验证码
        $submail = new MESSAGEXsend($message_configs);
        $submail->SetTo($msgResult['car_tel']);
        $submail->SetProject('F5Cd1');
        $submail->AddVar('mobile', $mobile);
        $submail->AddVar('ordernum', $passengerNum);
        $submail->AddVar('upaddr', $upCarAddr);
        $submail->AddVar('downaddr', $downCarAddr);
        // 生成司机短信里的短链接，点击可以确认预订
        $short = new ShortUrlModel();
        $confirmLongUrl = SERVER_HOST.'pinche/v2/order.php?act=confirm&id='.$id.'&mobile='.$mobile.'&driverMobile='.$msgResult['car_tel'];
        $rejectLongUrl = SERVER_HOST.'pinche/v2/order.php?act=reject&id='.$id.'&mobile='.$mobile.'&driverMobile='.$msgResult['car_tel'];
        $submail->AddVar('link', SERVER_HOST.$short->generateUrl($confirmLongUrl));
        $submail->AddVar('rejectlink', SERVER_HOST.$short->generateUrl($rejectLongUrl));
        $send=$submail->xsend();
        $send['type'] = '乘客预订-给司机发短信';
        $send['driver'] = $msgResult['car_tel'];
        $send['passenger'] = $mobile;
        file_put_contents(LOG_PATH . '/sms.log', json_encode($send, JSON_UNESCAPED_UNICODE) . "\r\n", FILE_APPEND);
        if ($send['status'] != 'success') {
            $returnArr = [
                'result' => 0,
                'msg' => '发送司机预订信息失败！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        break;
    case 'confirm':
        $msg = '确认成功!';
        // 确认行程对应的类型，是密云的还是河北的
        $msgObj = new MessageModel();
        $msgResult = $msgObj->getMessageById($id);
        if (empty($msgResult)) {
            $msg = '没有找到对应行程!';
            echo '<script>alert("'.$msg.'");</script>';
            die;
        }
        $url = SERVER_HOST.'pinche/v2/show.php?area='.$channelMappingForKey[$msgResult['channel']];
        // 查询乘客预订的车主确认状态，若已经确认的，禁止再次确认
        $order = new OrderModel();
        $orderInfo = $order->getOrderInfo($id, $mobile);
        if (empty($orderInfo)) {
            $msg = '没有找到预订信息!';
            echo '<script>alert("'.$msg.'");window.location.href="'.$url.'";</script>';
            die;
        }
        if ($orderInfo['is_confirm'] == OrderModel::CONFIRM_YES) {
            $msg = '您已经确认过了!';
            echo '<script>alert("'.$msg.'");window.location.href="'.$url.'";</script>';
            die;
        }
        $order->setIsConfirm(OrderModel::CONFIRM_YES);
        $order->setUpdateTime(getLocalDateTime());
        $order->addWhere(['id' => $orderInfo['id']]);
        $order->update();

        // 司机确认后，短信通知给乘客
        $submail = new MESSAGEXsend($message_configs);
        $submail->SetTo($mobile);
        $submail->SetProject('3rQO51');
        $submail->AddVar('drivermobile', $driverMobile);
        // 红包编码查询
        $code = new CodelibModel();
        $codeInfo = $code->getRandomCode(CodelibModel::TYPE_PASSENGER);
        $submail->AddVar('code', $codeInfo['code']);
        $send=$submail->xsend();
        $send['type'] = '司机确认-给乘客发短信';
        $send['driver'] = $driverMobile;
        $send['passenger'] = $mobile;
        file_put_contents(LOG_PATH . '/sms.log', json_encode($send, JSON_UNESCAPED_UNICODE) . "\r\n", FILE_APPEND);
        if ($send['status'] != 'success') {
            $msg = '给乘客发送确认验证码异常,请联系管理员!';
        } else {
            // 修改红包编码使用状态
            $code->setIsUsed(CodelibModel::USE_YES);
            $code->setUpdateTime(getLocalDateTime());
            $code->addWhere(['id' => $codeInfo['id']]);
            $code->update();
        }
        echo '<script>alert("'.$msg.'");window.location.href="'.$url.'";</script>';
        break;
    case 'reject':
        $msg = '已成功拒绝!';
        // 确认行程对应的类型，是密云的还是河北的
        $msgObj = new MessageModel();
        $msgResult = $msgObj->getMessageById($id);
        if (empty($msgResult)) {
            $msg = '没有找到对应行程!';
            echo '<script>alert("'.$msg.'");</script>';
            die;
        }
        $url = SERVER_HOST.'pinche/v2/show.php?area='.$channelMappingForKey[$msgResult['channel']];
        // 查询乘客预订的车主确认状态，若已经确认的，禁止再次确认
        $order = new OrderModel();
        $orderInfo = $order->getOrderInfo($id, $mobile);
        if (empty($orderInfo)) {
            $msg = '没有找到预订信息!';
            echo '<script>alert("'.$msg.'");window.location.href="'.$url.'";</script>';
            die;
        }
        if ($orderInfo['is_confirm'] == OrderModel::CONFIRM_REJECT) {
            $msg = '您已经拒绝过了!';
            echo '<script>alert("'.$msg.'");window.location.href="'.$url.'";</script>';
            die;
        }
        // 已经确认过的预订不能再次拒绝
        if ($orderInfo['is_confirm'] == OrderModel::CONFIRM_YES) {
            $msg = '您已经确认了，不能再拒绝!';
            echo '<script>alert("'.$msg.'");window.location.href="'.$url.'";</script>';
            die;
        }
        $order->setIsConfirm(OrderModel::CONFIRM_REJECT);
        $order->setUpdateTime(getLocalDateTime());
        $order->addWhere(['id' => $orderInfo['id']]);
        $order->update();

        // 司机确认后，短信通知给乘客
        $submail = new MESSAGEXsend($message_configs);
        $submail->SetTo($mobile);
        $submail->SetProject('oIgyi1');
        $submail->AddVar('drivermobile', $driverMobile);
        $send=$submail->xsend();
        $send['type'] = '司机拒绝-给乘客发短信';
        $send['driver'] = $driverMobile;
        $send['passenger'] = $mobile;
        file_put_contents(LOG_PATH . '/sms.log', json_encode($send, JSON_UNESCAPED_UNICODE) . "\r\n", FILE_APPEND);
        if ($send['status'] != 'success') {
            $msg = '给乘客发送确认验证码异常,请联系管理员!';
        }
        echo '<script>alert("'.$msg.'");window.location.href="'.$url.'";</script>';
        break;
}
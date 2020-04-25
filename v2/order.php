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
require './SUBMAIL_PHP_SDK/app_config.php';
require_once('./SUBMAIL_PHP_SDK/SUBMAILAutoload.php');

$act = empty($_REQUEST['act']) ? 'default' : $_REQUEST['act'];
$id = empty($_REQUEST['id']) ? 0 : $_REQUEST['id'];
$mobile = empty($_REQUEST['mobile']) ? '' : $_REQUEST['mobile'];
$code = empty($_REQUEST['code']) ? '' : $_REQUEST['code'];

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
if (empty($code)) {
    $returnArr = [
        'result' => 0,
        'msg' => '验证码不能为空！'
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
        $msgObj->setUpdateTime(date('Y-m-d H:i:s'));
        $msgObj->addWhere(['id' => $id]);
        $msgObj->update();
        // 预订成功后给司机发送验证码
        $submail = new MESSAGEXsend($message_configs);
        $submail->SetTo($msgResult['car_tel']);
        $submail->SetProject('F5Cd1');
        $submail->AddVar('mobile', $mobile);
        $send=$submail->xsend();
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
}
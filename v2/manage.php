<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/4/21
 * Time: 23:31
 */
require_once('../core/init.config.php');
require_once(ROOT_PATH . 'core/common.php');
require_once(ROOT_PATH.'model/MessageModel.php');
require_once('./const/const.php');
require_once(ROOT_PATH . 'model/SmsModel.php');

$act = empty($_REQUEST['act']) ? 'default' : $_REQUEST['act'];
$id = empty($_REQUEST['id']) ? 0 : $_REQUEST['id'];
$code = empty($_REQUEST['code']) ? '' : $_REQUEST['code'];

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
    case 'update':
        $seatNum = empty($_REQUEST['seat']) ? 0 : $_REQUEST['seat'];
        $returnArr = [
            'result' => 1,
            'msg' => '修改成功！'
        ];
        if ($id <= 0) {
            $returnArr = [
                'result' => 0,
                'msg' => 'id不能小于或等于0！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        if ($seatNum < 0) {
            $returnArr = [
                'result' => 0,
                'msg' => '座位数不能小于0！'
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
        // 检查验证码是否一致(根据手机号查询验证码，然后对比)
        $sms = new SmsModel();
        $smsResult = $sms->getSmsByConditions($msgResult['car_tel']);
        if (empty($smsResult) || $smsResult['code'] != $code) {
            $returnArr = [
                'result' => 0,
                'msg' => '错误的验证码！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        $msgObj->setCarSeatnum($seatNum);
        $msgObj->setUpdateTime(getLocalDateTime());
        $msgObj->addWhere(['id' => $id]);
        $msgObj->update();
        die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        break;
    case 'cancel':
        $returnArr = [
            'result' => 1,
            'msg' => '取消成功！'
        ];
        if ($id <= 0) {
            $returnArr = [
                'result' => 0,
                'msg' => 'id不能小于或等于0！'
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
        // 检查验证码是否一致(根据手机号查询验证码，然后对比)
        $sms = new SmsModel();
        $smsResult = $sms->getSmsByConditions($msgResult['car_tel']);
        if (empty($smsResult) || $smsResult['code'] != $code) {
            $returnArr = [
                'result' => 0,
                'msg' => '错误的验证码！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        $msgObj->setCarSeatnum($seatNum);
        $msgObj->setUpdateTime(getLocalDateTime());
        //$msgObj->setStatus(MessageModel::STATUS_INVALID);
        $msgObj->setIsDeleted(MessageModel::DELETED_YES);
        $msgObj->addWhere(['id' => $id]);
        $msgObj->update();
        die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        break;
}

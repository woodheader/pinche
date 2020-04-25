<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/4/21
 * Time: 18:05
 */
require './SUBMAIL_PHP_SDK/app_config.php';
require_once('./SUBMAIL_PHP_SDK/SUBMAILAutoload.php');
require_once('../core/common.php');
require_once('../core/init.config.php');
require_once(ROOT_PATH . 'core/help.class.php');
require_once(ROOT_PATH . 'model/SmsModel.php');
require_once(ROOT_PATH.'model/MessageModel.php');

$act = empty($_REQUEST['act']) ? 'default' : $_REQUEST['act'];
$id = empty($_REQUEST['id']) ? 0 : $_REQUEST['id'];

switch ($act) {
    case 'query':
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
        // 检查手机号是否发送过验证码
        $sms = new SmsModel();
        $smsResult = $sms->getSmsByConditions($msgResult['car_tel']);
        if (empty($smsResult)) {
            $returnArr = [
                'result' => 0,
                'msg' => '没有找到验证码发送记录！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        $returnArr = [
            'result' => 1,
            'msg' => '发送过验证码！'
        ];
        die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        break;
    case 'send':
        $returnArr = [
            'result' => 1,
            'msg' => '发送成功！'
        ];

        $mobile = empty($_POST['mobile']) ? '' : $_POST['mobile'];
        if (empty($mobile)) {
            $returnArr = [
                'result' => 0,
                'msg' => '手机号码不能为空！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }

        $code = generateSmsCode();

        // 先保存入库
        $sms = new SmsModel();
        $sms->setCarTel($mobile);
        // 若对应手机号已经发送过短信，并且还没有过期，就不再二次发送，节省费用
        $result = $sms->getSmsByConditions($mobile);
        if (!empty($result)) {
            if ($result['expire_time'] > date('Y-m-d H:i:s')) {
                die(json_encode($returnArr));
            }
        }
        $sms->setCode($code);
        $sms->setExpireTime(date('Y-m-d H:i:s', strtotime('+1 year')));
        $sms->setStatus(SmsModel::STATUS_VALID);
        $sms->setCreateTime(date('Y-m-d H:i:s'));
        $sms->setUpdateTime(date('Y-m-d H:i:s'));
        $r = $sms->save();
        if (!$r) {
            $returnArr = [
                'result' => 0,
                'msg' => '发送失败！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }

        // 开始发送
        $submail = new MESSAGEXsend($message_configs);
        $submail->SetTo($mobile);
        $submail->SetProject('vrNoP3');
        $submail->AddVar('code', $code);
        $send=$submail->xsend();
        if ($send['status'] != 'success') {
            file_put_contents(LOG_PATH . '/sms.log', json_encode($send, JSON_UNESCAPED_UNICODE) . "\r\n", FILE_APPEND);
            $returnArr = [
                'result' => 0,
                'msg' => '发送失败！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }

        die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        break;
    case 'sendPassenger':

        break;
    case 'default':
    default:
        $returnArr = [
            'result' => 0,
            'msg' => '非法操作！'
        ];
        die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        break;
}
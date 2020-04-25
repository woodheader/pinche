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
require_once(ROOT_PATH . 'model/OrderModel.php');

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

        break;
}
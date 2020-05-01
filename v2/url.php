<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/4/26
 * Time: 13:12
 */


require_once('../core/init.config.php');
require_once('../core/common.php');
require_once(ROOT_PATH . 'core/help.class.php');
require_once(ROOT_PATH.'model/ShortUrlModel.php');
require_once(ROOT_PATH.'model/ShortUrlLogModel.php');

$act = empty($_REQUEST['act']) ? 'default' : $_REQUEST['act'];

switch ($act) {
    case 'default':
    default:
        $returnArr = [
            'result' => 0,
            'msg' => '无操作！'
        ];
        die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        break;
    case 'generate':
        $token = empty($_REQUEST['token']) ? '' : $_REQUEST['token'];
        $longUrl = empty($_REQUEST['longUrl']) ? '' : $_REQUEST['longUrl'];
        if (empty($token) || $token != 'lsj') {
            $returnArr = [
                'result' => 0,
                'msg' => '没有权限',
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        $shortUrl = shorturl($longUrl)[0];
        // 检查短链接是否存在
        $short = new ShortUrlModel();
        $data = $short->getUrlByConditions($shortUrl);
        if (!empty($data)) {
            $returnArr = [
                'result' => 1,
                'msg' => SERVER_HOST. $data['short_url'],
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }

        $formData = [
            'url' => $longUrl,
            'short_url' => $shortUrl,
            'expire_time' => getAnyDateTime('+1 year'),
            'status' => ShortUrlModel::STATUS_VALID,
            'create_time' => getLocalDateTime(),
            'update_time' => getLocalDateTime(),
        ];
        $short = new ShortUrlModel();
        $short->load($formData);
        $short->save();
        $returnArr = [
            'result' => 1,
            'msg' => SERVER_HOST. $shortUrl
        ];
        die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        break;
    case 'redirect':
        $shortUrl = empty($_REQUEST['shortUrl']) ? '' : $_REQUEST['shortUrl'];
        if (empty($shortUrl) || strlen($shortUrl) < 6) {
            $returnArr = [
                'result' => 0,
                'msg' => '不合法的短链接！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        $short = new ShortUrlModel();
        $data = $short->getUrlByConditions($shortUrl);
        if (empty($data)) {
            $returnArr = [
                'result' => 0,
                'msg' => '找不到短链接数据！'
            ];
            die(json_encode($returnArr, JSON_UNESCAPED_UNICODE));
        }
        $short->setVisitCount($data['visit_count']+1);
        $short->setUpdateTime(getLocalDateTime());
        $short->addWhere(['id' => $data['id']]);
        $short->update();
        $formData = [
            'url_id' => $data['id'],
            'equ' => help::getEqu(),
            'sys' => help::getOsInfo(),
            'browser' => help::getBrowserInfo().'-'.help::getBrowserLang(),
            'ip' => help::getip(),
            'city' => help::getLocation(),
            'agent' => help::getUserAgent(),
            'status' => ShortUrlLogModel::STATUS_VALID,
            'create_time' => getLocalDateTime(),
            'update_time' => getLocalDateTime(),
        ];
        $log = new ShortUrlLogModel();
        $log->load($formData);
        $log->save();
        header("Location: ". htmlspecialchars_decode($data['url']));
        break;
}
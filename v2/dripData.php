<?php
require_once('../core/init.config.php');
require_once(ROOT_PATH.'core/help.class.php');
require_once(ROOT_PATH.'model/MessageModel.php');
require_once(ROOT_PATH . 'core/common.php');

$channel = $_POST['c'];
$data = $_POST['data'];

if (empty($channel)) {
	echo '错误的通道类型';
	return;
}

if (empty($data)) {
    echo '服务端没有收到任何消息...';
    return;
}

$jsonObj = json_decode($data);

if (empty($jsonObj) || $jsonObj->status != 'success') {
	echo '提交的数据状态不是success';
	return;	
}

$insertDataList = [];

// 表单提交第一层级字段映射
$filterKey = ['id' => 'drip_id',
    'formId' => 'form_id',
    'isDeleted' => 'is_deleted',
    'answers' => 'answers',
    'ip' => 'ip',
    'createTime' => 'create_time',
    'updateTime' => 'update_time'
];

// 表单提交第二层级字段映射
$answerFieldMapping = [
    '始发站日期' => 'car_time',
    '始发站时间' => 'car_time',
    '日期' => 'car_time',
    '时间' => 'car_time',
    '方向' => 'goto',
    '行驶路线' => 'car_line',
    '座位数' => 'car_seatnum',
    '单价' => 'car_price',
    '联系方式' => 'car_tel',
    '车主微信' => 'car_wechat_img',
    '车牌信息' => 'car_license_plate',
];

// 单价
$priceArr = [
    '1' => '20',
    '2' => '25',
    '3' => '30',
    '4' => '999',
];
$priceHbArr = [
    '1' => '80',
    '2' => '90',
    '3' => '100',
    '4' => '110',
    '5' => '120',
    '6' => '999',
];

function downloadImg($imgSrc, $mobile) {

    //$host = 'http://localhost';
    $host = 'https://uri.wiki';

    if ($imgSrc == 'undefined' || empty($imgSrc)) {
        return $host.'/pinche/v2/img/qrcode.png';
    }

    $target = './img/userqr/'.$mobile.'.png';

    // 验证手机号对应的图片是否存在
    if (file_exists($target)) {
        return $host.'/pinche/v2/img/userqr/'.$mobile.'.png';
    }

    $img = file_get_contents($imgSrc);
    file_put_contents($target, $img);

    return $host.'/pinche/v2/img/userqr/'.$mobile.'.png';
}


foreach($jsonObj->result->participants as $formData) {
	$insertData = [];
	foreach ($formData as $dripField => $dripFieldValue) {
		foreach($filterKey as $dField => $ourField) {
			if ($dripField == $dField) {
			    // 保存表单数据
				$insertData[$ourField] = $dripFieldValue;
				// 处理拼车数据
				if ($dripField == 'answers') {
					$insertData[$ourField] = json_encode($dripFieldValue, JSON_UNESCAPED_UNICODE);
					// 处理表单数据的日期、方向、路线等等
                    foreach ($dripFieldValue as $answerData) {
                        $filedLabel = $answerData->filedLabel;
                        $fieldValue = $answerData->fieldValues[0];
                        // 特殊字段处理
                        if (strpos($filedLabel, '时间') !== false) {
                            $insertData[$answerFieldMapping[$filedLabel]] = $insertData[$answerFieldMapping[$filedLabel]].' '.$fieldValue. ':00';
                        } elseif (strpos($filedLabel, '微信') !== false) {
                            $wechatImgLink = $answerData->fieldValueArr[0]->url;
                            $insertData[$answerFieldMapping[$filedLabel]] = downloadImg($wechatImgLink, $insertData['car_tel']);
                        } elseif (strpos($filedLabel, '单价') !== false) {
                            $insertData[$answerFieldMapping[$filedLabel]] = $channel == 1 ? $priceArr[$fieldValue] : $priceHbArr[$fieldValue];
                        } else {
                            $insertData[$answerFieldMapping[$filedLabel]] = $fieldValue;
                        }
                    }
				}
			}
		}
	}
	$insertDataList[] = $insertData;
}

foreach ($insertDataList as $message) {
    $msgObj = new MessageModel();
    $msgObj->setChannel($channel);
    $msgObj->load($message);
    $msgResult = $msgObj->getMessageByConditions($msgObj->getCarTime(), $msgObj->getGoto(), $msgObj->getCarTel());
    if (!empty($msgResult)) {
        // 若重复发布，修改更新时间（判定重复依据：dripId 是否一致，若不一致，表示为新提交）
        if ($msgResult['drip_id'] != $msgObj->getDripId()) {
            $msgObj->setUpdateTime(getLocalDateTime());
            $msgObj->addWhere(['id' => $msgResult['id']]);
            $msgObj->update();
        }
        continue;
    }
    $msgObj->save();
}

echo 'success';

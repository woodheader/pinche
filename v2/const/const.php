<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/4/17
 * Time: 21:38
 */
$areaTypeList = [
    'miyun' =>'1,2',
    'hb' =>'1,2,3,4'
];
$areaArr = [
    'miyun' =>'密云',
    'hb' =>'河北',
];
$gotoMapping = [
    1 =>'去北京',
    2 =>[1 => '去密云', 2 => '去平山'],
    3 =>'去石家庄',
    4 =>'去邯郸',
];
$channelMapping = [
    'miyun' => 1,
    'hb' => 2
];
$channelMappingForKey = [
    1 => 'miyun',
    2 => 'hb',
];
$msgTitle = [
   '日期:', '方向:', '行驶路线:', '座位数:', '单价:', '联系方式:', '车牌信息:'
];
$priceMapping = [
    20 => '20',
    25 => '25',
    80 => '80',
    90 => '90',
    100 => '100',
    110 => '110',
    120 => '120',
    999 => '协商'
];


<?php
/**
 * 公共工具函数集合
 *
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2019/2/16
 * Time: 19:49
 */

/**
 * 比较两个字符串是否相似
 *
 * @param $oldWorlds
 * @param $newWorlds
 * @return int
 */
function compareIsSimilarWords($oldWorlds, $newWorlds) {
    similar_text($oldWorlds, $newWorlds, $persent);
    return intval(ceil($persent));
}

/**
 * 替换掉图标字符串
 *
 * @param $str
 * @return string
 */
function replaceImgString($str) {

    $rtnString = '';

    if (empty($str)) {
        return $rtnString;
    }

    // 图标 - 文字 - 图标
    $firstCaseNum = preg_match('/&amp;gt;(.*)&amp;lt;/i', $str, $firstMatches);
    if ($firstCaseNum > 0) {
        return $firstMatches[1];
    }

    // 其他情况，直接正则替换掉图片就行
    return preg_replace('/&amp;lt;(.*)&amp;gt;/i', '', $str);
}

/**
 * 写消息对比相似度日志
 *
 * @param $oldMsg
 * @param $newMsg
 * @param $similar
 */
function writeCompareLog($oldMsg, $newMsg, $similar) {
    file_put_contents(ROOT_PATH . '/catch.log',
        date('Y-m-d H:i:s').'--- 旧消息: '. $oldMsg . ' | 新消息: ' . $newMsg . ' | 相似度' . $similar ."\r\n",
        FILE_APPEND);
}

function connectRedis() {

}

function generateSmsCode() {
    $key = '';
    $pattern='1234567890';
    for( $i=0; $i<6; $i++ ) {
        $key .= $pattern[mt_rand(0, 9)];
    }
    return $key;
}

function getLocalDateTime() {
    return date('Y-m-d H:i:s');
}
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

function getAnyDateTime($flag = '') {
    if (empty($flag)) {
        return date('Y-m-d H:i:s');
    }
    return date('Y-m-d H:i:s', strtotime($flag));
}

function shorturl($url) {
    $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $key = 'abc'; //加盐
    $urlhash = md5($key . $url);
    $len = strlen($urlhash);

    //将加密后的串分成4段，每段4字节，对每段进行计算，一共可以生成四组短连接
    for ($i = 0; $i < 4; $i++) {
        $urlhash_piece = substr($urlhash, $i * $len / 4, $len / 4);

        //将分段的位与0x3fffffff做位与，0x3fffffff表示二进制数的30个1，即30位以后的加密串都归零
        //此处需要用到hexdec()将16进制字符串转为10进制数值型，否则运算会不正常
        $hex = hexdec($urlhash_piece) & 0x3fffffff;

        //域名根据需求填写
        $short_url = "";

        //生成6位短网址
        for ($j = 0; $j < 6; $j++) {

            //将得到的值与0x0000003d,3d为61，即charset的坐标最大值
            $short_url .= $charset[$hex & 0x0000003d];

            //循环完以后将hex右移5位
            $hex = $hex >> 5;
        }

        $short_url_list[] = $short_url;
    }

    return $short_url_list;
}
<?php

phpinfo();die;

$arr1 = [9,2,3,5,7,6,10];  // php sort 函数
$arr2 = [9,2,3,5,7,6,10];  // 自己实现
$arr3 = [9,2,3,5,7,6];  // 快速排序
$arr4 = [9,2,3,5,7,6];


/**
 * @param array $arr 要排序的数组
 * @param int $type 排序类型,0升序;1降序
 */
function mysort($arr, $type = 0) {
    if (empty($arr)) {
        return [];
    }
    switch ($type) {
        case 0: // 升序
            $n = count($arr);
            if ($n <= 1) {
                return $arr;
            }
            $baseNum = $arr[0];
            $leftArr = [];
            $rightArr = [];
            foreach ($arr as $idx => $val) {
                if ($idx == 0) {
                    continue;
                }
                if ($val <= $baseNum) {
                    $leftArr[] = $val;
                }
                if ($val > $baseNum) {
                    $rightArr[] = $val;
                }
            }
            echo '<pre/>';
            echo '1-----';
            print_r($leftArr);
            $leftArr = mysort($leftArr);
            $rightArr = mysort($rightArr);

            //print_r($rightArr);
            $fArr = array_merge($leftArr, [$baseNum], $rightArr);
            echo '2-----';
            print_r($leftArr);
            //print_r($fArr);
            return $fArr;
            /*if ($n > 1) {
                $leftMaxIndex = floor($n / 2);
                $leftArr = array_slice($arr, 0, $leftMaxIndex);
                $rightArr = array_slice($arr, $leftMaxIndex, $n);
                print_r($leftArr);
                print_r($rightArr);
                mysort($leftArr, $type);
            }*/
            break;
        case 1:

            break;
    }
}

/**
 * 冒泡排序 O(n2)
 * @param $arr
 * @return mixed
 */
function bubbleSort($arr)
{
    $len = count($arr);
    //该层循环控制 需要冒泡的轮数
    for ($i = 0; $i < $len-1; $i++) { //该层循环用来控制每轮 冒出一个数 需要比较的次数
        for ($j = $len - 1; $j > $i; $j--) {
            if ($arr[$j - 1] > $arr[$j]) {
                swap($arr, $j - 1, $j);
            }
        }
    }
    return $arr;
}

/**
 * 交换数组arr下表为i和j的值
 * @param array $arr
 * @param $i
 * @param $j
 */
function swap(array &$arr, $i, $j)
{
    $tmp = $arr[$i];
    $arr[$i] = $arr[$j];
    $arr[$j] = $tmp;
}


//------------- php sort 函数排序 ——------------------
$startTime = microtime(true) * 1000;
sort($arr1);
$endTime = microtime(true) * 1000;

print_r($arr1);
echo '自带函数花费时间：' . (($endTime - $startTime)) . ' 毫秒.<br>';

//------------- 按照自己想法实现排序 ——------------------
$startTime = microtime(true) * 1000;
mysort($arr2);
$endTime = microtime(true) * 1000;
echo '快速排序花费时间：' . (($endTime - $startTime)) . ' 毫秒.<br>';

// ---------- 递归 - 实现字符串翻转 -------------
function reverseWords($words) {
    if (empty($words)) {
        return '';
    }
    static $newWords = [];
    if(strlen($words)>0){
        reverseWords(substr($words,1));
    }
    $newWords[] = substr($words,0,1);
    return $newWords;
}
print_r(reverseWords('Hello'));

// ---------------- 冒泡排序 ----------------------
$startTime = microtime(true) * 1000;
$arr3 = bubbleSort($arr3);
print_r($arr3);
$endTime = microtime(true) * 1000;
echo '冒泡排序花费时间：' . (($endTime - $startTime)) . ' 毫秒.<br>';

// ----------------- 递归目录 ---------------------
function loopDir($dir){
    static $dirList = [];
    $handle = opendir($dir);
    while(false !==($file =readdir($handle))){
        if($file!='.'&&$file!='..' && $file != '.DS_Store'){
            $dirList[] = $dir.'/'.$file;
            if(filetype($dir.'/'.$file)=='dir'){
                loopDir($dir.'/'.$file);
            }
        }
    }
    return $dirList;
}

print_r(loopDir('./'));

<?php

$html = '<table class="statistics-table">   <thead><tr class="hd"><th class="seq">序号</th><th>车主行程</th></tr></thead>   <tbody><tr><td>3</td><td><b>始发站日期:</b> 2020-04-08<b></b> 07:50<br><b>方向:</b> <span style="color:red;font-weight:bold;">去北京</span><br><b>行驶路线:</b> 学府，果园，西大桥，西统路高速，六环上地<br><b>座位数:</b> 3位<br><b>单价:</b> 25<br><b>联系方式:</b> <a href="tel://13241977101">13241977101</a><br><b>微信二维码:</b> <img class="qrcode" src="https://qiniu.drip.im/form/ac07dc43-e5d7-4863-af82-49241105d8d6/18879d98-c843-4859-9cbe-b3f8504dfda2.png" width="16px" height="16px" alt="车主微信"><br><b>车牌信息:</b> 别克威朗（9A57）</td></tr><tr><td>2</td><td><b>始发站日期:</b> 2020-04-08<b></b> 18:10<br><b>方向:</b> <span style="color:green;font-weight:bold;">去密云</span><br><b>行驶路线:</b> 万霖大厦，华联，五环高速密云，万象汇，少年宫，果园，终点学府<br><b>座位数:</b> 3位<br><b>单价:</b> 25<br><b>联系方式:</b> <a href="tel://13241977101">13241977101</a><br><b>微信二维码:</b> <img class="qrcode" src="undefined" width="16px" height="16px" alt="车主微信"><br><b>车牌信息:</b> 别克威朗（9A57）</td></tr></tbody> </table>';

preg_match_all('/src="(.*?)"/' ,$html, $r);

echo '<pre/>';
print_r($r[1][0]);


//$img = file_get_contents('https://qiniu.drip.im/form/ac07dc43-e5d7-4863-af82-49241105d8d6/18879d98-c843-4859-9cbe-b3f8504dfda2.png');
//file_put_contents('./img/userqr/a.png', $img);
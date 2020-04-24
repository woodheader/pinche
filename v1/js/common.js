var showImg = function() {
	var $image = $('.qrcode');
	$image.viewer();
}

setTimeout(showImg, 2000);

var copyWords = function() {
	var tdHtml = '';
	$('.btnCopy').click(function(){
		tdHtml = $(this).parent().html();
		
		var aObj = $(this).parent().find('a');
		
		var imgObj = $(this).parent().find('img').first();
		
		var btnObj = $(this).parent().find('button').first();
		
		tdHtml = replaceFunc(tdHtml, '<b>', '');
		tdHtml = replaceFunc(tdHtml, '</b>', '');
		tdHtml = replaceFunc(tdHtml, '<br>', '\r\n');
		tdHtml = replaceFunc(tdHtml, '<span style="color:red;font-weight:bold;">', '');
		tdHtml = replaceFunc(tdHtml, '<span style="color:green;font-weight:bold;">', '');
		tdHtml = replaceFunc(tdHtml, '</span>', '');
		tdHtml = replaceFunc(tdHtml, aObj.prop("outerHTML"), aObj.html());
		tdHtml = replaceFunc(tdHtml, imgObj.prop("outerHTML"), aObj.html());
		tdHtml = replaceFunc(tdHtml, btnObj.prop("outerHTML"), '');
		tdHtml = replaceFunc(tdHtml, '&nbsp;&nbsp;&nbsp;&nbsp;', '');
	});
	
	var clipboard = new ClipboardJS('.btnCopy',{ 
			text: function(trigger){
							var href = window.location.href;
							if (href.indexOf('miyun') != -1) {
								href = 'http://rrd.me/gCUN8';
							} else {
								href = 'http://rrd.me/gCUPk';
							}
							tdHtml = tdHtml + '\r\n点击查看更多车主行程: ' + href;
							console.log('================'+tdHtml);
							return tdHtml;
						}});
						
	clipboard.on('success', function(e) {
    alert("复制成功");
  });

  clipboard.on('error', function(e) {
    alert("复制失败");
  });
}

setTimeout(copyWords, 500);

var replaceFunc = function (str, from, to) {
    var reg = new RegExp(from,"g");
    //g表示全文替换
    var res = str.replace(reg, to);
    return res;
}
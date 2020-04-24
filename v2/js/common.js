(function() {
    'use strict';

    var $ = $ || window.$;

    var server = 'https://uri.wiki/pinche/v2';
    server = 'http://localhost/pinche/v2';

    /**
	 * 图片浮现
     */
    var showImg = function() {
        var $image = $('.qrcode');
        $image.viewer();
    }

    /**
	 * 拼车消息复制
     */
    var copyWords = function() {
        var tdHtml = '';
        $('.btnCopy').click(function(){
            tdHtml = $(this).parent().html();
            var aObj = $(this).parent().find('a');
            var aObjOuterHtml = aObj.prop("outerHTML");
            var newAObjOuterHtml = aObjOuterHtml.replace('>'+aObj.html()+'<', '>'+aObj.attr('href').replace('tel://', '')+'<');
            tdHtml = tdHtml.replace(aObjOuterHtml, newAObjOuterHtml);
            var imgObj = $(this).parent().find('img').first();
            var btnObj1 = $(this).parent().find('button').first();
            var btnObj2 = $(this).parent().find('button').last();
            tdHtml = replaceFunc(tdHtml, '<div class="div-msg-left">', '');
            tdHtml = replaceFunc(tdHtml, '<div class="div-msg-right">', '');
            tdHtml = replaceFunc(tdHtml, '<div class="div-msg-right allow-edit" contenteditable="true">', '');
            tdHtml = replaceFunc(tdHtml, '</div>', '');
            tdHtml = replaceFunc(tdHtml, '<b>', '');
            tdHtml = replaceFunc(tdHtml, '</b>', '');
            tdHtml = replaceFunc(tdHtml, '<br>', '\r\n');
            tdHtml = replaceFunc(tdHtml, '<span style="color:red;font-weight:bold;">', '');
            tdHtml = replaceFunc(tdHtml, '<span style="color:green;font-weight:bold;">', '');
            tdHtml = replaceFunc(tdHtml, '</span>', '');
            tdHtml = replaceFunc(tdHtml, newAObjOuterHtml, aObj.attr('href').replace('tel://', ''));
            if (imgObj.prop("outerHTML") !== undefined) {
                tdHtml = replaceFunc(tdHtml, imgObj.prop("outerHTML"), aObj.attr('href').replace('tel://', ''));
            }
            tdHtml = replaceFunc(tdHtml, btnObj1.prop("outerHTML"), '');
            tdHtml = replaceFunc(tdHtml, btnObj2.prop("outerHTML"), '');
            tdHtml = replaceFunc(tdHtml, '&nbsp;', '');
        });

        var clipboard = new ClipboardJS('.btnCopy',{
            text: function(trigger){
                var href = window.location.href;
                if (href.indexOf('miyun') != -1) {
                    href = 'http://rrd.me/gDTFw';
                } else {
                    href = 'http://rrd.me/gDTF4';
                }
                tdHtml = tdHtml + '\r\n点击查看更多车主行程: ' + href;
                console.log(''+tdHtml);
                return tdHtml;
            }});

        clipboard.on('success', function(e) {
            alert("复制成功");
        });

        clipboard.on('error', function(e) {
            alert("复制失败");
        });
    }

    var replaceFunc = function (str, from, to) {
        var reg = new RegExp(from,"g");
        //g表示全文替换
        var res = str.replace(reg, to);
        return res;
    }

    /**
     * 预订
     */
    var carBuy = function () {
    	$('.btnBuy').click(function() {
            alert('功能开发中...');
        });
    }

    /**
     * 发送验证码
     */
    var sendCode = function (mobile) {
        $.ajax({
            url: server + '/sms.php',
            type: 'post',
            dataType: 'json',
            data: 'act=send&mobile=' + mobile,
            success: function(res) {
                console.log("服务器返回数据："+JSON.stringify(res));
                return res;
            }
        });
    }

    /**
     * 修改座位数
     */
    var editSeat = function () {
        var seatNum = 0;
        $('.allow-edit').on('blur', function() {
            // 数据ID
            var id = $(this).parent('td').prev().html();
            // 手机号
            var mobile = $(this).parent('td').find('a').html();
            var divContent = $(this).html().replace(/<\/?[^>]*>/g, '').replace(/[ ]/g,'').replace(/[\r\n]/g,'');
            seatNum = $.trim(divContent).substr(0,1);
            if (seatNum > 6) {
                alert('座位数不能大于6个!');
                $(this).html((seatNum-1) + '位');
            }
            if (seatNum <= 0) {
                alert('提示：你已将座位数调整为0个,乘客将不会再预定!');
            }
            var code = '';
            // 查询历史发送状态
            var isSend = validateIsSend(id);
            if (isSend === 1) {
                smsCodeDialog('调整座位','update',id,seatNum);
            } else {
                let otherData = {
                    'mobile': mobile,
                    'title': '调整座位',
                    'action': 'update',
                    'dataId': id,
                    'seatNum': seatNum
                };
                msgDialog('温馨提示',
                    '请确认: '+mobile+' 是否属于你？<br/><br/>点击确定，该手机号将会收到一个验证码，此验证码长期有效，可用于后续每日行程管理！',otherData);
            }
        });
    }

    /**
     * 取消行程
     */
    var cancelCar = function() {
        $('#dialog-confirm-code').hide();
        $('#dialog-confirm-msg').hide();
        $('.btnCancel').click(function(){
            // 数据ID
            var id = $(this).parent('td').prev().html();
            // 手机号
            var mobile = $(this).parent('td').find('a').html();
            // 查询历史发送状态
            var isSend = validateIsSend(id);
            if (isSend === 1) {
                smsCodeDialog('取消行程','cancel',id,0);
            } else {
                let otherData = {
                    'mobile': mobile,
                    'title': '取消行程',
                    'action': 'cancel',
                    'dataId': id,
                    'seatNum': 0
                };
                msgDialog('温馨提示',
                    '请确认: '+mobile+' 是否属于你？<br/><br/>点击确定，该手机号将会收到一个验证码，此验证码长期有效，可用于后续每日行程管理！',otherData);
            }
        });
    }

    /**
     * 取消或编辑前，需要验证对应手机号是否已经发送过验证码，如果是则省略掉一个弹出框
     */
    var validateIsSend = function (id) {
        var result = 0;
        $.ajax({
            url: server + '/sms.php',
            type: 'post',
            dataType: 'json',
            async: false,
            data: 'act=query&id=' + id,
            success: function(res) {
                console.log("服务器返回数据："+JSON.stringify(res));
                result = res.result;
            }
        });
        return result;
    }

    /**
     * 取消行程/调整座位弹出框
     */
    var smsCodeDialog = function(title,act,id,seatNum) {
        $('#dialog-confirm-code').show();
        $('#dialog-confirm-code').dialog({
            resizable: false,
            height: "auto",
            width: 260,
            modal: true,
            title: title,
            buttons: {
                '确定': function() {
                    $.ajax({
                        url: server + '/manage.php',
                        type: 'post',
                        dataType: 'json',
                        data: 'act='+act+'&id=' + id + '&code=' + $('#code').val() + '&seat='+ seatNum,
                        success: function(res) {
                            console.log("服务器返回数据："+JSON.stringify(res));
                            alert(res.msg);
                            window.location.reload();
                        }
                    });
                    $( this ).dialog( "close" );
                },
                '取消': function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    }

    /**
     * 温馨提示弹出框
     */
    var msgDialog = function(title,msg,otherData) {
        $('#dialog-confirm-msg').show();
        $('#dialog-confirm-msg').attr('title', title);
        $('#message').html(msg);
        $('#dialog-confirm-msg').dialog({
            resizable: false,
            height: 'auto',
            width: 260,
            modal: true,
            buttons: {
                '确定': function() {
                    $(this).dialog( 'close' );
                    sendCode(otherData.mobile);
                    smsCodeDialog(otherData.title,otherData.action,otherData.dataId,otherData.seatNum);
                },
                '取消': function() {
                    $(this).dialog( 'close' );
                }
            }
        });
    }

    setTimeout(showImg, 1000);
    setTimeout(copyWords, 500);
    setTimeout(carBuy, 500);
    setTimeout(editSeat, 500);
    setTimeout(cancelCar, 500);
})();


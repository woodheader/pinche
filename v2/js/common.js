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
    var sendCode = function (mobile,type) {
        var r = 0;
        $.ajax({
            url: server + '/sms.php',
            type: 'post',
            dataType: 'json',
            data: 'act=send&mobile=' + mobile+'&type='+type,
            async: false,
            success: function(res) {
                console.log("服务器返回数据："+JSON.stringify(res));
                r = res.result;
            }
        });
        return r;
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
                    sendCode(otherData.mobile,1);
                    smsCodeDialog(otherData.title,otherData.action,otherData.dataId,otherData.seatNum);
                },
                '取消': function() {
                    $(this).dialog( 'close' );
                }
            }
        });
    }

    /**
     * 点击预订按钮
     */
    var btnOrderClick = function () {
        $('.btnOrder').click(function() {
            // 数据ID
            var id = $(this).parent('td').prev().html();
            orderDialog(id);
        });
    }

    /**
     * 预订发送验证码按钮
     */
    var btnPassengerClick = function () {
        $('.btnPassenger').click(function() {
            var btnPassenger = $(this);
            var mobile = $('#orderMobile').val();
            if (empty(mobile)) {
                $( ".validateTips" ).text('请填写手机号').addClass( "ui-state-highlight" );
                return;
            }
            var sendResult = sendCode(mobile, 2);
            btnPassenger.css('background-color', '#9e9e9eb0');
            btnPassenger.css('border-color', '#9e9e9eb0');
            btnPassenger.attr('disabled', true);
            var time = 10;
            var interval;
            interval = setInterval(function() {
                if (time < 1) {
                    clearInterval(interval);
                    btnPassenger.css('background-color', '#4caf50');
                    btnPassenger.css('border-color', '#4caf50');
                    btnPassenger.attr('disabled', false);
                    btnPassenger.html('发送');
                    if (sendResult === 1) {
                        btnPassenger.hide();
                        $('.spanMsg').show();
                    }
                    return;
                }
                $('.btnPassenger').html(time + 's 后重新发送');
                time--;
            }, 1000);
        });
    }

    /**
     * 预订弹出框
     */
    var orderDialog = function(id) {
        var form = $("#dialog-form-order").find( "form" ).on( "submit", function( event ) {
            event.preventDefault();
            orderConfirm();
        });
        var tips = $( ".validateTips" );
        var mobile = $('#orderMobile');
        var code = $('#orderCode');
        var allFields = $( [] ).add( mobile ).add( code );

        // 从本地缓存读取数据，设置
        var localMobile = getLocalData('passengerMobile');

        // 手机号码在本地存在，表示已经预订成功过，预订成功表示该手机号已经发送过验证码，则需要隐藏发送按钮
        if (!empty(localMobile)) {
            mobile.val(localMobile);
            $('.btnPassenger').hide();
            $('.spanMsg').show();
        }

        // 如果乘客的旧手机号不用了，换了新手机号，要保证可以正常发送验证码
        mobile.keyup(function() {
            var curVal = $(this).val();
            if (curVal.length < 11 || curVal.length > 11) {
                return;
            }
            if (curVal !== localMobile) {
                $('.btnPassenger').show();
                $('.spanMsg').hide();
            } else {
                $('.btnPassenger').hide();
                $('.spanMsg').show();
            }
        });

        $("#dialog-form-order").dialog({
            //autoOpen: false,
            height: 'auto',
            width: 260,
            modal: true,
            buttons: {
                '确定': function () {
                    orderConfirm(id);
                },
                Cancel: function() {
                    $(this).dialog( 'close' );
                    window.location.reload();
                }
            },
            close: function() {
                form[ 0 ].reset();
                tips.text('填写预订信息');
                allFields.removeClass( "ui-state-error" );
            }
        });
    }

    /**
     * 确认预订逻辑
     */
    var orderConfirm = function (id) {
        var valid = true;
        var mobile = $('#orderMobile');
        var code = $('#orderCode');
        var allFields = $( [] ).add( mobile ).add( code );
        allFields.removeClass( "ui-state-error" );
        valid = valid && formCheckForLength( mobile, "手机号", 11, 11 );
        valid = valid && formCheckForLength( code, "验证码", 6, 6 );
        if (valid) {
            $.ajax({
                url: server + '/order.php',
                type: 'post',
                dataType: 'json',
                async: true,
                data: 'act=order&id=' + id + '&mobile=' + mobile.val() + '&code=' + code.val(),
                success: function(res) {
                    console.log("服务器返回数据："+JSON.stringify(res));
                    // 预订成功后，写入本地缓存
                    if(res.result === 1) {
                        setLocalData('passengerMobile', mobile.val());
                    }
                    $( ".validateTips" ).text(res.msg).addClass( "ui-state-highlight" );
                    setTimeout(function() {
                        $( ".validateTips" ).removeClass( "ui-state-highlight", 1500 );
                    }, 500 );
                    //$("#dialog-form-order").dialog('close');
                }
            });
        }
    }

    /**
     * 表单验证逻辑 - 长度验证
     */
    var formCheckForLength = function (o, n, min, max) {
        var tips = $( ".validateTips" );
        if ( o.val().length > max || o.val().length < min ) {
            o.addClass( "ui-state-error" );
            tips
                .text( n + " 必须是 " + min + '位。')
                .addClass( "ui-state-highlight" );
            setTimeout(function() {
                tips.removeClass( "ui-state-highlight", 1500 );
            }, 500 );
            return false;
        } else {
            return true;
        }
    }

    /**
     * 从本地获取和设置数据
     */
    var getLocalData = function (key) {
        return localStorage.getItem(key);
    }
    var setLocalData = function (key, val) {
        localStorage.setItem(key, val);
    }

    /**
     * 判断值是否为空
     */
    var empty = function (val) {
        return val === '' || val === undefined || val === null || val === 'undefined';
    }


    setTimeout(showImg, 1000);
    setTimeout(copyWords, 500);
    setTimeout(carBuy, 500);
    setTimeout(editSeat, 500);
    setTimeout(cancelCar, 500);
    setTimeout(btnOrderClick, 100);
    setTimeout(btnPassengerClick, 100);
})();


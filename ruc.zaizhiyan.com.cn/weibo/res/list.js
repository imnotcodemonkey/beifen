// JavaScript Document
function pic(){window.open('../include/dialog/select_weibo.php?f=f1.picname','popUpImagesWin', 'scrollbars=yes,resizable=yes,statebar=no,width=600,height=450,left=100, top=100');}
function showPic(n){
	if(n==1){$('#btns').html('网络地址：<input name="picname" onchange="showImg()" value="" id="imgurl" />');}
	if(n==2){
		$('#btns').html('图片地址：<input name="picname" onchange="showImg()" value="请在弹出窗口中选择" id="imgurl" />');
		pic();
	}
	//else{$('#btns').html('本地选择：<input name="picname" value="" id="picname" >\
	//<input name="litpic" type="file" id="litpic"  onchange="SeePicNew(this, \'divpicview\', \'uplitpicfra\', 165, \'weibolist.php\');" id="imgurl" />');}
	$('#btns').removeClass('none');
}
function changweibo(url){
	$('#btns').html('内容网址：<input name="picname" value="'+url+'" id="imgurl" />');
	$('#showpic').addClass('none');
	$('#btns').removeClass('none');
}
function check(){if($('#cont').val()=='')return false;$('#msg').hide()}
function showImg(){
	img=$('#imgurl').val().replace('请在弹出窗口中选择','');
	if(img!=''){
		$('#showpic').html('<a href="'+img+'" target="img"><img src="'+img+'" border=0 /></a>');
		$('#showpic').removeClass('none');
	}
	else{
		$('#showpic').html('');	
		$('#showpic').addClass('none');
	}
}


(function ($) {
    // tipWrap:  提示消息的容器
    // maxNumber:  最大输入字符
    $.fn.artTxtCount = function (tipWrap, maxNumber) {
        var countClass = 'js_txtCount',
            // 定义内部容器的CSS类名
            fullClass = 'js_txtFull',
            // 定义超出字符的CSS类名
            disabledClass = 'disabled'; // 定义不可用提交按钮CSS类名
        // 统计字数
        var count = function () {
                var btn = $(this).closest('form').find(':submit'),
                    val = getLength($(this).val(),maxNumber),
                    // 是否禁用提交按钮
                    disabled = {
                        on: function () {
                            btn.removeAttr('disabled').removeClass(disabledClass);
                        },
                        off: function () {
                            btn.attr('disabled', 'disabled').addClass(disabledClass);
                        }
                    };
                if (val == 0) disabled.off();
                if (val <= maxNumber) {
                    if (val > 0) disabled.on();
                    tipWrap.html('<span class="' + countClass + '">\u8FD8\u80FD\u8F93\u5165 <strong>' + (maxNumber - val) + '</strong> \u5B57</span>');
                } else {
                    disabled.off();
                    tipWrap.html('<span class="' + countClass + ' ' + fullClass + '">\u5DF2\u7ECF\u8D85\u51FA <strong>' + (val - maxNumber) + '</strong> \u5B57</span>');
                };
				//$(this).val($(this).val().replace(/  /gi,' '));
            };
        $(this).bind('keyup change', count);
        return this;
    };
})(jQuery);

var getLength = (function(){
    var trim = function(h) {
        try {
            return h.replace(/^\s+|\s+$/g, "")
        } catch(j) {
            return h
        }
    }
    var byteLength = function(b) {
        if (typeof b == "undefined") {
            return 0
        }
        var a = b.match(/[^\x00-\x80]/g);
        return (b.length + (!a ? 0 : a.length))
    };
 
    return function(q, g) {
        g = g || {};
        g.max = g.max || 140;
        g.min = g.min || 41;
        g.surl = g.surl || 20;
        var p = trim(q).length;
        if (p > 0) {
            var j = g.min,
            s = g.max,
            b = g.surl,
            n = q;    //输入字符串
            var r = q.match(/(http|https):\/\/[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)+([-A-Z0-9a-z\$\.\+\!\_\*\(\)\/\,\:;@&=\?~#%]*)*/gi) || [];
            var h = 0;
            for (var m = 0,p = r.length; m < p; m++) {
                //var o = byteLength(r[m]);
                //if (/^(http:\/\/t.cn)/.test(r[m])) {
                //    continue
                //} else {
                    //if (/^(http:\/\/)+(weibo.com|weibo.cn)/.test(r[m])) {
                    //    h += o <= j ? o: (o <= s ? b: (o - s + b))
                    //} else {
						//alert(r[m].length)
                         // o <= s ? b: (o - s + b)
                    //}
                //}
				h += 20 ;
                n = n.replace(r[m], "")
            }
            return Math.ceil((h + byteLength(n))/2)
        } else {
            return 0
        }
    }
})();


function formartCont(){
$('#list p span').each(function() {
	var h = $(this).html();
	var r = h.match(/(http|https):\/\/t.cn+([-A-Z0-9a-z\$\.\+\!\_\*\(\)\/\,\:;@&=\?~%]*)*/gi) || [];
    for (var m = 0;m<r.length; m++) {
		h = h.replace(r[m],'<a href="'+r[m]+'">'+r[m]+'</a>');
	}

	var a = h.match(/\@[^\ |"|”|\@]+\:/gi) || [];
    for (var m = 0;m<a.length; m++) {
			h = h.replace(a[m],'<a href="http://weibo.com/n/'+a[m].replace('@','').replace(' ','').replace(':','')+'">'+a[m]+'</a>');
	}
	var a = h.match(/\@[^\ |"|”|\:|\@]+\ /gi) || [];
    for (var m = 0;m<a.length; m++) {
			h = h.replace(a[m],'<a href="http://weibo.com/n/'+a[m].replace('@','').replace(' ','').replace(':','')+'">'+a[m]+'</a>');
	}
	h = h.replace(/\:\<\/a\>/gi,'</a>:');
	var t = h.match(/\#[^\#]+\#/gi) || [];
    for (var m = 0;m<t.length; m++) {
			h = h.replace(t[m],'<a href="http://huati.weibo.com/k/'+t[m].replace(/#/gi,'')+'/">'+t[m]+'</a>');
	}	
	$(this).html(h);
});
$("div#list a[class!='self']").attr("target","_blank");
}


function repost(str){
	$('#cont').val(str);
	$('#cont').focus();
}
function commentForm(i,txt,idstr){
	$('.forms').html('');
	str='<form action="save.php" name="f1" method="post" enctype="multipart/form-data" onsubmit="return check()">\
    <p><textarea name="text" id="cont" rows="4">'+txt+'</textarea></p>\
    <div id="t">\
    	<tt class="l">\
        	<a href="###" id="face">表情</a> |\
            <input type="checkbox" name="forward" value="0" id="do" /><label for="do">同时转发到我的微博</label> |\
            <span id="contTip"></span>\
        </tt>\
    	<label class="r"><input id="smt" type="submit" value="评论" /></label>\
        <input type="hidden" name="action" value="comments" />\
        <input type="hidden" name="id" value="'+idstr+'" />\
    </div>\
	</form>\
	<script type="text/javascript">\
	$("#face").sinaEmotion({\
	target: $("#cont")\
	});\
	jQuery(function(){\
		$("#cont").artTxtCount($("#contTip"), 140);\
	});\
	</script>';
	$('#form'+i).html(str);
}
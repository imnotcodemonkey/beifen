$.require("$ 20080304","SDrag");$.SDrag=$({__init:function(J,E,K){this.e=$E(J);this.hand=E?$E(E):this.e;var G={axis:"ALL",cursor:"move",maxZIndex:1000};this.sets=$.extend(G,K||{});this.hand.css({cursor:this.sets.cursor});if(this.e.css("position")!="absolute"){var M=this.e.$("$$position");this.e.css("position:absolute;left:"+M[0]+"px;top:"+M[1]+"px")}J=this.e,E=this.hand,K=this.sets;var D=this.sets.axis.toUpperCase(),B,H,F;var A=function(N){B=[$(N,"x"),$(N,"y")];H=[parseInt(J.css("left"))||0,parseInt(J.css("top"))||0];F=J.css("z-index")||0;J.css("z-index:"+K.maxZIndex);if(document.body.setCapture){document.body.setCapture()}else{if(window.captureEvents){window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP)}}$.observe(document,"mousemove",C);$.observe(document,"mouseup",L);(K.onStartDrag||$.empty)(J);$(N,"stop")};var L=function(N){J.css("z-index:"+F);if(document.body.releaseCapture){document.body.releaseCapture()}else{if(window.captureEvents){window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP)}}$.stopObserving(document,"mousemove",C);$.stopObserving(document,"mouseup",L);(K.onEndDrag||$.empty)(J);$(N,"stop")};var C=function(W){var T=[$(W,"x"),$(W,"y")],S=B,O=H;var V=[T[0]-S[0],T[1]-S[1]];var N={},X={};if(D=="ALL"||D=="H"){var R=O[0]+V[0];if(K.rangeX){var Q=K.rangeX;R=R<Q[0]?Q[0]:(R>Q[1]?Q[1]:R)}X.left=R;N.left=R+"px"}if(D=="ALL"||D=="V"){var U=O[1]+V[1];if(K.rangeY){var P=K.rangeY;U=U<P[0]?P[0]:(U>Q[1]?Q[1]:U)}X.top=U;N.top=U+"px"}if(K.onDrag){K.onDrag(X)}J.css(N);$(W,"stop")};var I=false;this.activate=function(){if(I){return }I=true;E.mousedown(A)};this.disable=function(){if(!I){return }I=false;E.$mousedown(A)};this.activate()}});$.SDrag.version=20080319;$.Dom._goto=function(G,B,H){var D=parseInt(G.style.left)||0,F=parseInt(G.style.top)||0;var C=B-D,A=H-F;if(C==0&&A==0){return }var E=$.effect({onFinish:function(){G.style.left=B+"px";G.style.top=H+"px"},render:function(I){G.style.left=(D+C*I)+"px";G.style.top=(F+A*I)+"px"},duration:0.5});$.Effect.add(E)};$.Dom._isHide=function(A){return A.style.display=="none"};function CopyUrl(D){var K=$(D.parentNode).find("input[type=text]");var C=K.e.value,L=$(D.parentNode).find("*.msg"),G="节目地址已复制，赶快分享给你的朋友吧!<br />按Ctrl+V粘贴到QQ/MSN对话框",F="请在浏览器地址栏输入'about:config'并回车<br/>然后将'signed.applets.codebase_principal_support'设置为'true'",B="您所使用的浏览器暂不支持该功能，请手动复制。";try{C=location.href.replace(/st=[^&]+/i,"")}catch(I){}if($.isIE){window.clipboardData.clearData();window.clipboardData.setData("Text",C);if(L){L.$("$class","+Green").html(G);L.$("$fade");$({timespan:5,callback:function(){L.$("$hide")}})}}else{if($.isGecko){try{netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");var E=Components.classes["@mozilla.org/widget/clipboard;1"].createInstance(Components.interfaces.nsIClipboard);if(!E){return }var M=Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);if(!M){return }M.addDataFlavor("text/unicode");var J=new Object();var H=new Object();var J=Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);J.data=C;M.setTransferData("text/unicode",J,C.length*2);var A=Components.interfaces.nsIClipboard;if(!E){return false}E.setData(M,null,A.kGlobalClipboard);if(L){L.$("$class","+Green").html(G);L.$("$fade");$({timespan:5,callback:function(){L.$("$hide")}})}}catch(I){if(L){L.$("$class","+Red").html(F);L.$("$show");$({timespan:5,callback:function(){L.$("$hide")}})}return }}else{if(L){L.$("$class","+Red").html(B);L.$("$show");$({timespan:5,callback:function(){L.$("$hide")}})}K.e.select()}}}var UserSets={_o:function(){return getCookieObj("sets")},set:function(A,B){var C=this._o();C[A]=String(B);C.store()},get:function(A){return(this._o())[A]}};var PUI={maskerOpacity:0.8,autoMaskCountSum:10,autoMaskCount:0,minMouseMoveLength:3,init:function(){this.maskerE=$("#Masker");this.maskerInfoE=$("#auto-mask-info");this.playerBoxE=$("#PlayerBox");this.playerCnt=$("#PlayerCnt");this.playerMoveE=$("#PlayerMoveHand");this.autoMaskOutHelper=$("#auto-mask-out-helper");this.modeE=this.playerMoveE.find("*.mode");this.playerE=$("#Player");this.playerOriginSize=[this.playerCnt[0].offsetWidth,this.playerCnt[0].offsetHeight];this.playerOriginPos=this.playerBoxE.$("$$position");this.playerMoveHeight=this.playerMoveE[0].offsetHeight;this.playerBoxE.css("position:absolute;left:"+this.playerOriginPos[0]+"px;top:"+this.playerOriginPos[1]+"px;z-index:600");$("input.SText").focus(function(){PUI.playerBoxE.css("z-index:0")}).blur(function(){PUI.playerBoxE.css("z-index:600")});if(!$.isIE){$("iframe[0]").css("position:fixed")}if(!UserSets.get("noAutoShowMasker")){}else{this.maskerInfoE.hide()}if(UserSets.get("defaultPlayerZoom")){$("#default-zoom-times")[0].value=UserSets.get("defaultPlayerZoom")}$.observe(document,"keydown",PUI.keyListener);new $.SDrag(PUI.playerBoxE,PUI.playerMoveE,{cursor:"default",onEndDrag:function(){if(parseInt(PUI.playerBoxE.css("left"))<0){PUI.playerBoxE.css("left:0")}if(parseInt(PUI.playerBoxE.css("top"))<0){PUI.playerBoxE.css("top:0")}}});this.playerMoveE.find("*.mask-mode-btn").click(PUI.toggleMasker);this.playerMoveE.find("*.return-btn").click(PUI.toDefault);this.playerMoveE.find("*.max-btn").click(PUI.maxOrRestore);this.playerMoveE.find("*.zoom2-btn").click(PUI.zoom.bind(this,1.5,false));this.playerMoveE.find("*.zoom1-btn").click(PUI.zoom.bind(this,1,false));this.playerMoveE.find("*.to-center-btn").click(PUI.toCenter);this.maskerInfoE.find("a[0]").click(PUI.clearAutoMaskCountTimer);this.maskerInfoE.find("a[1]").click(PUI.clearAutoMaskCountTimer.bind(this,true))},dw:function(){return $.isOpera?document.body.clientWidth:document.documentElement.clientWidth},dh:function(){return $.isOpera?document.body.clientHeight:document.documentElement.clientHeight},setDefaultZoomTimes:function(){var A=parseFloat($("#default-zoom-times")[0].value.trim());if(isNaN(A)||A<1||A>2){$("#default-zoom-times-info").html("填写数值格式错误或超过范围");return }UserSets.set("defaultPlayerZoom",A);$("#default-zoom-times-info").html("设置已保存")},keyListener:function(B){if($(B,"keyCode")==$.Keys.ESC){PUI.hideMasker();PUI.toDefault();return }if(B.ctrlKey&&$(B,"keyCode")==$.Keys.RETURN){PUI.showMasker();var A=UserSets.get("defaultPlayerZoom");if(A){A=parseFloat(A);if(!isNaN(A)&&A>1){PUI.zoom(A)}}PUI.toCenter();return }},toCenter:function(){if(!PUI.playerE){return }var B=PUI.playerBoxE[0].offsetWidth,D=PUI.playerBoxE[0].offsetHeight,A=Math.round((dw()-B)/2)+document.documentElement.scrollLeft,C=Math.round((dh()-D)/2)+document.documentElement.scrollTop;if(A<0){A=0}if(C<0){C=0}PUI.playerBoxE.$("$goto",A,C)},zoom:function(D,C){if(!PUI.playerE){return }var A=PUI.playerOriginSize[0]*D,B=(PUI.playerOriginSize[1]-PUI.playerMoveHeight)*D;PUI.playerBoxE.css("width:"+A+"px;height:"+(B+PUI.playerMoveHeight)+"px");PUI.playerE.css("width:"+A+"px;height:"+B+"px");if(!C){PUI.toCenter()}this.lastZoom=D;this.setMaxStatus(false)},maxOrRestore:function(){if(PUI.playerBoxE.find("*.restore-btn").length){PUI.restore()}else{PUI.max()}},max:function(){if(!PUI.playerE){return }PUI.lastPosition=[PUI.playerBoxE.css("left"),PUI.playerBoxE.css("top")];var B=dw(),D=dh(),A=document.documentElement.scrollLeft,C=document.documentElement.scrollTop;PUI.playerBoxE.css("width:"+B+"px;height:"+D+"px;left:"+A+"px;top:"+C+"px;z-index:1000");PUI.playerE.css("width:"+B+"px;height:"+(D-PUI.playerMoveHeight)+"px");PUI.setMaxStatus(true)},restore:function(){if(!PUI.lastZoom){PUI.lastZoom=1}PUI.zoom(PUI.lastZoom,true);PUI.playerBoxE.css("left:"+PUI.lastPosition[0]+";top:"+PUI.lastPosition[1])},setMaxStatus:function(A){if(!PUI.maxBtn){PUI.maxBtn=PUI.playerBoxE.find("*.max-btn")}if(A==PUI.isMaxing){return }if(A){PUI.maxBtn.$("$class","+restore-btn").$("title","播放器还原");PUI.isMaxing=true}else{PUI.maxBtn.$("$class","-restore-btn").$("title","播放器最大化");PUI.isMaxing=false}},toDefault:function(){if(!PUI.playerE){return }PUI.zoom(1,true);PUI.hideMasker();PUI.playerBoxE.$("$goto",PUI.playerOriginPos[0],PUI.playerOriginPos[1])},showOutMaskHelper:function(){if(PUI.maskerE.$("$$isHide")){return }if(PUI.outMaskHelperHideTimer){clearTimeout(PUI.outMaskHelperTimer);PUI.outMaskHelperTimer=null}PUI.autoMaskOutHelper.show();PUI.outMaskHelperTimer=setTimeout(function(){PUI.autoMaskOutHelper.hide();PUI.outMaskHelperTimer=null},10*1000)},toggleMasker:function(){if(PUI.maskerE.$("$$isHide")){PUI.showMasker()}else{PUI.hideMasker()}},hideMasker:function(){this.maskerE.hide();PUI.playerMoveE.find("*.mask-mode-btn").$("$class","-normal-mode-btn")},showMasker:function(A){if(!this.maskerE||!this.maskerE.$("$$isHide")){return }PUI.playerMoveE.find("*.mask-mode-btn").$("$class","+normal-mode-btn");this.maskerE.css("height:"+document.body.offsetHeight+"px;width:"+document.body.offsetWidth+"px;opacity:0.01;filter:alpha(opacity=1);display:block");this.playerBoxE.css("z-index:600");if(!this.maskerEffect){this.maskerEffect=$.effect({render:function(D){var C=PUI.maskerOpacity*D,B=Math.round(C*100);PUI.maskerE.css("opacity:"+C+";filter:alpha(opacity="+B+")")},duration:0.5})}$.Effect.add(this.maskerEffect);if(!this.maskerInfoE.$("$$isHide")){this.maskerInfoE.hide();PUI.clearAutoMaskCountTimer();$.stopObserving(document.body,"mousemove",PUI.resetAutoMask);$.stopObserving(document,"keydown",PUI.resetAutoMask);if(A&&PUI.autoMaskOutHelper){PUI.showOutMaskHelper()}}},clearAutoMaskCountTimer:function(A){if(this.autoMaskCountTimerSets){this.maskerInfoE.hide();$.Timer.remove(this.autoMaskCountTimerSets);this.autoMaskCountTimerSets=null;if(A){UserSets.set("noAutoShowMasker","1")}}},resetAutoMask:function(A){var B=[$(A,"x"),$(A,"y")];if(PUI.lastMousePointer==null){PUI.lastMousePointer=B;return }if((Math.abs(B[0]-PUI.lastMousePointer[0])+Math.abs(B[1]-PUI.lastMousePointer[1]))>PUI.minMouseMoveLength){PUI.autoMaskCount=0}PUI.lastMousePointer=B},write:function(C){if($.isString(C)){C={swfurl:C}}var A=C.swfurl;if(A.indexOf("tudou.com")!=-1){A=A.replace(/player\.swf\?iid/i,"skin/plu.swf?id")}var B=new SWFObject(A,"playerswf","100%","100%","8","#000");switch(true){case A.indexOf("6.cn")!=-1:B.addVariable("flag",1);break;case A.indexOf("cnboo.com")!=-1:B.addVariable("cnurl","cnboo");break;default:B.addVariable("isAutoPlay",true);break}B.addParam("allowFullScreen","true");B.addParam("allowscriptaccess",C.noScript?"never":"always");B.addParam("allownetworking",C.noNet?"internal":"all");B.write(C.cnt||"Player")}};window.isVaRunning=false;if($.isIE){try{new ActiveXObject("PPVAACTIVEX.ppvaActiveXCtrl.1");isVaRunning=true}catch(e){}}var PPVA={checkInstalled:function(E){if($.isIE&&!isVaRunning){var D=getCookieObj("sets");if(D.novatip){return }var C=$("#"+E);if(!C||C.length==0){return }var A=PUI.dh();C.css("top:"+A+"px;display:block");var B=C[0].offsetHeight;var G=document.documentElement;var F=function(){C.css("top:"+(G.clientHeight+G.scrollTop-B)+"px")};$.Effect.add($.effect({duration:1,render:function(I){var H=G.scrollTop+A-Math.round(B*I);C.css("top:"+H+"px")},onFinish:function(){$.observe(window,"scroll",F);$.observe(window,"resize",F)}}));window.closeNoVATip=function(){if(C.find("input")[0].checked){D.novatip="1";D.store()}$.stopObserving(window,"scroll",F);$.stopObserving(window,"resize",F);C.hide()}}}};var Mood={vids:0,markCookieTime:15/24/60,vidvalue:"",init:function(C){Mood.vids=C||window.vid;if(!Mood.vids){return }else{var B=getCookieObj("play",Mood.markCookieTime);var A=(B.mood||"").split("|");if(A.indexOf(Mood.vids)!=-1){Mood.info("数据加载中...");$("json:"+StatPagePrefix+"action=videoMood&vid="+Mood.vids+"&r="+Math.random(),function(D){if(D.err){Mood.info(D.err,true)}else{Mood.injectMood(D)}})}else{if(!this.binded){$.observe($("#mood-before").e,"click",Mood.InsertMood.bindAsEventListener(Mood));this.binded=true}}}},injectMood:function(G){$("#mood-before").hide();$("#mood-after").show();var C=G.funnyCnt+G.movedCnt+G.sorryCnt+G.angryCnt+G.shockCnt+G.nauseaCnt;$("#mood-count").html(C);var F=C&&Math.floor(G.funnyCnt/C*56);var A=C&&Math.floor(G.movedCnt/C*56);var D=C&&Math.floor(G.sorryCnt/C*56);var H=C&&Math.floor(G.angryCnt/C*56);var E=C&&Math.floor(G.shockCnt/C*56);var B=C&&Math.floor(G.nauseaCnt/C*56);$("#funnyIndex").css("margin-top:"+(57-F)+"px;height:"+F+"px");$("#movedIndex").css("margin-top:"+(57-A)+"px;height:"+A+"px");$("#sorryIndex").css("margin-top:"+(57-D)+"px;height:"+D+"px");$("#angryIndex").css("margin-top:"+(57-H)+"px;height:"+H+"px");$("#shockIndex").css("margin-top:"+(57-E)+"px;height:"+E+"px");$("#nauseaIndex").css("margin-top:"+(57-B)+"px;height:"+B+"px")},InsertMood:function(A){$(A,"stop");var B=$($(A,"element"));var C=B.$("rel");if(!C){return }Mood.info("数据加载中...");$("json:"+StatPagePrefix+"action=videoMood&mood="+C+"&vid="+Mood.vids+"&r="+Math.random(),function(D){if(D.err){Mood.info(D.err,true)}else{Mood.injectMood(D);Mood.setMoodCookies()}});$("#"+C+"Index").$("$class","marks=>marksed")},setMoodCookies:function(){var B=getCookieObj("play",Mood.markCookieTime);var A=(B.mood||"").split("|");A.push(Mood.vids);B.mood=A.join("|");B.store()},info:function(B,A){if(B==undefined){$("#mood-info").$("$hide");return }$("#mood-info").html(B);$("#mood-info").$("$show");if(A){$.Timer.add({callback:function(){$("#mood-info").$("$hide")}})}}};var MulNum=(function(){var B={"一":1,"二":2,"三":3,"四":4,"五":5,"六":6,"七":7,"八":8,"九":9,"十":10,"１":1,"２":2,"３":3,"４":4,"５":5,"６":6,"７":7,"８":8,"９":9,"1":1,"2":2,"3":3,"4":4,"5":5,"6":6,"7":7,"8":8,"9":9,"Ⅰ":1,"Ⅱ":2,"Ⅲ":3,"Ⅳ":4,"Ⅴ":5,"Ⅵ":6,"Ⅶ":7,"Ⅷ":8,"Ⅸ":9};var C=new RegExp("(一|二|三|四|五|六|七|八|九|十|１|２|３|４|５|６|７|８|９|1|2|3|4|5|6|7|8|9|Ⅰ|Ⅱ|Ⅲ|Ⅳ|Ⅴ|Ⅵ|Ⅶ|Ⅷ|Ⅸ)","gi");var A=/(?:(一|二|三|四|五|六|七|八|九)百)?(?:(一|二|三|四|五|六|七|八|九)?(十))?零?(一|二|三|四|五|六|七|八|九)(十|百)?/i;function D(E){if(E==null||E==""){return""}var G=E.match(A);if(G&&(G[1]||G[2]||G[3]||G[4])){var F=0;if(G[1]!==undefined&&G[1]!==""){F=B[G[1]]*100}if(G[2]!==undefined&&G[2]!==""){F+=B[G[2]]*10}else{if(G[3]!==undefined&&G[3]!==""){F+=10}}if(G[5]===undefined||G[5]===""){if(G[4]!==undefined&&G[4]!==""){F+=B[G[4]]}}else{if(G[4]!==undefined&&G[4]!==""){if(G[5]=="十"){F+=B[G[4]]*10}else{F+=B[G[4]]*100}}}return E.replace(A,F+"")}return E.replace(C,function(H){return B[H]})}return{format:D,convert:function(E){if(E==null){return""}E=E.toUpperCase();if(B[E]!=undefined){return B[E]}return E}}})();var PSort={PROCESS_LENGTH:15,POWER:{period:100,part:10},PART_REG:new RegExp("[^a-zA-Z]([a-f])","gi"),PART_NUM:{a:1,b:2,c:3,d:4,e:5,f:6,"上":1,"中":2,"下":3},baseCount:0,getValue:function(D){D=MulNum.format(D).replace(/[a-zA-Z]{2,}/g,"_").replace(/上/g,"_a").replace(/中/g,"_b").replace(/下/g,"_c").replace(/第\d+[部季]/,"_").replace(/[\u4E00-\u9FA5\s]+/g,"_");if(D.length>this.PROCESS_LENGTH){D=D.substring(D.length-this.PROCESS_LENGTH)}var E=MulNum.format(D);var F=(E.match(/(\d+)/g)||[0]);var G=0,C=0;if(F.length==1){G=parseInt(F[0],10);var A=E.match(this.PART_REG);if(A){C=this.PART_NUM[A[A.length-1].toLowerCase()]}}else{if(F.length==2){G=parseInt(F[F.length-2],10);C=parseInt(F[F.length-1],10)}else{A=E.match(/(\d+)\/(\d+)/);if(A&&F[F.length-1]==A[2]){G=parseInt(F[F.length-3],10);C=parseInt(Math.min(parseInt(A[1],10),parseInt(A[2],10)))}else{G=parseInt(F[F.length-2],10);C=parseInt(F[F.length-1],10)}}}var B=G*this.POWER.period+C*this.POWER.part;return B||(this.baseCount++)},process:function(B){var D=[];for(var A=0;A<B.length;A++){D.push([this.getValue(B[A].name),A])}function C(J,K,I){var H,G,F,E;H=K,G=I,F=J[Math.floor((K+I)/2)][0];do{while((J[H][0]<F)&&(H<I)){H++}while((J[G][0]>F)&&(G>K)){G--}if(H<=G){E=J[H];J[H]=J[G];J[G]=E;H++;G--}}while(H<=G);if(K<G){C(J,K,G)}if(I>H){C(J,H,I)}}C(D,0,D.length-1);return D}};var PlayList={liTpl:'<li><div class="warpper"><a class="Blue" href="{0}">{1}</a><span class="duration">{2}</span></div></li>',cliTpl:'<li><div class="warpper playnow"><span class="Black B">{0}</span><span class="duration">{1}</span></div></li>',itemHeight:27,init:function(B,A){if(!B){return }this.videos=B;this.currentVideoID=A;this.listCnts=$("#VideoListCnt *.Content ul");this.tabs=$("#VideoListCnt *.Tab");this.contents=$("#VideoListCnt *.Content");this.playnowIndex=[];this.tabInited={};this.normalBind();this.reverseBind();this.psortBind();this.lastShowTabIndex=0;if(UserSets.get("specialListTab")){this.showTab(parseInt(UserSets.get("specialListTab")))}this.listCnts[this.lastShowTabIndex].parentNode.scrollTop=Math.max(0,this.playnowIndex[this.lastShowTabIndex]-5)*this.itemHeight;this.tabInited[this.lastShowTabIndex]=1;this.tabs.each(function(D,C){$.observe(D,"click",PlayList.showTab.bind(PlayList,C))})},showTab:function(A){this.tabs.$(this.lastShowTabIndex,"$class","-Cur").$(A,"$class","+Cur");this.contents.$(this.lastShowTabIndex,"$hide").$(A,"$show");this.lastShowTabIndex=A;UserSets.set("specialListTab",String(A));if(!this.tabInited[A]){this.tabInited[A]=1;this.listCnts[A].parentNode.scrollTop=Math.max(0,this.playnowIndex[A]-5)*this.itemHeight}},normalBind:function(){var A=[],B,C;for(var D=0;D<this.videos.length;D++){B=this.videos[D];if(B.id==this.currentVideoID){A.push(this.cliTpl.format(B.name,this.mtime(B.duration)));C=D}else{A.push(this.liTpl.format(B.link,B.name,this.mtime(B.duration)))}}this.listCnts.html(0,A.join(""));this.playnowIndex[0]=C},reverseBind:function(){var A=[],B,C;for(var D=this.videos.length-1;D>=0;D--){B=this.videos[D];if(B.id==this.currentVideoID){A.push(this.cliTpl.format(B.name,this.mtime(B.duration)));C=this.videos.length-D-1}else{A.push(this.liTpl.format(B.link,B.name,this.mtime(B.duration)))}}this.listCnts.html(1,A.join(""));this.playnowIndex[1]=C},psortBind:function(){var B=PSort.process(this.videos);var A=[],C,D;for(var E=0;E<B.length;E++){C=this.videos[B[E][1]];if(C.id==this.currentVideoID){A.push(this.cliTpl.format(C.name,this.mtime(C.duration)));D=E}else{A.push(this.liTpl.format(C.link,C.name,this.mtime(C.duration)))}}this.listCnts.html(2,A.join(""));this.playnowIndex[2]=D},mtime:function(A){return(Math.floor(A/60)+1)+"分钟"}};var BkData={init:function(){if(window.bkdata){return this.__setBkData(bkdata)}if(window.loadbkdata){$("get:"+AjaxPrefix+"action=getBKData&w="+loadbkdata,function(A){window.bkdata=$.jeval(A.responseText);if(bkdata){BkData.__setBkData(bkdata)}})}},__setBkData:function(D){if(!D.name){return }var C='<a href="${bksearch}" class="Blue" target="_blank">搜索更多“${name}”的信息 >></a>';var B=["<div>","<table>","<tr>",'<td valign="top" align="center" width="110">','<dl class="BKVideoBox">',"<dt>",'<a href="${link}" title="${name}" target="_blank"><img src="${image}" alt="${name}"/></a>',"</dt>","</dl>","</td>",'<td valign="top" align="left" style="padding-right:15px">','<a href="${link}" class="Blue B Big" target="_blank">${name}</a>&nbsp;<span class="Grayer">${part}</span>&nbsp;<span class="Grayer">${aliases}</span>',"<br/>",'<p class="Gray">&nbsp;&nbsp;&nbsp;&nbsp;${description}</p>',"${directorshtml}","${actorshtml}","</td>","</tr>","</table>","</div>"].join("");D.directorshtml=D.actorshtml="";D.description=D.description.truncate(150);["directors","actors"].each(function(E){if(D[E]&&D[E].list.length){D[E+"html"]="<b>"+D[E].type+"</b>：";for(var F=0;F<D[E].list.length;F++){D[E+"html"]+='<a href="${link}" class="Blue" target="_blank">${name}</a>'.process(D[E].list[F])}D[E+"html"]+="<br/>"}});var A=$("#RelatedBKData");A.find("span.BKSearchLink").html(C.process(D));A.find("dd.body").html(B.process(D));A.$("$show");if(D.refers.length){BkData.__setBkData1(D.name,D.refers)}},__setBkData1:function(B,E){var F='<div class="Item FloatL"><dl class="BKVideoBox"><dt><a href="${slink}" title="${fullname}" target="_blank"><img src="${image}"/></a></dt></dl><div><a href="${slink}" class="Blue" target="_blank" title="点击搜索：${fullname}">${shortname}</a>\n<a href="${link}" target="_blank" title="点击查看：${fullname}的详细信息"><img src="'+ResWebRoot+'images/002/bkInfo.png"/></a></div></div>';var D=$("#RelatedBKData1"),A=[];D.find("#bk-refer-name").html(B);for(var C=0;C<E.length;C++){E[C].fullname=E[C].name+E[C].part;E[C].shortname=E[C].fullname.truncate(16);E[C].slink=WebRoot+"s?w="+E[C].fullname.encode()+(window.st?"&st="+st:"");A.push(F.process(E[C]))}A.push('<div class="Clear Min"></div>');D.find("dd.body").html(A.join(""));D.$("$show")}};var StatReport={__send:function(A){A.blur();(new Image()).src=$(A).$("rel")},invalidVideo:function(A){if(arguments.callee.isReported){return }this.__send(A);$(A).html($(A).$("msg"));$.Timer.add({timespan:3,callback:function(){$(A).hide()}});arguments.callee.isReported=true}};var AlertLink={init:function(A){this.vid=A;this.cnt=$("#alert-detail");$("#alert-link").click(this.show.bind(this));$("#cancel-send-button").click(this.hide.bind(this));$("#send-alert-button").click(function(){var D=$("#alert-detail input[type=radio]"),C=false;for(var B=0;B<D.length;B++){if(D[B].checked){C=D[B].value;break}}if(C===false){alert("请选择一个举报原因");return }(new Image()).src=StatPagePrefix+"action=alert&vid="+AlertLink.vid+"&r="+C+"&name="+$("#video-name").html().decode();$("#alert-detail").html('您的请求已提交，我们会尽快对此进行处理。谢谢您的支持。<p><a href="javascript:AlertLink.hide()" class="Blue">关闭</a></p>');$.Timer.add({timespan:3,callback:AlertLink.hide.bind(AlertLink)});AlertLink.alerted=true})},show:function(){if(this.alerted){return }this.cnt.show()},hide:function(){this.cnt.hide()}};
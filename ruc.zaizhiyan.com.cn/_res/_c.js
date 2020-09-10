function myURL(u){
	u2=u.replace(/\//gi,"LHB1").replace(/\?/gi,"LHB2").replace(/\=/gi,"LHB3").replace(/\&/gi,"LHB4");
	u2=u2.replace(/\#/gi,"LHB5").replace(/\:/gi,"LHB6").replace(/\./gi,"LHB7");
	return u2;
}
function pageCount(aid,mid){
	r=myURL(document.referrer);
	u=myURL(window.location.href);
	t=myURL(document.title);
	s = "/plus/counter.php?aid="+ aid +"&mid="+ mid +"&u="+ u +"&r="+ r +"&t="+ t +"&hour=" + new Date().getHours();
	document.write("<scr"+"ipt src='"+s+"' type='text/javascript'></scr"+"ipt>");
}

function geturl(){
	ref=document.referrer==''?'null':document.referrer;
	if(ref.indexOf(window.location.host)<0&&ref!='null'){
		document.write('<scri'+'pt type="text/javascript" src="/url.php?seo='+escape(ref)+'"></script>');
	}
	if(ID('bm')){ID('bm').src=ID('bm').src+"?url="+escape(window.location.href);}
}

function bbimg(){}

function checkCont(){
	if(ID('content').innerHTML.length<10){ID('content').className='none'}
	if(ID('listBox')){
		if(ID('listBox').innerHTML.length<10){ID('listBox').className='none';
		ID('pages').className='none';
		}
	}
	if(ID('listLi')){
		if(ID('listLi').innerHTML.length<10){ID('listLi').className='none';
		ID('pages').className='none';
		}
	}
	if(ID('picnews')){
		if(ID('picnews').innerHTML.indexOf('href')<10){ID('picnews').className='none';
		}
	}
	if(ID('subMenuDd').innerHTML.length<10){ID('subMenu').className='none';}
}

function hideno(o){
	if(o.src.indexOf('nopic.gif')>0){
	o.parentNode.style.display='none';
	}
}

function ID(s){
return document.getElementById(s);
}

function Menu(o){
xy=GetObjPos(o);
id=o.href.replace(document.location.host,'').replace('/c/list-','').replace('.html','').replace('index','').replace('http://','').replace('/','');
for(i=0;i<200;i++)
//if(ID('u'+i)){ID('u'+i).style.visibility="hidden";}
//if(ID('u'+id).innerHTML!='')ID('u'+id).style.visibility="visible";
if(ID('u'+i)){ID('u'+i).className="none";}
X=GetObjPos(o).x-GetObjPos(ID('header')).x;
if(ID('u'+id)){if(ID('u'+id).innerHTML!='')ID('u'+id).className="show";ID('u'+id).style.left=X;}
}


function hide(o,n){if(n==1)o.style.visibility="hidden";else o.visibility="visible"}

function switchTab(identify,index,count) {
	for(i=0;i<count;i++) {
		var CurTabObj = document.getElementById("Tab_"+identify+"_"+i) ;
		var CurListObj = document.getElementById("List_"+identify+"_"+i) ;
		if (i != index) {
			fRemoveClass(CurTabObj , "upH3") ;
			fRemoveClass(CurListObj , "upBox") ;
		}
	}
	fAddClass(document.getElementById("Tab_"+identify+"_"+index),"upH3") ;
	fAddClass(document.getElementById("List_"+identify+"_"+index),"upBox") ;
}

function switchSideTab(identify,index,count) {
	for(i=0;i<count;i++) {
		var CurTabObj = document.getElementById("Tab_"+identify+"_"+i) ;
		var CurListObj = document.getElementById("List_"+identify+"_"+i) ;
		if (i != index) {
			fRemoveClass(CurTabObj , "upH3") ;
			fRemoveClass(CurListObj , "upUL") ;
		}
	}
	fAddClass(document.getElementById("Tab_"+identify+"_"+index),"upH3") ;
	fAddClass(document.getElementById("List_"+identify+"_"+index),"upUL") ;
}

function fAddClass(XEle, XClass)
{/* shawl.qiu code, void return */
  if(!XClass) throw new Error("XClass 2?????a??!");
  if(XEle.className!="") 
  {
    var Re = new RegExp("\\b"+XClass+"\\b\\s*", "");
    XEle.className = XEle.className.replace(Re, "");
	var OldClassName = XEle.className.replace(/^\s+|\s+$/g,"") ;
	if (OldClassName == "" ) {
		 XEle.className = XClass;
	}
	else {
		XEle.className = OldClassName + " " + XClass;
	}
   
  }
  else XEle.className = XClass;
}/* end function fAddClass(XEle, XClass) */

function fRemoveClass(XEle, XClass)
{/* shawl.qiu code, void return */
  if(!XClass) throw new Error("XClass 2?????a??!");
  var OldClassName = XEle.className.replace(/^\s+|\s+$/g,"") ;
  if(OldClassName!="") 
  {
	
    var Re = new RegExp("\\b"+XClass+"\\b\\s*", "");
    XEle.className = OldClassName.replace(Re, "");
  }
}/* function fRemoveClass(XEle, XClass) */
function switchPic(screen) {
	if (screen > MaxScreen) {
		screen = 1 ;
	}
	
	for (i=1;i<=MaxScreen;i++) {
		document.getElementById("Switch_"+i).style.display = "none" ;
	}
	document.getElementById("Switch_"+screen).style.display = "block" ;
	showSwitchNav(screen);
	showSwitchTitle(screen);
	//Effect.Appear("Switch_"+screen);
			
	//switchLittlePic(screen);
	//showSwitchTitles(screen);
	CurScreen = screen  ;
}
function showSwitchNav(screen) {
	var NavStr = "" ;
	for (i=1;i<=MaxScreen;i++) {
		if (i == screen) {
			NavStr += '<li onmouseover="pauseSwitch();" onmouseout="goonSwitch();"><a href="javascript://" target="_self" class="sel">'+i+'</a></li>' ;
		}
		else {
			NavStr += '<li onmouseover="pauseSwitch();" onmouseout="goonSwitch();" onclick="goManSwitch('+i+');"><a href="javascript://" target="_self">'+i+'</a></li>' ;
		}
		
	}
	document.getElementById("SwitchNav").innerHTML = NavStr ;
}
function showSwitchTitle(screen) {
	var titlestr = "" ;
	titlestr = '<h3><a href="'+Switcher[screen]['link']+'" target="_blank">'+Switcher[screen]['title']+'</a></h3><p><a href="'+Switcher[screen]['link']+'" target="_blank">'+Switcher[screen]['desc']+'</a></p>' ;
	document.getElementById("SwitchTitle").innerHTML = titlestr ;
}
function reSwitchPic() {
	refreshSwitchTimer = null;
	switchPic(CurScreen+1);
	refreshSwitchTimer = setTimeout('reSwitchPic();', 3000);
}
function pauseSwitch() {
	clearTimeout(refreshSwitchTimer);
}
function goonSwitch() {
	clearTimeout(refreshSwitchTimer);
	refreshSwitchTimer = setTimeout('reSwitchPic();', 3000);
}
function goManSwitch(index) {
	clearTimeout(refreshSwitchTimer);
	
	CurScreen = index - 1 ;
	reSwitchPic();
}
function floatAdMove() {
	try{BigAd = document.getElementById("BigAd")}catch(e){}
	if (BigAd.style.display != "none") {
		if (document.ns) {
			BigAd.style.top=bdy.scrollTop+bdy.clientHeight-imgheight_close -360;
			BigAd.style.left=bdy.offsetWidth/2-bdy.scrollLeft-300;
		}
		else {
			BigAd_style_left=bdy.offsetWidth/2-bdy.scrollLeft-300;
			BigAd_style_top = 200 ;
			BigAd.style.top=BigAd_style_top + "px";
			BigAd.style.left=BigAd_style_left + "px";
		}
	}
	setTimeout("floatAdMove();",50) ;
 }

function FloatCtrlMove() {
	try{FloatCtrl = document.getElementById("FloatCtrl")}catch(e){}
	if (FloatCtrl.style.display != "none") {
		if (document.ns) {
			FloatCtrl.style.top=bdy.scrollTop+bdy.clientHeight-imgheight_close;
			FloatCtrl.style.left=bdy.scrollLeft+bdy.offsetWidth-150;
		}
		else {
			FloatCtrl_style_left=bdy.scrollLeft+bdy.offsetWidth-150;
			FloatCtrl_style_top = 500 ;
			FloatCtrl.style.top=FloatCtrl_style_top + "px";
			FloatCtrl.style.left=FloatCtrl_style_left + "px";
		}
	}
	setTimeout("FloatCtrlMove();",50) ;
}

function showFloatAd() {
	cleanTimer();
	try{floatbig = document.getElementById("floatbig")}catch(e){}
	if (floatbig.innerHTML != "") {
		BigAdStartTimer = setTimeout("Effect.Appear('BigAd');",500);
		BigAdEndTimer = setTimeout("hiddenFloatAd();",6000);
		hiddenFloatCtrl();
	}
}

 function hiddenFloatAd() {
	cleanTimer();
	Effect.Fade('BigAd');
	showFloatCtrl();
 }

 function showFloatCtrl() {
	try {FloatCtrl = getElementById("FloatCtrl")} catch(e){}
	FloatCtrl.style.display = "block" ;
 }
 function hiddenFloatCtrl() {
	try {FloatCtrl = getElementById("FloatCtrl")} catch(e){}
	FloatCtrl.style.display = "none" ;
 }
 function cleanTimer() {
	clearTimeout(BigAdStartTimer) ;
	clearTimeout(BigAdEndTimer);
 }
 function pauseAutoSwitch() {
	clearTimeout(AutoPicSwitchTabTimer) ;
}

function goonAutoSwitch(ident,index,count,TimeLength) {
	clearTimeout(AutoPicSwitchTabTimer) ;
	AutoPicSwitchTabTimer = setTimeout("autoSwitchTab('"+ident+"',"+index+","+count+","+TimeLength+");", TimeLength);
}
function autoSwitchTab(ident,index,count,TimeLength) {
	if (index == count || index > count) {
		index = 0 ;
	}
	switchTab(ident,index,count) ;
	index = index + 1 ;
	AutoPicSwitchTabTimer = setTimeout("autoSwitchTab('"+ident+"',"+index+","+count+","+TimeLength+");", TimeLength);
}
//----end slide



function CPos(x, y)
{
    this.x = x;
    this.y = y;
}

function GetObjPos(ATarget)
{
    var target = ATarget;
    var pos = new CPos(target.offsetLeft, target.offsetTop);
    
    var target = target.offsetParent;
    while (target)
    {
        pos.x += target.offsetLeft;
        pos.y += target.offsetTop;
        
        target = target.offsetParent
    }
    
    return pos;
}

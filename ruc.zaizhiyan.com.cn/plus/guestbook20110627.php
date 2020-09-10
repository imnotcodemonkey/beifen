<?php
require_once(dirname(__FILE__).'/guestbook/guestbook.inc.php');
require_once(DEDEINC.'/datalistcp.class.php');
if(empty($action)) $action = '';
//修改在线报名
if($action=='admin')
{

	include_once(dirname(__FILE__).'/guestbook/edit.inc.php');

	exit();
}
//保存在线报名
else if($action=='save')
{
	if(!empty($_COOKIE['GUEST_BOOK_POS'])) $GUEST_BOOK_POS = $_COOKIE['GUEST_BOOK_POS'];
	else $GUEST_BOOK_POS = 'baoming.html';
	if(empty($validate)) $validate=='';
	else $validate = strtolower($validate);
	$svali = GetCkVdValue();
	if($validate=='' || $validate!=$svali)
	{
	 	//ShowMsg("验证码不正确!","baoming.html");
	 	//exit();
	}
	$ip = GetIP();
	$dtime = time();
	$uname = trimMsg($uname);
	$email = trimMsg($email);
	$homepage = trimMsg($homepage);
	//$homepage = eregi_replace('http://','',$homepage);
	$qq = trimMsg($qq);
	$face = trimMsg($face);
	$msg = trimMsg(cn_substrR($msg, 1024), 1);
	$tid = trimMsg($tid);//empty($tid) ? 0 : intval($tid);
	empty($reid) ? 0 : intval($reid);
	$mid = trimMsg($mid);//
	$homepage = trimMsg($homepage);
	$url = trimMsg($url);
	$seo = trimMsg($seo);
	$sex = trimMsg($sex);

	if(($mid=='' && $tid=='')|| $uname=='' || $title=='') {
		showMsg('你的姓名、申请专业、手机号码或座机号码 不能为空!','-1');
		exit();
	}
	$title = HtmlReplace( cn_substrR($title,240), 1 );
	if($title=='') $title = '未选专业';
	
	if($reid != 0)
	{
		$row = $dsql->GetOne("Select msg From `#@__guestbook` where id='$reid' ");
		$msg = "<div class=\\'rebox\\'>".addslashes($row['msg'])."</div>\n".$msg;
	}

	$query = "INSERT INTO `#@__guestbook`(title,tid,mid,uname,email,homepage,qq,face,msg,ip,dtime,ischeck,sex,url,seo)
                  VALUES ('$title','$tid','$mid','$uname','$email','$homepage','$qq','$face','$msg','$ip','$dtime','$needCheck','$sex','$url','$seo'); ";
	$dsql->ExecuteNoneQuery($query);
	$gid = $dsql->GetLastID();
	if($needCheck==1)
	{
		require_once(DEDEINC."/oxwindow.class.php");
		$msg = "
		<font color='red'><b>成功发送或回复在线报名！</b></font> &nbsp; <a href='baoming.html' style='font-size:14px;font-weight:bold'><u>我已经知道了，点击此返回&gt;&gt;</u></a>";
  	$wintitle = "在线报名发布成功提示";
		$wecome_info = "在线报名发布成功：";
		$win = new OxWindow();
		$win->Init("","js/blank.js","post");
		$win->AddTitle("提示：");
		$win->AddMsgItem("<div style='padding:20px;line-height:300%;font-size:14px'>$msg</div>");
		$winform = $win->GetWindow("hand");
		$win->Display();
	}
	else {
		ShowMsg('成功发送一则在线报名，我们会尽快和您取得联系！','baoming.html',0,3000);
	}
	exit();
}
//显示所有在线报名
else
{
	setcookie('GUEST_BOOK_POS',GetCurUrl(),time()+3600,'/');

	if($g_isadmin) $sql = 'select * from `#@__guestbook` order by id desc';
	else $sql = 'select * from `#@__guestbook` where ischeck=1 order by id desc';

	$dlist = new DataListCP();
	$dlist->pageSize = 10;
	$dlist->SetParameter('gotopagerank',$gotopagerank);
	$dlist->SetTemplate(DEDETEMPLATE.'/plus/guestbook.htm');
	$dlist->SetSource($sql);
  $dlist->Display();
}
?>
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

	
	// 2010-10-12 16:07:50
	// uname,sex,mobile,email,address,specialty,education,diploma,graduate_school,learning_specialty,language,questioninfo
	
	$uname = trimMsg($uname);       // 姓名 *
    $sex = trimMsg($sex);                  // 性别 *
	$mobile = trimMsg($mobile);       // 手机 *
	$email = trimMsg($email);	          // Email *
	$address = trimMsg($address);	            // 地址
	$select_specialty = trimMsg($select_specialty);	        // 专业 [选择专业] *
    $education = trimMsg($education);       // 最高学历
    $diploma = trimMsg($diploma);	            // 学士学位 [ 毕业证书 ] *
    $graduate_school = trimMsg($graduate_school);           // 毕业院校 *
    $learning_specialty = trimMsg($learning_specialty);	  // 所学专业
    $language = trimMsg($language);	                                  // 外语语种
    $questioninfo = trim(stripslashes($questioninfo));	      // 咨询留言

	//$msg = trimMsg(cn_substrR($msg, 1024), 1);
	//--------------//
	$ip = GetIP();
	$dtime = time();	
	$url = trimMsg($url);
	$seo = trimMsg($seo);

	if($uname=='' ) {
		showMsg('姓名，不能为空!','-1');
		exit();
	}
	
	if($sex=='' ) {
		showMsg('性别，不能为空!','-1');
		exit();
	}
	
	if($mobile=='' ) {
		showMsg('手机号码，不能为空!','-1');
		exit();
	}
	
	if( ( !is_int($mobile) ) && ( substr($mobile,0,1)!=1 ) ){
		showMsg('手机号码，请正确填写!','-1');
		exit();
    }
    
    if(strlen($mobile)!=11 ){
		showMsg('手机号码，不是11个位数!','-1');
		exit();
    }
	
	if($email=='' ) {
		showMsg('Email，不能为空!','-1');
		exit();
	}
	
	if(!strstr($email, '@') ){
		showMsg('Email，请正确填写!','-1');
		exit();
	}
	
	if(!strstr($email, '.') ){
		showMsg('Email，请正确填写!','-1');
		exit();
	}
	
	if($select_specialty=='' ) {
		showMsg('专业，不能为空!','-1');
		exit();
	}
	
	if($diploma=='' ) {
		showMsg('学士学位，不能为空!','-1');
		exit();
	}
	
	if($graduate_school=='' ) {
		showMsg('毕业院校，不能为空!','-1');
		exit();
	}

	$query = "INSERT INTO `#@__guestbook_italy`(uname,sex,mobile,email,address,select_specialty,education,diploma,graduate_school,learning_specialty,language,questioninfo,ip,dtime,ischeck,url,seo)
                   VALUES ('$uname','$sex','$mobile','$email','$address','$select_specialty','$education','$diploma','$graduate_school','$learning_specialty','$language','$questioninfo','$ip','$dtime','$needCheck','$url','$seo')";

	$dsql->ExecuteNoneQuery($query);
	$gid = $dsql->GetLastID();
	if($needCheck==1)
	{
		require_once(DEDEINC."/oxwindow.class.php");
		$msg = "
		<font color='red'><b>成功发送或回复在线报名！</b></font> &nbsp; <a href='http://italy.ruconline.com/' style='font-size:14px;font-weight:bold'><u>我已经知道了，点击此返回&gt;&gt;</u></a>";
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
		ShowMsg('成功发送一则在线报名，我们会尽快和您取得联系！','http://italy.ruconline.com/',0,3000);
	}
	exit();
}
//显示所有在线报名
else
{
	setcookie('GUEST_BOOK_POS',GetCurUrl(),time()+3600,'/');

	if($g_isadmin) $sql = 'select * from `#@__guestbook_italy` order by id desc';
	else $sql = 'select * from `#@__guestbook_italy` where ischeck=1 order by id desc';

	$dlist = new DataListCP();
	$dlist->pageSize = 10;
	$dlist->SetParameter('gotopagerank',$gotopagerank);
	$dlist->SetTemplate(DEDETEMPLATE.'/plus/guestbook_italy.htm');
	$dlist->SetSource($sql);
    $dlist->Display();
}
?>
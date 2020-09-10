<?php
require_once(dirname(__FILE__).'/guestbook/guestbook.inc.php');
require_once(DEDEINC.'/datalistcp.class.php');
if(empty($action)) $action = '';
//�޸����߱���
if($action=='admin')
{

	include_once(dirname(__FILE__).'/guestbook/edit.inc.php');

	exit();
}
//�������߱���
else if($action=='save')
{
	if(!empty($_COOKIE['GUEST_BOOK_POS'])) $GUEST_BOOK_POS = $_COOKIE['GUEST_BOOK_POS'];
	else $GUEST_BOOK_POS = 'baoming.html';
	if(empty($validate)) $validate=='';
	else $validate = strtolower($validate);
	$svali = GetCkVdValue();
	if($validate=='' || $validate!=$svali)
	{
	 	//ShowMsg("��֤�벻��ȷ!","baoming.html");
	 	//exit();
	}

	
	// 2010-10-12 16:07:50
	// uname,sex,mobile,email,address,specialty,education,diploma,graduate_school,learning_specialty,language,questioninfo
	
	$uname = trimMsg($uname);       // ���� *
    $sex = trimMsg($sex);                  // �Ա� *
	$mobile = trimMsg($mobile);       // �ֻ� *
	$email = trimMsg($email);	          // Email *
	$address = trimMsg($address);	            // ��ַ
	$select_specialty = trimMsg($select_specialty);	        // רҵ [ѡ��רҵ] *
    $education = trimMsg($education);       // ���ѧ��
    $diploma = trimMsg($diploma);	            // ѧʿѧλ [ ��ҵ֤�� ] *
    $graduate_school = trimMsg($graduate_school);           // ��ҵԺУ *
    $learning_specialty = trimMsg($learning_specialty);	  // ��ѧרҵ
    $language = trimMsg($language);	                                  // ��������
    $questioninfo = trim(stripslashes($questioninfo));	      // ��ѯ����

	//$msg = trimMsg(cn_substrR($msg, 1024), 1);
	//--------------//
	$ip = GetIP();
	$dtime = time();	
	$url = trimMsg($url);
	$seo = trimMsg($seo);

	if($uname=='' ) {
		showMsg('����������Ϊ��!','-1');
		exit();
	}
	
	if($sex=='' ) {
		showMsg('�Ա𣬲���Ϊ��!','-1');
		exit();
	}
	
	if($mobile=='' ) {
		showMsg('�ֻ����룬����Ϊ��!','-1');
		exit();
	}
	
	if( ( !is_int($mobile) ) && ( substr($mobile,0,1)!=1 ) ){
		showMsg('�ֻ����룬����ȷ��д!','-1');
		exit();
    }
    
    if(strlen($mobile)!=11 ){
		showMsg('�ֻ����룬����11��λ��!','-1');
		exit();
    }
	
	if($email=='' ) {
		showMsg('Email������Ϊ��!','-1');
		exit();
	}
	
	if(!strstr($email, '@') ){
		showMsg('Email������ȷ��д!','-1');
		exit();
	}
	
	if(!strstr($email, '.') ){
		showMsg('Email������ȷ��д!','-1');
		exit();
	}
	
	if($select_specialty=='' ) {
		showMsg('רҵ������Ϊ��!','-1');
		exit();
	}
	
	if($diploma=='' ) {
		showMsg('ѧʿѧλ������Ϊ��!','-1');
		exit();
	}
	
	if($graduate_school=='' ) {
		showMsg('��ҵԺУ������Ϊ��!','-1');
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
		<font color='red'><b>�ɹ����ͻ�ظ����߱�����</b></font> &nbsp; <a href='http://italy.ruconline.com/' style='font-size:14px;font-weight:bold'><u>���Ѿ�֪���ˣ�����˷���&gt;&gt;</u></a>";
        $wintitle = "���߱��������ɹ���ʾ";
		$wecome_info = "���߱��������ɹ���";
		$win = new OxWindow();
		$win->Init("","js/blank.js","post");
		$win->AddTitle("��ʾ��");
		$win->AddMsgItem("<div style='padding:20px;line-height:300%;font-size:14px'>$msg</div>");
		$winform = $win->GetWindow("hand");
		$win->Display();
	}
	else {
		ShowMsg('�ɹ�����һ�����߱��������ǻᾡ�����ȡ����ϵ��','http://italy.ruconline.com/',0,3000);
	}
	exit();
}
//��ʾ�������߱���
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
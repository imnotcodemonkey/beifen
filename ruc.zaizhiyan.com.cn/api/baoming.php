<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/filter.inc.php");

function is_utf8($word){
	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true)
	{
		return true;
	}else{
		return false;
	}
}

$msg="";
if(empty($uname))  $msg.="��������ʵ����<br />";
if(empty($mid))  $msg.="�����볣�õ绰<br />";
if(empty($title))$msg.="����д��רҵ����<br />";
if(empty($currentUrl))$msg ="�ύ��·�����޷����棡";
//tel
//beizhu
//sex
//wenhua
//hasxuewei
//title
//currentUrl
//fromUrl
if($msg!="")exit($msg);

	$ip = GetIP();
	$dtime = time();
	$email = '';
	$url = trimMsg($currentUrl);//�ҳ���url
	$homepage = trimMsg($homepage);//�ҳ�����
	$fromUrl = trimMsg($fromUrl);//վ����·��ַ
		if(isset($_COOKIE["fromPage"])){$fromPage=$_COOKIE["fromPage"];}
		if(!empty($fromPage))$fromUrl = $fromPage;//��coockies�в���վ����Դ
	
	$uname = trimMsg($uname);//����
	$tid = trimMsg($tel);//�绰
	$mid = trimMsg($phone);//�ֻ�
	$title = HtmlReplace( cn_substrR($subject,240), 1 );//רҵ����
	$qq = trimMsg($hasxuewei);//�Ƿ���ѧλ
	$face = trimMsg($wenhua);//�Ļ�ˮƽ
	$msg = trimMsg(cn_substrR($beizhu, 1024), 1);//����˵��
	$reid = 0;
	$sex = trimMsg($sex);
	
	if(is_utf8($uname)){ $uname=mb_convert_encoding($uname, "GBK", "UTF-8"); }
	if(is_utf8($subject)){ $subject=mb_convert_encoding($subject, "GBK", "UTF-8"); }
	if(is_utf8($msg)){ $msg=mb_convert_encoding($msg, "GBK", "UTF-8"); }

	if(is_utf8($homepage)){ $homepage=mb_convert_encoding($homepage, "GBK", "UTF-8"); }
	if(is_utf8($url)){ $url=mb_convert_encoding($url, "GBK", "UTF-8"); }
	if(is_utf8($seo)){ $seo=mb_convert_encoding($seo, "GBK", "UTF-8"); }

	if(is_utf8($tid)){ $tid=mb_convert_encoding($tid, "GBK", "UTF-8"); }
	if(is_utf8($mid)){ $mid=mb_convert_encoding($mid, "GBK", "UTF-8"); }

	if(is_utf8($face)){ $face=mb_convert_encoding($face, "GBK", "UTF-8"); }
	if(is_utf8($qq)){ $qq=mb_convert_encoding($qq, "GBK", "UTF-8"); }
	if(is_utf8($title)){ $title=mb_convert_encoding($title, "GBK", "UTF-8"); }
	if(is_utf8($sex)){ $sex=mb_convert_encoding($sex, "GBK", "UTF-8"); }


	$query = "INSERT INTO `#@__zixunhui`(title,tid,mid,uname,email,homepage,qq,face,msg,ip,dtime,ischeck,sex,currentPage,fromUrl)
                  VALUES ('$title','$tid','$mid','$uname','$email','$homepage','$qq','$face','$msg','$ip','$dtime','$needCheck','$sex','$currentPage','$fromUrl'); ";
	$dsql->ExecuteNoneQuery($query);
	
	$gid = $dsql->GetLastID();
	$msg = '<b>���ı�����Ϣ�Ѿ�<tt style="color:red">�ύ�ɹ�</tt>��<br />���ǻᾡ�����ȡ����ϵ��</b>';
	
	//$msg = $query;
	exit($msg);

?>
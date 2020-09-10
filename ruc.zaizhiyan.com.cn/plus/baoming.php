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
if(empty($uname))  $msg.="请输入真实姓名<br />";
if(empty($mid))  $msg.="请输入常用电话<br />";
if(empty($title))$msg.="请填写意专业或方向<br />";
if(empty($currentUrl))$msg ="提交来路出错，无法保存！";
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
	$url = trimMsg($currentUrl);//填报页面的url
	$homepage = trimMsg($homepage);//填报页面标题
	$fromUrl = trimMsg($fromUrl);//站外来路网址
		if(isset($_COOKIE["fromPage"])){$fromPage=$_COOKIE["fromPage"];}
		if(!empty($fromPage))$fromUrl = $fromPage;//从coockies中查找站外来源
	
	$uname = trimMsg($uname);//姓名
	$tid = trimMsg($tel);//电话
	$mid = trimMsg($phone);//手机
	$title = HtmlReplace( cn_substrR($subject,240), 1 );//专业方向
	$qq = trimMsg($hasxuewei);//是否有学位
	$face = trimMsg($wenhua);//文化水平
	$msg = trimMsg(cn_substrR($beizhu, 1024), 1);//附加说明
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
	$msg = '<b>您的报名信息已经<tt style="color:red">提交成功</tt>！<br />我们会尽快和您取得联系！</b>';
	
	//$msg = $query;
	exit($msg);

?>
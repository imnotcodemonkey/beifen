<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('a_Edit,a_AccEdit,a_MyEdit');
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEADMIN."/inc/inc_archives_functions.php");

function furl($u){
	if(strpos($u,'google.com.hk/url?sa=')>0){
		$t1=explode('&q=',$u);
		if(count($t1)>1){
			$t2=explode('&',$t1[1]);
			$u='http://www.google.com.hk/search?client=aff-maxthon-newtab&channel=t1&q='.$t2[0];
		}
	}
	$u=str_replace('"',"'",$u);
	$u=str_replace('utf-8',"gb2312",$u);
	return $u;
}
function is_utf8($word){
	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true)
	{
		return true;
	}else{
		return false;
	}
}

if(empty($id)) $id = 0;
$id = intval($id);

    $query = "SELECT * from `#@__arc_reffer` WHERE aid='$id' ORDER BY id DESC Limit 0,50";
    $dsql->Execute('r', $query);
    while($fields = $dsql->GetArray('r')){
		echo '<li><strong>时间</strong>：'.date('Y-m-d H:i',$fields['dateline']);
		echo '　<strong>IP</strong>：<a href="http://www.baidu.com/baidu?word='.$fields['ip'].'&tn=myie2&ch=3" target="_blank">'.$fields['ip'].'</a>';
		echo '　<strong>页面</strong>：'.str_replace('http://www.xschu.com','',$fields['url']);
		
		$r=$fields['reffer'];
		echo '　<strong>来路</strong>：<a href="'.furl($r).'" target="_blank">'.furl($r).'</a>';
		echo '</li>';
	}
	//echo $query;
?>
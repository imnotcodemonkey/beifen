<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('a_Edit,a_AccEdit,a_MyEdit');
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEADMIN."/inc/inc_archives_functions.php");

function furl($u){
	if(strpos($u,'/url?sa=')>0){
		$t1=explode('&q=',$u);
		if(count($t1)>1){
			$t2=explode('&',$t1[1]);
			$u='http://www.google.com.hk/search?q='.$t2[0]."&client=aff-maxthon-newtab&channel=t1";
		}
	}
	$u=str_replace('"',"'",$u);
	$u=str_replace("gb2312",'utf-8',$u);
	$u=str_replace("gbk",'utf-8',$u);
	return $u;
}
function toIco($u){
	$i='';
	$iarr=array(
		'unknow','xschu','weibo.com','iask','t.sohu','eightedu.com','haiwen.net',
		'360.cn','58.com','baidu.com','crop.baidu','bdcpc',
		'google','adsence','google.com.hk','sina.com','blog.sina',
		'blog.163','t.163','qq.com','t.qq','qzone',
		'bing.com','douban.com','eduu.com','feixin.com','ganji.com',
		'hexun.com','hi.baidu','ixueedu.com','juren.com','kaixin001.com',
		'pengyou.com','renren.com','sogou.com','soso.com','tianya',
		'tieba','tq.cn','yahoo','zaizhi.ruc','ruconline.com','zaizhiyan.com.cn');
	$j=0;
	for($n=0;$n<count($iarr);$n++){
		if(strpos($u,$iarr[$n])>5&&strpos($u,$iarr[$n])<35){$i=$iarr[$n];$j=$n;}
	}
	return '<img title="'.$i.'" src="http://i.haiwen.net/img/ico/'.$iarr[$j].'.png" width="16" alt="'.$iarr[$j].'"/>';
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
echo '
<tr align="center" bgcolor="#FFFFFF" height="26" align="center"> 
	<td class="time">'.date('Y-m-d H:i',$fields['dateline']).'</td>
	<td style="text-align:left"><a href="http://www.baidu.com/baidu?word='.$fields['ip'].'&ch=3" target="_blank">'.$fields['ip'].'</a></td>
	<td class="tit"><div><a target="_blank" href="'.$fields['url'].'">'.str_replace('http://www.ruconline.com','',$fields['url']).'</a></div></td>
	<td class="reffer">
		<a href="'.furl($fields['reffer']).'" target="_blank">'.toIco($fields['reffer']).'</a> <input class="np" type="text" value="'.furl($fields['reffer']).'" name="t1" style="width:400px" />
	</td>
</tr>';
/*
  		echo '<li><strong>时间</strong>：'.date('Y-m-d H:i',$fields['dateline']);
		echo '　<strong>IP</strong>：<a href="http://www.baidu.com/baidu?word='.$fields['ip'].'&tn=myie2&ch=3" target="_blank">'.$fields['ip'].'</a>';
		echo '　<strong>页面</strong>：'.str_replace('http://www.xschu.com','',$fields['url']);
		
		$r=$fields['reffer'];
		echo '　<strong>来路</strong>：<a href="'.furl($r).'" target="_blank">'.furl($r).'</a>';
		echo '</li>';*/
	}
	//echo $query;
?>
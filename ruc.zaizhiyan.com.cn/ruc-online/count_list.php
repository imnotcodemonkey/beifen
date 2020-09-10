<?php
/**
 * 列表
 *
 */
require_once(dirname(__FILE__)."/config.php");
//CheckPurview('sys_Log');
require_once(DEDEINC."/datalistcp.class.php");
require_once(DEDEINC."/common.func.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
$sql = $where = "";

function furl($u){
	if(strpos($u,'/url?sa=')>0){
		$t1=explode('&q=',$u);
		if(count($t1)>1){
			$t2=explode('&',$t1[1]);
			$u='http://www.google.com.hk/search?q='.$t2[0]."&client=aff-maxthon-newtab&channel=t1";
		}
	}
	$u=str_replace('"',"'",$u);
	$u=str_replace('utf-8',"gb2312",$u);
	return $u;
}
function wz($aid){
	$p='文章';
	if($aid==0)$p='--';
	if($aid==-1)$p='<span style="color:red">首页</span>';
	if($aid==-2)$p='<span style="color:green">频道</span>';
	if($aid==-3)$p='<span style="color:blue">列表</span>';
	if($aid==-4)$p='<span style="color:orange">TAG</span>';
	if($aid==-5)$p='<span style="color:orange">搜索</span>';
	return $p;
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
	return '<img title="'.$i.'" src="ico/'.$iarr[$j].'.png" width="16" alt="'.$iarr[$j].'"/>';
}

if(empty($urltype)) $urltype = '';
if(empty($reffer)) $reffer = '';
if(empty($cip)) $cip = "";
if(!isset($day)) $day = '';
if($urltype!=''){
	if($urltype>-6&&$urltype<0) $where .= " AND aid='$urltype' ";
	else $where .= " AND aid>0 ";
}
if($cip!="") $where .= " AND ip LIKE '%$cip%' ";
if($reffer!=""){
	if($reffer=='weibo'){
		$where .= " AND (reffer LIKE '%weibo.com%' or reffer LIKE '%iask.cn%' or reffer LIKE '%t.qq.com%')";
	}
	else
	$where .= " AND reffer LIKE '%$reffer%' ";
}
if($day!='')
{
	$starttime=strtotime($day);
	$where .= " AND (dateline<'".($starttime+86400)."' AND dateline>'$starttime') ";
}

$urllist='
	  <option value=""   '.($urltype == ''?'selected':'').'>--全部---</option>
	  <option value="-1" '.($urltype == -1?'selected':'').'>--首页</option>
	  <option value="-2" '.($urltype == -2?'selected':'').'>--频道</option>
	  <option value="-3" '.($urltype == -3?'selected':'').'>--列表</option>
	  <option value="-4" '.($urltype == -4?'selected':'').'>--TAG</option>
	  <option value="1"  '.($urltype == 1?'selected':'').'>--文章页</option>
	  <option value="-5" '.($urltype == -5?'selected':'').'>--专题</option>';
$solist='
	  <option value="" '.($reffer == ''?'selected':'').'>--全部---</option>
	  <option value="google" '.($reffer == 'google'?'selected':'').'>--google</option>
	  <option value="baidu.com" '.($reffer == 'baidu.com'?'selected':'').'>--baidu</option>
	  <option value="sogou.com" '.($reffer == 'sogou.com'?'selected':'').'>--搜狗sogou</option>
	  <option value="360.cn" '.($reffer == '360.cn'?'selected':'').'>--360.cn</option>
	  <option value="soso.com" '.($reffer == 'soso.com'?'selected':'').'>--腾讯soso</option>
	  <option value="bing.com" '.($reffer == 'bing.com'?'selected':'').'>--微软bing</option>
	  <option value="haiwen.net" '.($reffer == 'haiwen.net'?'selected':'').'>--海文</option>
	  <option value="eightedu.com" '.($reffer == 'eightedu.com'?'selected':'').'>--八人</option>
	  <option value="blog.sina" '.($reffer == 'blog.sina'?'selected':'').'>--新浪博客</option>
	  <option value="weibo" '.($reffer == 'weibo'?'selected':'').'>--新浪微博</option>';
$timelist='
	<option value="0" '.($day == '0'?'selected':'').'>--全部--</option>
	<option value="1" '.($day == '1'?'selected':'').'>今天</option>
	<option value="2" '.($day == '2'?'selected':'').'>昨天</option>
	<option value="7" '.($day == '7'?'selected':'').'>最近7天</option>
	<option value="15" '.($day == '15'?'selected':'').'>15天内</option>
	<option value="30" '.($day == '30'?'selected':'').'>30天以内</option>
	<option value="60" '.($day == '60'?'selected':'').'>60天以内</option>';


$sql = "SELECT #@__arc_reffer.* FROM #@__arc_reffer
		WHERE 1=1 $where ORDER BY #@__arc_reffer.id DESC";

$dlist = new DataListCP();
$dlist->pageSize = 50;
$dlist->SetParameter("urltype",$urltype);
$dlist->SetParameter("cip",$cip);
$dlist->SetParameter("day",$day);
$dlist->SetTemplate(DEDEADMIN."/templets/count_list.htm");
$dlist->SetSource($sql);
$dlist->Display();
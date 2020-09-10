<?php
/**
 * 文档编辑
 *
 * @version        $Id: article_edit.php 1 14:12 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('a_Edit,a_AccEdit,a_MyEdit');
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEADMIN."/inc/inc_archives_functions.php");
if(empty($dopost)) $dopost = '';


$aid = isset($aid) && is_numeric($aid) ? $aid : 0;

    require_once(DEDEADMIN."/inc/inc_catalog_options.php");
    require_once(DEDEINC."/dedetag.class.php");
    ClearMyAddon();

    //读取归档信息
    $query = "SELECT ch.typename AS channelname,ar.membername AS rankname,arc.*
    FROM `#@__archives` arc
    LEFT JOIN `#@__channeltype` ch ON ch.id=arc.channel
    LEFT JOIN `#@__arcrank` ar ON ar.rank=arc.arcrank WHERE arc.id='$aid' ";
    $arcRow = $dsql->GetOne($query);
    if(!is_array($arcRow))
    {
        ShowMsg("读取档案基本信息出错!","-1");
        exit();
    }
    $query = "SELECT * FROM `#@__channeltype` WHERE id='".$arcRow['channel']."'";
    $cInfos = $dsql->GetOne($query);
    if(!is_array($cInfos))
    {
        ShowMsg("读取频道配置信息出错!","javascript:;");
        exit();
    }
    $addtable = $cInfos['addtable'];
    $addRow = $dsql->GetOne("SELECT * FROM `$addtable` WHERE aid='$aid'");
    if(!is_array($addRow))
    {
        ShowMsg("读取附加信息出错!","javascript:;");
        exit();
    }
    $channelid = $arcRow['channel'];


function str_replace_once($needle, $replace, $haystack) {//只替换一次关键词的函数
   $pos = strpos($haystack, $needle);
   if ($pos === false) {
      return $haystack;
   }
   return substr_replace($haystack, $replace, $pos, strlen($needle));
}

	$txt = $arcRow['description'];//描述作为微博内容传递
	$tags = GetTags($aid);
    if(!empty($tags)){//替换tag为微博话题
		$tagArr = explode(',', $tags);
		for($i=0;$i<count($tagArr);$i++){
			$txt = str_replace_once($tagArr[$i],'#'.$tagArr[$i].'#',$txt);
		}
	}
	$txt = urlencode('#'.$arcRow['title'].'# '.$txt);//摘要带上标题作为话题
	
	$pic    = urlencode($arcRow['litpic']);//缩略图插入微博
    $artUrl = MakeArt($aid,true,true);
	$artUrl = urlencode($cfg_basehost.$artUrl); //文章地址跟在微博后边
	$url2   = urlencode($cfg_basehost.'/api/changweibo.php?aid='.$aid);
	//$txt=urlencode('#'.$arcRow['title'].'# '.$arcRow['description'].$tags);
	//echo $artUrl;
		
	header('Location:/weibo/weibolist.php?url='.$artUrl.'&pic='.$pic.'&url2='.$url2.'&txt='.$txt); 
    exit();
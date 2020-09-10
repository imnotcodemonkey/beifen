<?php
/**
 * �ĵ��༭
 *
 * @version        $Id: article_edit.php 1 14:12 2010��7��12��Z tianya $
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

    //��ȡ�鵵��Ϣ
    $query = "SELECT ch.typename AS channelname,ar.membername AS rankname,arc.*
    FROM `#@__archives` arc
    LEFT JOIN `#@__channeltype` ch ON ch.id=arc.channel
    LEFT JOIN `#@__arcrank` ar ON ar.rank=arc.arcrank WHERE arc.id='$aid' ";
    $arcRow = $dsql->GetOne($query);
    if(!is_array($arcRow))
    {
        ShowMsg("��ȡ����������Ϣ����!","-1");
        exit();
    }
    $query = "SELECT * FROM `#@__channeltype` WHERE id='".$arcRow['channel']."'";
    $cInfos = $dsql->GetOne($query);
    if(!is_array($cInfos))
    {
        ShowMsg("��ȡƵ��������Ϣ����!","javascript:;");
        exit();
    }
    $addtable = $cInfos['addtable'];
    $addRow = $dsql->GetOne("SELECT * FROM `$addtable` WHERE aid='$aid'");
    if(!is_array($addRow))
    {
        ShowMsg("��ȡ������Ϣ����!","javascript:;");
        exit();
    }
    $channelid = $arcRow['channel'];


function str_replace_once($needle, $replace, $haystack) {//ֻ�滻һ�ιؼ��ʵĺ���
   $pos = strpos($haystack, $needle);
   if ($pos === false) {
      return $haystack;
   }
   return substr_replace($haystack, $replace, $pos, strlen($needle));
}

	$txt = $arcRow['description'];//������Ϊ΢�����ݴ���
	$tags = GetTags($aid);
    if(!empty($tags)){//�滻tagΪ΢������
		$tagArr = explode(',', $tags);
		for($i=0;$i<count($tagArr);$i++){
			$txt = str_replace_once($tagArr[$i],'#'.$tagArr[$i].'#',$txt);
		}
	}
	$txt = urlencode('#'.$arcRow['title'].'# '.$txt);//ժҪ���ϱ�����Ϊ����
	
	$pic    = urlencode($arcRow['litpic']);//����ͼ����΢��
    $artUrl = MakeArt($aid,true,true);
	$artUrl = urlencode($cfg_basehost.$artUrl); //���µ�ַ����΢�����
	$url2   = urlencode($cfg_basehost.'/api/changweibo.php?aid='.$aid);
	//$txt=urlencode('#'.$arcRow['title'].'# '.$arcRow['description'].$tags);
	//echo $artUrl;
		
	header('Location:/weibo/weibolist.php?url='.$artUrl.'&pic='.$pic.'&url2='.$url2.'&txt='.$txt); 
    exit();
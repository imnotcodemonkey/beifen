<?php
/**
 *
 * �ĵ�ͳ��
 *
 * �������ʾ�������,������view����,��������ʣӵ��÷ŵ��ĵ�ģ���ʵ�λ��
 * <script src="{dede:field name='phpurl'/}/count.php?view=yes&aid={dede:field name='id'/}&mid={dede:field name='mid'/}" language="javascript"></script>
 * ��ͨ������Ϊ
 * <script src="{dede:field name='phpurl'/}/count.php?aid={dede:field name='id'/}&mid={dede:field name='mid'/}" language="javascript"></script>
 *
 * @version        $Id: count.php 1 20:43 2010��7��8��Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");
if(!isset($aid)) $aid = 0;

$cid = empty($cid)? 1 : intval(preg_replace("/[^-\d]+[^\d]/",'', $cid));
if($aid=='A')$aid=-1;
elseif($aid=='B')$aid=-2;
elseif($aid=='C')$aid=-3;
elseif($aid=='D')$aid=-4;
else $aid = intval($aid);

function myUrl($url){
	$url=str_replace('LHB1','/',$url);
	$url=str_replace('LHB2','?',$url);
	$url=str_replace('LHB3','=',$url);
	$url=str_replace('LHB4','&',$url);
	$url=str_replace('LHB5','#',$url);
	$url=str_replace('LHB6',':',$url);
	$url=str_replace('LHB7','.',$url);
	return $url;
}
function is_utf8($word){
	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true)
	{
		return true;
	}else{
		return false;
	}
}

$u = empty($u)? '' : myUrl($u);
$r = empty($r)? '' : myUrl($r);
$t = empty($t)? '' : myUrl($t);
$t = str_replace('_��ְ�о�������������','',$t);
$t = str_replace('-��ְ�о���������','',$t);
$t = str_replace('_�й������ѧ��ְ�о���������','',$t);
$t = str_replace('-�˴���ְ��������','',$t);
$rb='';
if(strpos($r,'ruconline.com')>4&&strpos($r,'ruconline.com')<20){$r='';}//��·��ַΪ��վ��ʱ����Ϊ��
if($r!=''){
	if(strpos($r,'baidu.com')<1){
		if(!is_utf8($r)){
			//$r=mb_convert_encoding($r, "UTF-8", "GBK");
		}
	}
}

$maintable = '#@__archives';
$idtype='id';


$IP=GetIP();
$inner=true;
if($IP=='59.108.73.73')$inner=false;
if($IP=='59.108.73.72')$inner=false;
if($IP=='118.186.10.64')$inner=false;

if($aid>0){
	//���Ƶ��ģ��ID
	if($cid < 0)
	{
		$row = $dsql->GetOne("SELECT addtable FROM `#@__channeltype` WHERE id='$cid' AND issystem='-1';");
		$maintable = empty($row['addtable'])? '' : $row['addtable'];
		$idtype='aid';
	}
	if(!empty($click)){
		$row = $dsql->GetOne(" SELECT click,goodpost FROM `{$maintable}` WHERE {$idtype}='$aid' ");
		if(is_array($row))
		{
			if($click=='yes'){echo "document.write('".$row['click']."');\r\n";}
			else if($click=='hide'){echo "//".$row['click'];}
			else{
				echo "<!--\n";
				echo '$("#'.$click.'").html("'.$row['click'].'");';
				echo '$("#diggNum").html("'.$row['goodpost'].'")';
				echo "//".$u."\n";
				echo "//".$r."\n";
				echo "-->";
			}
		}
	}
	//$mid = (isset($mid) && is_numeric($mid)) ? $mid : 0;
	if($inner){
		//if(!empty($mid))
		//{
		//    $dsql->ExecuteNoneQuery(" UPDATE `#@__member_tj` SET pagecount=pagecount+1 WHERE mid='$mid' ");
		//}
		if(!empty($maintable))
		{
			if($r!=''){//��·��ַ��Ϊ�յ�ʱ�������ʼ�¼
				$dsql->ExecuteNoneQuery(" UPDATE `{$maintable}` SET click=click+1,tackid=tackid+1 WHERE {$idtype}='$aid' ");
				if((isset($mid) && $mid=='zt'))$aid=-5;
				$dsql->ExecuteNoneQuery("INSERT INTO `#@__arc_reffer` (`aid`,`tit`,`url`,`reffer`,`ip`,`dateline`)VALUES('{$aid}','{$t}','{$u}','{$r}','{$IP}','".time()."') ");
			}
			else
				$dsql->ExecuteNoneQuery(" UPDATE `{$maintable}` SET click=click+1 WHERE {$idtype}='$aid' ");
		}
	}
}else{//������ҳ���¼������·
	if($r!=''){
		$dsql->ExecuteNoneQuery("INSERT INTO `#@__arc_reffer` (`aid`,`tit`,`url`,`reffer`,`ip`,`dateline`)VALUES('{$aid}','{$t}','{$u}','{$r}','{$IP}','".time()."') ");
	}
	echo "<!--\n";
	echo "//".$u."\n";
	echo "//".$r."\n";
	echo "-->";
}
exit();
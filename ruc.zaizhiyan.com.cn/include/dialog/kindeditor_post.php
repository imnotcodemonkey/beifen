<?php
/**
 * �������
 *
 * @version        $Id: select_soft_post.php 1 9:43 2010��7��8��Z tianya $
 * @package        DedeCMS.Dialog
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

if(!isset($cfg_basedir)){
    include_once(dirname(__FILE__).'/config.php');
}
function alert($msg) {
	header('Content-type: text/html; charset=GBK');
	echo $msg;
	exit;
}

if(empty($uploadfile))   $uploadfile = '';
if(empty($uploadmbtype)) $uploadmbtype = '�������';
if(empty($bkurl))        $bkurl = 'kindeditor_browse.php';

if(empty($dir)){
	if(empty($fileType)){$dir = 'soft';}
	else{$dir=$fileType;}
}
if(empty($fileType)) $fileType =$dir;

$dir = $cfg_medias_dir.'/'.$dir;

$dir = str_replace('.','',$dir);
$dir = preg_replace("#\/{1,}#", '/', $dir);
if(strlen($dir) < strlen($cfg_soft_dir)){
    $dir = $cfg_soft_dir;
}


$newname = ( empty($newname) ? '' : preg_replace("#[\\ \"\*\?\t\r\n<>':\/|]#", "", $newname) );

if(!is_uploaded_file($uploadfile))
{
    alert("��û��ѡ���ϴ����ļ���ѡ����ļ���С��������!");
    exit();
}

//����������֧�ֵĸ���
if($fileType== 'flash')      {$cfg_filetype = $cfg_mediatype; $mediatype=2;$updir = $cfg_medias_dir.'/flash';}
else if($fileType== 'media') {$cfg_filetype = $cfg_mediatype; $mediatype=3;$updir = $cfg_other_medias;}
else if($fileType== 'allimg'){$cfg_filetype = $cfg_imgtype;   $mediatype=1;$updir = $cfg_image_dir;}
else{
	$cfg_filetype = $cfg_softtype;
	$mediatype=4;
	$updir = $cfg_soft_dir;
}

$cfg_filetype = str_replace('||', '|', $cfg_filetype);

$uploadfile_name = trim(preg_replace("#[ \r\n\t\*\%\\\/\?><\|\":]{1,}#", '', $uploadfile_name));
if(!preg_match("#\.(".$cfg_filetype.")#i", $uploadfile_name))
{
    alert("�����ϴ���{$uploadmbtype}��������б������ϵͳ����չ���޶������ã�");
    exit();
}

$nowtme = time();
//if($dir==$cfg_soft_dir)
//{
    $newdir = MyDate($cfg_addon_savetype, $nowtme);
    $dir = $updir.'/'.$newdir;
    if(!is_dir($cfg_basedir.$dir))
    {
        MkdirAll($cfg_basedir.$dir,$cfg_dir_purview);
        CloseFtp();
    }
//}

//�ļ�����ǰΪ�ֹ�ָ���� �����Զ�����
if(!empty($newname))
{
    $filename = $newname;
    if(!preg_match("#\.#", $filename)) $fs = explode('.', $uploadfile_name);
    else $fs = explode('.', $filename);
    if(preg_match("#".$cfg_not_allowall."#", $fs[count($fs)-1]))
    {
        alert("��ָ�����ļ�����ϵͳ��ֹ��");
        exit();
    }
    if(!preg_match("#\.#", $filename)) $filename = $filename.'.'.$fs[count($fs)-1];
}else{
    $filename = MyDate('m-d_His',$nowtme).'-'.$cuserLogin->getUserID();
    $fs = explode('.', $uploadfile_name);
    if(preg_match("#".$cfg_not_allowall."#", $fs[count($fs)-1]))
    {
        alert("���ϴ���ĳЩ���ܴ��ڲ���ȫ���ص��ļ���ϵͳ�ܾ�������");
        exit();
    }
    $filename = $filename.'.'.$fs[count($fs)-1];
}

$fullfilename = $cfg_basedir.$dir.'/'.$filename;
$fullfileurl = $dir.'/'.$filename;
move_uploaded_file($uploadfile,$fullfilename) or die("�ϴ��ļ��� $fullfilename ʧ�ܣ�");
@unlink($uploadfile);
if($cfg_remote_site=='Y' && $remoteuploads == 1)
{
    //����Զ���ļ�·��
    $remotefile = str_replace(DEDEROOT, '', $fullfilename);
    $localfile = '../..'.$remotefile;
    //����Զ���ļ���
    $remotedir = preg_replace('/[^\/]*\.('.$cfg_softtype.')/', '', $remotefile);
    $ftp->rmkdir($remotedir);
    $ftp->upload($localfile, $remotefile);
}


$inquery = "INSERT INTO `#@__uploads`(arcid,title,url,mediatype,width,height,playtime,filesize,uptime,mid)
   VALUES ('0','$filename','$fullfileurl','$mediatype','0','0','0','{$uploadfile_size}','{$nowtme}','".$cuserLogin->getUserID()."'); ";

$dsql->ExecuteNoneQuery($inquery);
$fid = $dsql->GetLastID();
AddMyAddon($fid, $fullfileurl);

$browseUpload = isset($browseUpload )? TRUE:FALSE;
if ($GLOBALS['cfg_html_editor']=='kindeditor' && !$browseUpload )
{
	header('Content-type: text/html; charset=UTF-8');
	echo '{"error":0,"url":"'.$dir.'/'.$filename.'","filename":"'.$uploadfile_name.'"}';
	exit();
}


ShowMsg("�ɹ��ϴ��ļ���",$bkurl."?comeback=".urlencode($filename)."&fileType=$fileType&dir=".urlencode($dir)."&d=".time());

exit();
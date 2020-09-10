<?php
/**
 * 软件发送
 *
 * @version        $Id: select_soft_post.php 1 9:43 2010年7月8日Z tianya $
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
if(empty($uploadmbtype)) $uploadmbtype = '软件类型';
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
    alert("你没有选择上传的文件或选择的文件大小超出限制!");
    exit();
}

//各类型所有支持的附件
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
    alert("你所上传的{$uploadmbtype}不在许可列表，请更改系统对扩展名限定的配置！");
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

//文件名（前为手工指定， 后者自动处理）
if(!empty($newname))
{
    $filename = $newname;
    if(!preg_match("#\.#", $filename)) $fs = explode('.', $uploadfile_name);
    else $fs = explode('.', $filename);
    if(preg_match("#".$cfg_not_allowall."#", $fs[count($fs)-1]))
    {
        alert("你指定的文件名被系统禁止！");
        exit();
    }
    if(!preg_match("#\.#", $filename)) $filename = $filename.'.'.$fs[count($fs)-1];
}else{
    $filename = MyDate('m-d_His',$nowtme).'-'.$cuserLogin->getUserID();
    $fs = explode('.', $uploadfile_name);
    if(preg_match("#".$cfg_not_allowall."#", $fs[count($fs)-1]))
    {
        alert("你上传了某些可能存在不安全因素的文件，系统拒绝操作！");
        exit();
    }
    $filename = $filename.'.'.$fs[count($fs)-1];
}

$fullfilename = $cfg_basedir.$dir.'/'.$filename;
$fullfileurl = $dir.'/'.$filename;
move_uploaded_file($uploadfile,$fullfilename) or die("上传文件到 $fullfilename 失败！");
@unlink($uploadfile);
if($cfg_remote_site=='Y' && $remoteuploads == 1)
{
    //分析远程文件路径
    $remotefile = str_replace(DEDEROOT, '', $fullfilename);
    $localfile = '../..'.$remotefile;
    //创建远程文件夹
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


ShowMsg("成功上传文件！",$bkurl."?comeback=".urlencode($filename)."&fileType=$fileType&dir=".urlencode($dir)."&d=".time());

exit();
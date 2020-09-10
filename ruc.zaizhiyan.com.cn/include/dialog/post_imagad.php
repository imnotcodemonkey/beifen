<?php
/**
 * 图片选择
 *
 * @version        $Id: select_images_post.php 1 9:43 2010年7月8日Z tianya $
 * @package        DedeCMS.Dialog
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__)."/../image.func.php");


if(empty($dir))
{
    $dir ='';
    $dir = str_replace('.', '', $dir);
    $dir = preg_replace("#\/{1,}#", '/', $dir);
    if(strlen($dir) < strlen($cfg_image_dir))
    {
        $dir = $cfg_image_dir;
    }
}

if(empty($imgfile))
{
    $imgfile='';
}
if(!is_uploaded_file($imgfile))
{
    ShowMsg("你没有选择上传的文件!".$imgfile, "-1");
    exit();
}
$CKEditorFuncNum = (isset($CKEditorFuncNum))? $CKEditorFuncNum : 1;
$imgfile_name = trim(preg_replace("#[ \r\n\t\*\%\\\/\?><\|\":]{1,}#", '', $imgfile_name));

if(!preg_match("#\.(".$cfg_imgtype.")#i", $imgfile_name))
{
    ShowMsg("你所上传的图片类型不在许可列表，请更改系统对扩展名限定的配置！", "-1");
    exit();
}
$nowtme = time();
$sparr = Array("image/pjpeg", "image/jpeg", "image/gif", "image/png", "image/xpng", "image/wbmp");
$imgfile_type = strtolower(trim($imgfile_type));
if(!in_array($imgfile_type, $sparr))
{
    ShowMsg("上传的图片格式错误，请使用JPEG、GIF、PNG、WBMP格式的其中一种！","-1");
    exit();
}


$filename_name = MyDate('m-d_His',$nowtme).'-'.$cuserLogin->getUserID();//.mt_rand(100,999));

$mdir='';
$filename = $filename_name;

$fs = explode('.', $imgfile_name);
$filename = $filename.'.'.$fs[count($fs)-1];
$filename_name = $filename_name.'.'.$fs[count($fs)-1];
$fullfilename = $cfg_basedir.$dir."/".$filename;
move_uploaded_file($imgfile, $fullfilename) or die("上传文件到 $fullfilename 失败！");
if($cfg_remote_site=='Y' && $remoteuploads == 1)
{
    //分析远程文件路径
    $remotefile = str_replace(DEDEROOT, '', $fullfilename);
    $localfile = '../..'.$remotefile;
    //创建远程文件夹
    $remotedir = preg_replace('/[^\/]*\.(jpg|gif|bmp|png)/', '', $remotefile);
    $ftp->rmkdir($remotedir);
    $ftp->upload($localfile, $remotefile);
}
@unlink($imgfile);
if(empty($resize))
{
    $resize = 0;
}
$info = '';
$sizes[0] = 0; $sizes[1] = 0;
    if(in_array($imgfile_type, $cfg_photo_typenames))
    {
		$sizes = getimagesize($fullfilename, $info);
		$imgwidthValue = $sizes[0];
		$imgheightValue = $sizes[1];
		
	}


$imgsize = filesize($fullfilename);
$inquery = "INSERT INTO `#@__uploads`(arcid,title,url,mediatype,width,height,playtime,filesize,uptime,mid)
  VALUES ('0','$filename','".$dir."/".$filename."','1','$imgwidthValue','$imgheightValue','0','{$imgsize}','{$nowtme}','".$cuserLogin->getUserID()."'); ";
$dsql->ExecuteNoneQuery($inquery);
$fid = $dsql->GetLastID();
AddMyAddon($fid, $dir.'/'.$filename);
$CKUpload = isset($CKUpload)? $CKUpload : FALSE;
if ($GLOBALS['cfg_html_editor']=='ckeditor' && $CKUpload)
{
    $fileurl = $dir.'/'.$filename;
    $message = '';
    
    $str='<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$CKEditorFuncNum.', \''.$fileurl.'\', \''.$message.'\');</script>';
    exit($str);
}

if(!empty($noeditor)){
	//（2011.08.25 根据用户反馈修正图片上传回调 by:织梦的鱼）
	ShowMsg("成功上传一幅图片！","browse_imgad.php?imgstick=$imgstick&comeback=".urlencode($filename_name)."&v=$v&f=$f&CKEditorFuncNum=$CKEditorFuncNum&noeditor=yes&dir=".urlencode($dir)."/$mdir&d=".time());
}else{
	ShowMsg("成功上传一幅图片！","browse_imgad.php?imgstick=$imgstick&comeback=".urlencode($filename_name)."&v=$v&f=$f&CKEditorFuncNum=$CKEditorFuncNum&dir=".urlencode($dir)."/$mdir&d=".time());
}
exit();
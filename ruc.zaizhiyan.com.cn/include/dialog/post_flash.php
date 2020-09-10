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
if(!isset($cfg_basedir))
{
    include_once(dirname(__FILE__).'/config.php');
}
if(empty($uploadfile)) $uploadfile = '';
if(!empty($imgfile)) $uploadfile = $imgfile;
if(empty($uploadmbtype)) $uploadmbtype = '��������';
if(empty($bkurl)) $bkurl = 'browse_flash.php';
$CKEditorFuncNum = (isset($CKEditorFuncNum))? $CKEditorFuncNum : 1;
$newname = ( empty($newname) ? '' : preg_replace("#[\\ \"\*\?\t\r\n<>':\/|]#", "", $newname) );

if(!is_uploaded_file($uploadfile))
{
    ShowMsg("��û��ѡ���ϴ����ļ���ѡ����ļ���С��������!", "-1");
    exit();
}

if(empty($activepath))$activepath=$cfg_medias_dir.'/flash';

//�����������֧�ֵĸ���
$cfg_softtype = 'swf|flv|fla';// $cfg_softtype;
$cfg_softtype = str_replace('||', '|', $cfg_softtype);

if(!empty($imgfile_name))$uploadfile_name=$imgfile_name;
$uploadfile_name = trim(preg_replace("#[ \r\n\t\*\%\\\/\?><\|\":]{1,}#", '', $uploadfile_name));
if(!preg_match("#\.(".$cfg_softtype.")#i", $uploadfile_name))
{
    ShowMsg("�����ϴ���{$uploadmbtype}��������б������ϵͳ����չ���޶������ã�","");
    exit();
}

$nowtme = time();
if($activepath==$cfg_soft_dir)
{
    $newdir = MyDate($cfg_addon_savetype, $nowtme);
    $activepath = $activepath.'/'.$newdir;
    if(!is_dir($cfg_basedir.$activepath))
    {
        MkdirAll($cfg_basedir.$activepath,$cfg_dir_purview);
        CloseFtp();
    }
}

//�ļ�����ǰΪ�ֹ�ָ���� �����Զ�����
if(!empty($newname))
{
    $filename = $newname;
    if(!preg_match("#\.#", $filename)) $fs = explode('.', $uploadfile_name);
    else $fs = explode('.', $filename);
    if(preg_match("#".$cfg_not_allowall."#", $fs[count($fs)-1]))
    {
        ShowMsg("��ָ�����ļ�����ϵͳ��ֹ��",'javascript:;');
        exit();
    }
    if(!preg_match("#\.#", $filename)) $filename = $filename.'.'.$fs[count($fs)-1];
}else{    
	$filename = MyDate('m-d_His',$nowtme).'-'.$cuserLogin->getUserID();//.mt_rand(100,999));//$filename = $cuserLogin->getUserID().'-'.dd2char(MyDate('ymdHis',$nowtme));
    $fs = explode('.', $uploadfile_name);
    if(preg_match("#".$cfg_not_allowall."#", $fs[count($fs)-1]))
    {
        ShowMsg("���ϴ���ĳЩ���ܴ��ڲ���ȫ���ص��ļ���ϵͳ�ܾ�������",'javascript:;');
        exit();
    }
    $filename = $filename.'.'.$fs[count($fs)-1];
}

$fullfilename = $cfg_basedir.$activepath.'/'.$filename;
$fullfileurl = $activepath.'/'.$filename;
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

if($uploadfile_type == 'application/x-shockwave-flash')
{
    $mediatype=2;
}
else if(preg_match('#image#i', $uploadfile_type))
{
    $mediatype=1;
}
else if(preg_match('#audio|media|video#i', $uploadfile_type))
{
    $mediatype=3;
}
else
{
    $mediatype=4;
}

$inquery = "INSERT INTO `#@__uploads`(arcid,title,url,mediatype,width,height,playtime,filesize,uptime,mid)
   VALUES ('0','$filename','$fullfileurl','$mediatype','0','0','0','{$uploadfile_size}','{$nowtme}','".$cuserLogin->getUserID()."'); ";

$dsql->ExecuteNoneQuery($inquery);
$fid = $dsql->GetLastID();
AddMyAddon($fid, $fullfileurl);

$CKUpload = isset($CKUpload)? $CKUpload : FALSE;
if ($GLOBALS['cfg_html_editor']=='ckeditor' && $CKUpload)
{
    $fileurl = $activepath.'/'.$filename;
    $message = '';
    
    $str='<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$CKEditorFuncNum.', \''.$fileurl.'\', \''.$message.'\');</script>';
    exit($str);
}


ShowMsg("�ɹ��ϴ��ļ���",$bkurl."?comeback=".urlencode($filename)."&f=$f&CKEditorFuncNum=$CKEditorFuncNum&dir=".urlencode($activepath)."&d=".time());
exit();
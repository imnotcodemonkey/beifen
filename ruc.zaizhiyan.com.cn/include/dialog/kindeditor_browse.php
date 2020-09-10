<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
/**
 * ���ѡ���
 *
 * @version        $Id: select_soft.php 1 9:43 2010��7��8��Z tianya $
 * @package        DedeCMS.Dialog
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");

if(empty($fileType)) $fileType= 'soft';
if($fileType== 'flash')      {$defaultDir  = $cfg_medias_dir.'/flash'; $mediatype=2;}
else if($fileType== 'media') {$defaultDir  = $cfg_other_medias; $mediatype=3;}
else if($fileType== 'allimg'){$defaultDir  = $cfg_image_dir;    $mediatype=1;}
else{
	$defaultDir = $cfg_soft_dir;
	$mediatype=4;
}

if(empty($dir)){ $dir = $defaultDir; }



$dir = str_replace('.','',$dir);
$dir = preg_replace("#\/{1,}#", '/', $dir);
if(strlen($dir) < strlen($cfg_medias_dir)){
   $dir = $cfg_medias_dir;
}

$inpath = $cfg_basedir.$dir;
$activeurl = '..'.$dir;

if(empty($f))
{
    $f='form1.file';
}

$addparm = '&f='.$f.'&fileType='.$fileType;
?>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=<?php echo $cfg_soft_lang; ?>' />
<title>�ļ�������</title>
<link href='/res/ico/fileview.css' rel='stylesheet' type='text/css' />
<script type="text/javascript">
function nullLink(){
	return;
}
function ReturnValue(reimg){
	if(window.opener.document.<?php echo $f;?>){
		window.opener.document.<?php echo $f;?>.value=reimg;
    }
	if(window.opener.document.getElementById('keUrl')){
		window.opener.document.getElementById('keUrl').value = reimg;
	}
	if(document.all) window.opener=true;
	window.close();
}
</script>
</head>

<body background='img/allbg.gif' leftmargin='5' topmargin='0'>
<table width='100%' border='0' cellpadding='0' cellspacing='1' bgcolor='#CBD8AC' align="center">
<tr>
<td colspan='3' bgcolor='#E8F1DE' background="img/tbg.gif" height='28'>
	<form action='kindeditor_post.php' method='post' enctype="multipart/form-data" name='myform'>
		�ϴ���<input type='file' name='uploadfile' size='25' />
		����:<input type='text' name='newname' style='width:90px' />(��ѡ)
		<input type='submit' name='sb1' value='ȷ��' /> ���������Զ����ϴ����ļ���������ʹ�÷������ϵ�ǰĿ¼�����е��ļ�����
		<input type='hidden' name='fileType' value='<?php echo $fileType?>' />
		<input type='hidden' name='f' value='<?php echo $f?>' />
		<input type='hidden' name='browseUpload' value='TRUE' />
		<input type='hidden' name='job' value='upload' /> .
	</form>
</td>
</tr>
<tr bgcolor='#FFFFFF'>
<td colspan='3'>
<!-- ��ʼ�ļ��б�  -->
<table border='0' cellspacing='0' cellpadding='2'>
<tr bgcolor="#CCCCCC" height="24">
<th class='linerow'>�������ѡ���ļ�</th>
<th width="100" class='linerow'>�ļ���С</th>
<th width="140" class='linerow'>����޸�ʱ��</th>
</tr>
<?php
$dh = dir($inpath);
$ty1 = $ty2 = '';
while($file = $dh->read())
{
    //-----�����ļ���С�ʹ���ʱ��
    if($file != "." && $file != ".." && !is_dir("$inpath/$file"))
    {
        $filesize = filesize("$inpath/$file");
        $filesize = $filesize / 1024;
        if($filesize != "")
        if($filesize < 0.1){
            @list($ty1, $ty2) = split("\.", $filesize);
            $filesize = $ty1.".".substr($ty2, 0, 2);
        }
        else{
            @list($ty1, $ty2) = split("\.", $filesize);
            $filesize = $ty1.".".substr($ty2, 0, 1);
        }
    }
    if($file != "." && $file != ".."){
        $filetime = filemtime("$inpath/$file");
        $filetime = MyDate("Y-m-d H:i:s", $filetime);
    }
    //------�ж��ļ����Ͳ�������
    if($file == ".") continue;
    else if($file == "..")
    {
        if($dir == "") continue;
        $tmp = preg_replace("#[\/][^\/]*$#i", "", $dir);
        $line = "
<tr height='24'>
	<td class='linerow'> <a href='?dir=".urlencode($tmp).$addparm."'><img src=/res/ico/dir2.gif border=0 align=absmiddle>�ϼ�Ŀ¼</a></td>
	<td colspan='2' class='linerow'> ��ǰĿ¼:$dir</td>
</tr>\r\n";
        echo $line;
    }
    else if(is_dir("$inpath/$file"))
    {
        if(preg_match("#^_(.*)$#i", $file)) continue; #����FrontPage��չĿ¼��linux����Ŀ¼
        if(preg_match("#^\.(.*)$#i", $file)) continue;
        $line = "
<tr height='24'>
	<td class='linerow TL'><a href=?dir=".urlencode("$dir/$file").$addparm."><img src=/res/ico/dir.gif border=0 align=absmiddle> $file</a></td>
	<td class='linerow'>-</td>
	<td class='linerow TR'>$filetime</td>
</tr>";
        echo "$line";
    }
    else
    {	
    	$str=explode('.',$file);
		$ico='<img src=/res/ico/'.$str[count($str)-1].'.gif border=0 onerror="this.src=\'/res/ico/unknow.gif\'" width=16 align=absmiddle>';
		//if(preg_match("#\.(zip|rar|tgr.gz)#i", $file)){$ico='<img src=img/zip.gif border=0 align=absmiddle>';}
		
        if($file==$comeback) $lstyle = " style='color:red' ";
        else  $lstyle = "";

        $reurl = "$activeurl/$file";

        $reurl = preg_replace("#^\.\.#", "", $reurl);
        if($cfg_remote_site=='Y' && $remoteuploads == 1)
        {
            $reurl  = $remoteupUrl.$reurl;
        } else {
            $reurl = $reurl;
        }

        $line = "
<tr height='24'>
	<td class='linerow TL'><a href=\"javascript:ReturnValue('$reurl');\" $lstyle>$ico $file</a></td>
	<td class='linerow TR'>$filesize KB</td>
	<td class='linerow TR'>$filetime</td>
</tr>";
        echo "$line";
    }
}//End Loop
$dh->close();
?>
<!-- �ļ��б��� -->
</table></td></tr>
<tr><td colspan='3' bgcolor='#E8F1DE' height='26'>&nbsp;����Ҫѡ����ļ�����ɫ������Ϊ���ϴ����ļ���</td></tr>
</table>
</body>
</html>
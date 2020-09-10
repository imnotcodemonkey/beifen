<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
/**
 * 软件选择框
 *
 * @version        $Id: select_soft.php 1 9:43 2010年7月8日Z tianya $
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
<title>文件管理器</title>
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
		上传：<input type='file' name='uploadfile' size='25' />
		改名:<input type='text' name='newname' style='width:90px' />(可选)
		<input type='submit' name='sb1' value='确定' /> 【改名是自定义上传后文件名，不可使用服务器上当前目录下已有的文件名】
		<input type='hidden' name='fileType' value='<?php echo $fileType?>' />
		<input type='hidden' name='f' value='<?php echo $f?>' />
		<input type='hidden' name='browseUpload' value='TRUE' />
		<input type='hidden' name='job' value='upload' /> .
	</form>
</td>
</tr>
<tr bgcolor='#FFFFFF'>
<td colspan='3'>
<!-- 开始文件列表  -->
<table border='0' cellspacing='0' cellpadding='2'>
<tr bgcolor="#CCCCCC" height="24">
<th class='linerow'>点击名称选择文件</th>
<th width="100" class='linerow'>文件大小</th>
<th width="140" class='linerow'>最后修改时间</th>
</tr>
<?php
$dh = dir($inpath);
$ty1 = $ty2 = '';
while($file = $dh->read())
{
    //-----计算文件大小和创建时间
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
    //------判断文件类型并作处理
    if($file == ".") continue;
    else if($file == "..")
    {
        if($dir == "") continue;
        $tmp = preg_replace("#[\/][^\/]*$#i", "", $dir);
        $line = "
<tr height='24'>
	<td class='linerow'> <a href='?dir=".urlencode($tmp).$addparm."'><img src=/res/ico/dir2.gif border=0 align=absmiddle>上级目录</a></td>
	<td colspan='2' class='linerow'> 当前目录:$dir</td>
</tr>\r\n";
        echo $line;
    }
    else if(is_dir("$inpath/$file"))
    {
        if(preg_match("#^_(.*)$#i", $file)) continue; #屏蔽FrontPage扩展目录和linux隐蔽目录
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
<!-- 文件列表完 -->
</table></td></tr>
<tr><td colspan='3' bgcolor='#E8F1DE' height='26'>&nbsp;请点击要选择的文件，红色字样的为刚上传的文件。</td></tr>
</table>
</body>
</html>
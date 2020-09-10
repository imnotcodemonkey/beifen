<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
/**
 * 多媒体选择框
 *
 * @version        $Id: select_media.php 1 9:43 2010年7月8日Z tianya $
 * @package        DedeCMS.Dialog
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
require_once(dirname(__FILE__)."/config.php");
if(empty($activepath))
{
    $activepath = '/upload';
}
$noeditor = isset($noeditor)? $noeditor : '';
$activepath = str_replace('.', '', $activepath);
$activepath = preg_replace("#\/{1,}#", '/', $activepath);
if(strlen($activepath) < strlen($cfg_other_medias))
{
    $activepath = $cfg_other_medias;
}
$inpath = $cfg_basedir.$activepath;
$activeurl = '..'.$activepath;
if(empty($f))
{
    $f = 'form1.enclosure';
}

if(empty($comeback))
{
    $comeback = '';
}
$addparm = '';
if (!empty($CKEditor)){
    $addparm .= '&CKEditor='.$CKEditor;
    $f = $CKEditor;
}
if (!empty($CKEditorFuncNum)){
    $addparm .= '&CKEditorFuncNum='.$CKEditorFuncNum;
}
if (!empty($kindeditor)){
    $addparm .= '&kindeditor='.$kindeditor;
    $f = 'form1.'.$kindeditor;
}
if (!empty($keID)){
    $addparm .= '&keID='.$keID;
    $v = $keID;
}

if (!empty($noeditor))
{
    $addparm .= '&noeditor=yes';
}
?>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=<?php echo $cfg_soft_lang; ?>' />
<title>媒体文件管理器</title>
<link href='/res/ico/fileview.css' rel='stylesheet' type='text/css' />
<script type="text/javascript">
function nullLink()
{
	return;
}

function ReturnValue(reimg)
{
    if(window.opener.document.<?php echo $f?> != null)	{
		 window.opener.document.<?php echo $f?>.value=reimg;
	}
	 
    var funcNum = <?php echo isset($CKEditorFuncNum)? $CKEditorFuncNum : 1;?>;
	if(window.opener.CKEDITOR != null && funcNum != 1){		
		window.opener.CKEDITOR.tools.callFunction(funcNum, reimg);		
	}
	
    if(window.opener.document.getElementById('<?php echo $v?>')){
		window.opener.document.getElementById('<?php echo $v?>').value = reimg;
	}

	window.close();
}
</script>
</head>

<body background='img/allbg.gif' leftmargin='0' topmargin='0'>
<table width='100%' border='0' cellpadding='0' cellspacing='1' bgcolor='#CBD8AC' align="center">
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
$ty1="";
$ty2="";
while($file = $dh->read()) {
    //-----计算文件大小和创建时间
    if($file!="." && $file!=".." && !is_dir("$inpath/$file")){
        $filesize = filesize("$inpath/$file");
        $filesize = $filesize/1024;
        if($filesize!="")
        if($filesize<0.1)
        {
            @list($ty1,$ty2) = split("\.", $filesize);
            $filesize = $ty1.".".substr($ty2, 0, 2);
        }
        else{
            @list($ty1,$ty2) = split("\.", $filesize);
            $filesize=$ty1.".".substr($ty2, 0, 1);
        }
        $filetime = filemtime("$inpath/$file");
        $filetime = MyDate("Y-m-d H:i:s", $filetime);
    }

    //------判断文件类型并作处理
    if($file == ".") continue;
    else if($file == "..")
    {
        if($activepath == "") continue;
        $tmp = preg_replace("#[\/][^\/]*$#i", "", $activepath);
        $line = "
<tr height='24'>
	<td class='linerow'> <a href='select_media.php?f=$f&activepath=".urlencode($tmp).$addparm."'><img src=/res/ico/dir2.gif border=0 align=absmiddle>上级目录</a></td>
	<td colspan='2' class='linerow'> 当前目录:$activepath</td>
</tr>\r\n";
        echo $line;
    }
    else if(is_dir("$inpath/$file"))
    {
        if(preg_match("#^_(.*)$#i", $file)) continue; #屏蔽FrontPage扩展目录和linux隐蔽目录
        if(preg_match("#^\.(.*)$#i", $file)) continue;
        $line = "
<tr height='24'>
	<td class='linerow TL'><a href=select_media.php?f=$f&activepath=".urlencode("$activepath/$file").$addparm."><img src=/res/ico/dir.gif border=0 align=absmiddle> $file</a></td>
	<td class='linerow'>-</td>
	<td class='linerow TR'>-</td>
</tr>";
        echo "$line";
    }
    else{
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
<tr>
<td colspan='3' bgcolor='#E8F1DE'>

<table width='100%'>
<form action='select_media_post.php' method='POST' enctype="multipart/form-data" name='myform'>
<input type='hidden' name='activepath' value='<?php echo $activepath?>'>
<input type='hidden' name='f' value='<?php echo $f?>'>
<input type='hidden' name='job' value='upload'>
<input type='hidden' name='CKEditorFuncNum' value='<?php echo isset($CKEditorFuncNum)? $CKEditorFuncNum : 1;?>'>
<input type='hidden' name='CKEditor' value='<?php if(isset($CKEditor))echo $CKEditor;?>' />
<input type='hidden' name='langCode' value='<?php if(isset($langCode))echo $langCode;?>' />
<tr>
<td background="img/tbg.gif" bgcolor="#99CC00">
  &nbsp;上　传： <input type='file' name='uploadfile' style='width:320px'>&nbsp;<input type='submit' name='sb1' value='确定'>
</td>
</tr>
</form>
</table>

</td>
</tr>
</table>

</td>
</tr>
</table>

</body>
</html>
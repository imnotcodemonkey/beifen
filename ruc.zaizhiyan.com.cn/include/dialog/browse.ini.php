<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
/**
 * 软件选择框
 *
 * @version        $Id: select_soft.php 1 9:43 2010年7月8日
*/

if(empty($fileType)) $fileType= 'soft';

$icoPath = '/res/lhbico/';


if($fileType== 'flash')      {$defaultDir  = $cfg_medias_dir.'/flash'; $mediatype=2;}
else if($fileType== 'media') {$defaultDir  = $cfg_other_medias; $mediatype=3;}
else if($fileType== 'allimg'){$defaultDir  = $cfg_image_dir;    $mediatype=1;}
else if($fileType== 'imgad'){$defaultDir  = $cfg_medias_dir.'/imgad';    $mediatype=1;}
else if($fileType== 'weibo'){$defaultDir  = $cfg_medias_dir.'/weibo';    $mediatype=1;}
else{
	$defaultDir = $cfg_soft_dir;
	$mediatype=4;
}

if(empty($dir)){ 
	if(!empty($up))
		$dir = $cfg_medias_dir;
	else
		$dir=$defaultDir;
}



$dir = str_replace('.','',$dir);
$dir = preg_replace("#\/{1,}#", '/', $dir);
if(strlen($dir) < strlen($cfg_medias_dir)){
   $dir = $cfg_medias_dir;
}

$inpath = $cfg_basedir.$dir;
$activeurl = '..'.$dir;


if(empty($f)){
    $f = 'form1.picname';
}
if(empty($v)){
    $v = 'picview';
}
if(empty($comeback)){
    $comeback = '';
}

$addparm = '&f='.$f;
if (!empty($CKEditor)){
    $addparm .= '&CKEditor='.$CKEditor;
    $f = $CKEditor;
}
if (!empty($CKEditorFuncNum)){
    $addparm .= '&CKEditorFuncNum='.$CKEditorFuncNum;
}

if (!empty($keID)){
    $addparm .= '&keID='.$keID;
    $v = $keID;
}

if (!empty($noeditor)){
    $addparm .= '&noeditor=yes';
}

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title>文件管理器</title>
<link href="/res/lhbico/filebrowse.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://i.haiwen.net/js/jquery.min.js"></script>
<!--script type="text/javascript" src="/res/lhbico/jquery.min.js"></script-->

<script type="text/javascript">
function nullLink(){ return; }
function ChangeImage(surl){ document.getElementById('floater').style.display='block';document.getElementById('picview').src = surl; }
function TNav()
{
	if(window.navigator.userAgent.indexOf("MSIE")>=1) return 'IE';
  else if(window.navigator.userAgent.indexOf("Firefox")>=1) return 'FF';
  else return "OT";
}
// 获取地址参数
function getUrlParam(paramName)
{
  var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
  var match = window.location.search.match(reParam) ;
  return (match && match.length > 1) ? match[1] : '' ;
}

function ReturnImg(reimg)
{
    var funcNum = getUrlParam('CKEditorFuncNum');
	if(funcNum > 1)
	{
		var fileUrl = reimg;
		window.opener.CKEDITOR.tools.callFunction(funcNum, fileUrl);
	}
	if(window.opener.document.<?php echo $f?> != null)
	{
		window.opener.document.<?php echo $f?>.value=reimg;
		if(window.opener.document.getElementById('div<?php echo $v?>'))
	    {
		 if(TNav()=='IE'){
			 //window.opener.document.getElementById('div<?php echo $v?>').filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = reimg;
			 window.opener.document.getElementById('div<?php echo $v?>').src = reimg;
			 window.opener.document.getElementById('div<?php echo $v?>').style.width = '150px';
			 window.opener.document.getElementById('div<?php echo $v?>').style.height = '100px';
		 }
		 else
			 window.opener.document.getElementById('div<?php echo $v?>').style.backgroundImage = "url("+reimg+")";
	  }
		else if(window.opener.document.getElementById('<?php echo $v?>')){
			window.opener.document.getElementById('<?php echo $v?>').src = reimg;
		}
		if(document.all) window.opener=true;
	}

	
    window.close();
}
function ReturnValue(reimg)
{
    if(window.opener.document.getElementById('<?php echo str_replace('form1.','',$f);?>') != null)
	{
		 window.opener.document.getElementById('<?php echo str_replace('form1.','',$f);?>').value=reimg;
	}
	 
    var funcNum = <?php echo isset($CKEditorFuncNum)? $CKEditorFuncNum : 1;?>;
	if(window.opener.CKEDITOR != null && funcNum != 1)
	{
		
		window.opener.CKEDITOR.tools.callFunction(funcNum, reimg);
		
	}
	
    if(window.opener.document.getElementById('<?php echo $v?>')){
		window.opener.document.getElementById('<?php echo $v?>').value = reimg;
	}

	window.close();
}

</script>
</head>

<body>
<dl id="nav">
<dt></dt>
<dd>
<ul>
	
<?php

echo '<li class="Lib"><a onfocus="this.blur()" href="?dir='.urlencode($cfg_medias_dir).$addparm.'"><strong>'.$cfg_medias_dir.'</strong></a></li>';

echo '<li class="Photo"><a onfocus="this.blur()" href="?dir='.urlencode($cfg_medias_dir.'/allimg').$addparm.'"><strong>allimg</strong></a></li>';
echo '<li class="Flash"><a onfocus="this.blur()" href="?dir='.urlencode($cfg_medias_dir.'/flash').$addparm.'"><strong>flash</strong></a></li>';
echo '<li class="Photo"><a onfocus="this.blur()" href="?dir='.urlencode($cfg_medias_dir.'/flink').$addparm.'"><strong>flink</strong></a></li>';
echo '<li class="Photo"><a onfocus="this.blur()" href="?dir='.urlencode($cfg_medias_dir.'/img').$addparm.'"><strong>img</strong></a></li>';
echo '<li class="Photo"><a onfocus="this.blur()" href="?dir='.urlencode($cfg_medias_dir.'/imgad').$addparm.'"><strong>imgad</strong></a></li>';
echo '<li class="Photo"><a onfocus="this.blur()" href="?dir='.urlencode($cfg_medias_dir.'/litimg').$addparm.'"><strong>litimg</strong></a></li>';
echo '<li class="Movie"><a onfocus="this.blur()" href="?dir='.urlencode($cfg_medias_dir.'/media').$addparm.'"><strong>media</strong></a></li>';
echo '<li class="Docs"><a onfocus="this.blur()" href="?dir='.urlencode($cfg_medias_dir.'/soft').$addparm.'"><strong>soft</strong></a></li>';
echo '<li class="Userup"><a onfocus="this.blur()" href="?dir='.urlencode($cfg_medias_dir.'/userup').$addparm.'"><strong>userup</strong></a></li>';
?>
	
</ul>
</dd>
</dl>

<div id="files">
<div id="filesInner">

<div id="floderInfo">
<dl id="infoDl">
<dt><span id="infoDt">文件管理器</span><span id="upInfo"></span></dt>
<dd>包括: <span id="infoDd"></span><tt id="postion"></tt></dd>
</dl>

<?php
if($fileType=='allimg'){
?>
<form action='post_images.php' method='post' enctype="multipart/form-data" name='myform1'>
<?php $noeditor = !empty($noeditor)?"<input type='hidden' name='noeditor' value='yes'>":''; echo $noeditor;//（2011.08.25 修正图片上传回调?>
<input type='hidden' name='dir' value='<?php echo $cfg_medias_dir.'/'.$fileType; ?>' />
<input type='hidden' name='f' value='<?php echo $f?>' />
<input type='hidden' name='v' value='<?php echo $v?>' />
<input type='hidden' name='imgstick' value='<?php echo $imgstick?>' />
<input type='hidden' name='CKEditorFuncNum' value='<?php echo isset($CKEditorFuncNum)? $CKEditorFuncNum : 1;?>' />
<input type='hidden' name='job' value='upload' />
<table cellpadding="0" cellspacing="0">
	<tr>
		<td><input type='file' name='imgfile' style='width:210px'/></td>
		<td rowspan="2"><input type='submit' name='sb1' style="height:42px;padding:0 6px" value='确定上传' /></td>
	</tr>
	<tr>
		<td>
<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type='checkbox' name='needwatermark' value='1' class='np<?php if($photo_markup=='1') echo "' checked='checked'"; ?>' /></td><td style="width:36px">水印</td>
	<td><input type='checkbox' name='resize' value='1' class='np' /></td><td style="width:36px">缩小</td>
	<td style="width:60px">宽:<input type='text' style='width:30px' name='iwidth' value='<?php echo $cfg_ddimg_width?>' /></td>
	<td>高:<input type='text' style='width:30px' name='iheight' value='<?php echo $cfg_ddimg_height?>' /></td>
</tr>
</table>
		</td>
	</tr>
</table>
</form>
<?php
}else if($fileType=='imgad'||$fileType=='weibo'){
?>
<form action='post_imgad.php' method='post' enctype="multipart/form-data" name='myform2'>
<?php $noeditor = !empty($noeditor)?"<input type='hidden' name='noeditor' value='yes'>":''; echo $noeditor;//（2011.08.25 修正图片上传回调?>
<input type='hidden' name='dir' value='<?php echo $cfg_medias_dir.'/'.$fileType; ?>' />
<input type='hidden' name='f' value='<?php echo $f?>' />
<input type='hidden' name='v' value='<?php echo $v?>' />
<input type='hidden' name='imgstick' value='<?php echo $imgstick?>' />
<input type='hidden' name='CKEditorFuncNum' value='<?php echo isset($CKEditorFuncNum)? $CKEditorFuncNum : 1;?>' />
<input type='hidden' name='job' value='upload' />
<table cellpadding="0" cellspacing="0">
	<tr>
		<td><input type='file' name='imgfile' style='width:210px'/></td>
		<td rowspan="2"><input type='submit' name='sb1' style="height:42px;padding:0 6px" value='确定上传' /></td>
	</tr>
	<tr>
		<td>
<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type='hidden' name='needwatermark' value='0' />
		<input type='hidden' name='resize' value='0' />
		<input type='hidden' name='iwidth' value='<?php echo $cfg_ddimg_width?>' />
		<input type='hidden' style='width:30px' name='iheight' value='<?php echo $cfg_ddimg_height?>' />重命名：</td>
	<td><input type='text' name='newname' style='width:150px' /></td>
</tr>
</table>
		</td>
	</tr>
</table>
</form>
<?php

}else{
?><form action="post_<?php if($fileType=='flash')echo 'flash';else echo 'other';?>.php" method='post' enctype="multipart/form-data" name='myform'>
<table cellpadding="0" cellspacing="0">
	<tr>
		<td><input type='file' name='uploadfile' size='25' style="width:210px" /></td>
		<td rowspan="2"><input type='submit' name='sb1' style="height:42px;padding:0 6px" value='确定上传' /></td>
	</tr>
	<tr>
		<td><input type='text' name='newname' style='width:103px' />自定义文件名(可选)
	<input type='hidden' name='activepath' value='<?php echo $cfg_medias_dir.'/'.$fileType;?>' />
	<input type='hidden' name='CKEditorFuncNum' value='<?php echo isset($CKEditorFuncNum)? $CKEditorFuncNum : 1;?>' />
	<input type='hidden' name='CKEditor' value='<?php if(isset($CKEditor))echo $CKEditor;?>' />
	<input type='hidden' name='langCode' value='<?php if(isset($langCode))echo $langCode;?>' />
	<input type='hidden' name='fileType' value='<?php echo $fileType?>' />
	<input type='hidden' name='bkurl' value='<?php echo 'browse_'.$fileType.'.php'?>' />
	<input type='hidden' name='f' value='<?php echo $f?>' />
	<input type='hidden' name='browseUpload' value='TRUE' />
	<input type='hidden' name='job' value='upload' />
		</td>
	</tr>
</table>
</form>
<?php
}
?>
</div>

<?php
$dh = dir($inpath);
$ty1 = $ty2 = '';

$arrFolder='';
$arrFiles ='';
$position ='';
$lib ='文件管理器';

$f1=0;
$f2=0;

$folderIco="folder-48.png";
if(strpos($dir, "img")!== false) {$folderIco="folderPhoto-48.png";$lib='图片文件库';}
if(strpos($dir, "soft")!== false) {$folderIco="folderDocs-48.png";$lib='文档及压缩包等综合库';}
if(strpos($dir, "flink")!== false){$folderIco="folderPhoto-48.png";$lib='友情链接图片库';}
if(strpos($dir, "flash")!== false) {$folderIco="folderFlash-48.png";$lib='Flash文件库';}
if(strpos($dir, "media")!== false) {$folderIco="folderMovie-48.png";$lib='媒体文件库';}
if(strpos($dir, "userup")!== false) {$folderIco="folderUserup-48.png";$lib='会员上传库';}

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
        $filetime = filemtime("$inpath/$file");//filectime 创建时间
        $filetime = MyDate("Y.m.d H:i", $filetime);
    }
    //------判断文件类型并作处理
    if($file == ".") continue;
    else if($file == "..")
    {
        if($dir == "") continue;
        $tmp = preg_replace("#[\/][^\/]*$#i", "", $dir);
        $position = '<a href="?up=1&dir='.urlencode($tmp).$addparm.'"><img src="'.$icoPath.'folderup.png" border=0 align=absmiddle>上级目录</a> | 当前目录:'.$dir;
    }
    else if(is_dir("$inpath/$file"))
    {
        if(preg_match("#^_(.*)$#i", $file)) continue; #屏蔽FrontPage扩展目录和linux隐蔽目录
        if(preg_match("#^\.(.*)$#i", $file)) continue;
        
		if(strpos($file, "img")!== false) {$folderIco="folderPhoto-48.png";}
		if(strpos($file, "soft")!== false) {$folderIco="folderDocs-48.png";}
		if(strpos($file, "flink")!== false) {$folderIco="folderPhoto-48.png";}
		if(strpos($file, "flash")!== false) {$folderIco="folderFlash-48.png";}
		if(strpos($file, "media")!== false) {$folderIco="folderMovie-48.png";}
		if(strpos($file, "userup")!== false) {$folderIco="folderUserup-48.png";}
		
        $dl  = '<dl class="folder">';
        $dl .= '<dd><a href="?dir='.urlencode("$dir/$file").$addparm.'"><img src="'.$icoPath.$folderIco.'" /></a></dd>';
        $dl .= '<dt class="title"><a href="?dir='.urlencode("$dir/$file").$addparm.'">'.$file.'</a></dt>';
        $dl .= '<dt class="info"><span>文件夹</span></dt>';
        $dl .= "</dl>\n";
        $arrFolder = $dl.$arrFolder;
        $f1++;
    }
    else
    {	
         if(preg_match("#^index\.html$#i", $file)) continue;
	   	$str=explode('.',$file);
		$ico= $str[count($str)-1];
		$isImg=false;
		$class="file";
		$img_info = array('','');
		if(preg_match("#\.(jpg|jpeg|gif|png)#i", $file)){
			$isImg=true;$class="pic";
			$img_info = getimagesize("$inpath/$file");
		}
		
        if($file==$comeback) $class .= " red";

        $reurl = "$activeurl/$file";

        $reurl = preg_replace("#^\.\.#", "", $reurl);
        if($cfg_remote_site=='Y' && $remoteuploads == 1)
        {
            $reurl  = $remoteupUrl.$reurl;
        } else {
            $reurl = $reurl;
        }
    
		$dl = '<dl class="'.$class.'">';
		$jsFc='ReturnValue';
		if($isImg==true){
			$jsFc='ReturnImg';
			$dl .= '	<dd><table cellpadding="0" cellspacing="0"><tr><td>';
			$dl .= '<a href="javascript:'.$jsFc.'(\''.$reurl.'\')"><img alt="'.$file.'" title="点击这里选择插入到文档中" src="'.$dir.'/'.$file.'" /></a></td></tr></table></dd>'."\n";
			$dl .= "<dt class='alpha'></dt><dt class='px'><a target='_blank' href='".$dir.'/'.$file."'>".($isImg==false?'':$img_info[0].'x'.$img_info[1].'像素 ')."　查看原图&gt;&gt;</a></dt>";
		}else{
			$dl .= '	<dd onclick="ReturnValue(\''.$reurl.'\')"><img alt="" src="'.$icoPath.$ico.'-48.png" /></dd>'."\n";
		}
		$dl .= '	<dt class="title"><a title="点击这里插入" href="javascript:'.$jsFc.'(\''.$reurl.'\')" class="'.$class.'">'.$file.'</a></dt>'."\n";
		$dl .= "<dt class='info' onclick=\"".$jsFc."('".$reurl."')\" title=\"点击这里插入\"><span>$filetime</span><small>".$filesize."KB</small></dt>\n";
		$dl .= "</dl>\n";
		$arrFiles = $dl.$arrFiles;
				
		$f2++;
    }
}//End Loop
$dh->close();

//echo $position;
echo $arrFolder;
echo $arrFiles;

?>
</div>

</div>
<script type="text/javascript">
<!--
$('#infoDt').html('<?php echo $lib;?>');
$('#infoDd').html('<?php echo "".$f1."个文件夹 &nbsp;".$f2."个文件";?>');
$("#upInfo").html("<?php if($comeback!=''){echo '红色字样的为刚上传的文件';}?>");
$("#postion").html("<?php echo str_replace('"','\"',$position);?>");

//-->
</script>
<!--[if IE 6]> 
<script>
function correctPNG() 
{
for(var i=0; i<document.images.length; i++)
{
var img = document.images[i];
var imgName = img.src.toUpperCase();
if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
{
var imgID = '';//(img.id) ? "id='" + img.id + "' " : "";
var imgClass = '';//(img.className) ? "class='" + img.className + "' " : "";
var imgTitle = '';//(img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' ";
var imgStyle = "display:inline-block;";// + img.style.cssText;
//if (img.align == "left") imgStyle = "float:left;" + imgStyle;
//if (img.align == "right") imgStyle = "float:right;" + imgStyle;
if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle;
var strNewHTML = "<span "+ imgID + imgClass + imgTitle + "style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";" 
+ "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader" + "(src='" + img.src + "', sizingMethod='scale');\"></span>";
img.outerHTML = strNewHTML;
i = i-1;
}
}
}
//window.attachEvent("onload", correctPNG);
</script>
<![endif]--> &#8203;

<!-- 文件列表完 -->

</body>
</html>
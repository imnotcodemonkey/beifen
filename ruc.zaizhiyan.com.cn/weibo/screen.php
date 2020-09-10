<?php
//要截图的网址
$url='';
$softdir='D:/soft/CutyCapt-Win32-2010-04-26/';
if(isset($_GET['url'])){
	$url=$_GET['url'];

	//输出图片的位置与名称
	$pic = $softdir.date('s',time()).'.png';
	
	$path = $softdir.'CutyCapt.exe';
	$cmd = "$path --url=$url --out=$pic --min-width=430 --min-height=200";
	//exec($cmd);
	system($cmd);
}
else{
	$pic = $softdir.'loading.gif';
}

//echo 'http://'.$pic;
echo file_get_contents($pic);
?>
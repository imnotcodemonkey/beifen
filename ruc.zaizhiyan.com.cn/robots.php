<?php
header('Content-Type: text/plain');
$u= strtolower($_SERVER['HTTP_HOST']);
if($u=='www.ruconline.com'||$u=='ruconline.com'){
	require_once("robots.txt");
}else{
echo 'User-agent: *
Disallow: /';
}
?>
<?php
header('Content-Type: text/html; charset=UTF-8');

$domain=$_SERVER['HTTP_HOST'];

if($domain=='zaizhi.ruc.edu.cn'){
	define( "WB_WeiBa", '人民大学在职研' );
	define( "baseURL" , 'http://zaizhi.ruc.edu.cn');
	define( "WB_UID"  , 3052260071 );
	
	define( "WB_AKEY" , '3167903313' );
	define( "WB_SKEY" , '18cc83dd54ebade9e2bc26ff1c335852' );
}
if($domain=='www.ruconline.com'){
	define( "WB_WeiBa", '人民大学在职研招生网' );
	define( "baseURL" , 'http://www.ruconline.com');
	define( "WB_UID"  , 2845871067 );

	define( "WB_AKEY" , '101956528' );
	define( "WB_SKEY" , '2448d5c38edd35891dcfff592073bfdd' );
}
if($domain=='www.zaizhiyan.com.cn'){
	define( "WB_WeiBa", '中国在职研网' );
	define( "baseURL" , 'http://www.zaizhiyan.com.cn');
	define( "WB_UID"  , 3052193637 );
	
	define( "WB_AKEY" , '1599710529' );
	define( "WB_SKEY" , '7f4494e58411f976cd2ffab7eccd98ee' );
}

define( "WB_URL"  , 'http://e.weibo.com/'.WB_UID.'/profile' );
//回调地址
define( "WB_CALLBACK_URL" , baseURL.'/weibo/callback.php' );


//字符串截取
function subString2($str, $start, $length) {
	if(!isset($str{$start})) return '...';
	//判断起始位置
	if(ord($str{$start}) < 192) {
		if(!isset($str{$start + 1})) return '...';
		if(ord($str{$start + 1}) >= 192) {
			$start++;
		} else {
			if(!isset($str{$start + 2})) return '...';
			if(ord($str{$start + 2}) >= 192) {
				$start += 2;
			}
		}
	}
	//长度不足
	if(!isset($str{$start + $length - 1})) return substr($str, $start);
	//判断结束位置
	if(ord($str{$start + $length -1}) >= 224) {
		return substr($str, $start, $length + 2) . '...';
	} elseif(ord($str{$start + $length -1}) >= 192 || ord($str{$start + $length -2}) >= 224){
		return substr($str, $start, $length + 1) . '...';
	} else {
		return substr($str, $start, $length) . '...';
	}
}
<?php

session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

function get_emotions(){
    $url="http://api.t.sina.com.cn/emotions.json";
    return $this->oauth->get($url);
}
function get_wb_face($c){
	//if( !is_file('wbface.php')){
		$face_arr = $c->emotions(); // 远程获取表情列表
		$cachefile = "wbface.php";
		$arr = array();
		$i=0;
		foreach($face_arr as $val){
			$arr[$val['phrase']] = '<img src="'.$val['url'].'" alt="'.$val['phrase'].'">';
			$i++;
		}
		$cachetext = "<?php\r\n".
			'$face='.var_export($arr, true).
			"\r\n?>";
		$cachetext = str_replace(",\n)","\n);",$cachetext);
		file_put_contents($cachefile,$cachetext);
		$filesize=abs(filesize($cachefile));
		echo '表情个数：'.$i.'　文件大小：'.intval ($filesize/1024).'k';
 	//}
}

function arrayeval($array, $level = 0) {
	$space = '';
	for($i = 0; $i <= $level; $i++) {
		$space .= "\t";
	}
	$evaluate = "Array\n$space(\n";
	$comma = $space;
	foreach($array as $key => $val) {
		$key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
		$val = !is_array($val) && (!preg_match("/^\-?\d+$/", $val) || strlen($val) > 12 || substr($val, 0, 1)=='0') ? '\''.addcslashes($val, '\'\\').'\'' : $val;
		if(is_array($val)) {
			$evaluate .= "$comma$key => ".arrayeval($val, $level + 1);
		} else {
			$evaluate .= "$comma$key => $val";
		}
		$comma = ",\n$space";
	}
	$evaluate .= "\n$space)";
	return $evaluate;
}

$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
get_wb_face($c);
//	$ms  = $c->home_timeline(); // done

//die(json_encode($ms));

/*

foreach( $ms as $key=>$val){
	$str= strtr($val['text'],$face);//其中$face就是第一步生成的一个缓存变量
 	$str = eregi_replace('(((f|ht){1}tp://t.cn/)[a-zA-Z0-9]+)','<a href="\1" target="_blank">\1</a>',$str);// 超链替换
        $ms[$key]['text']=$str;
        if($val['retweeted_status']){//转载中的内容
              $str = strtr($val['retweeted_status']['text'],$face);// 图标
              //$str = eregi_replace('(((f|ht){1}tp://t.cn/)[a-zA-Z0-9]+)','<a href="\1" target="_blank">\1</a>',$str);// 超链替换
              $ms[$key]['retweeted_status']['text'] = $str;
       }
}


*/
?>
<?php
if(isset($_REQUEST['action'])){
	$action = $_REQUEST['action'];
}else{
	exit('参数传递失败，请<a href="javascript:go(-1)">返回</a>');
}

session_start();

include_once( 'inc/config.php' );
include_once( 'inc/saetv2.ex.class.php' );
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );

$msg='发送成功';
//发布新微博
if( $action=='new' ) {
	$cont=$_POST['text'];
	if(strlen($_POST['url'])>10){$cont.=' '.$_POST['url'];}

	$p='';
	if(isset($_POST['picname'])){
		$p=$_POST['picname'];
		$p=str_replace('请在弹出窗口中选择','',$p);
	}
	if(!empty($p)){//带图片的微博
		//生成长微博图片
		if(strpos($p,'.jpg')<10||strpos($p,'.png')<10||strpos($p,'.gif')){
			$p=baseURL.'/weibo/screen.php?url='.urlencode($p);
		}
		if(strpos($p,'/')==0)$p=baseURL.$p;
		$ret = $c->upload( $cont,$p);
	}
	else
		$ret = $c->update( $cont );	//发送微博
}
//转发
if($_POST['action']=='reposts'){
	$is_comment=0;
	if(isset($_POST['forward'])){$is_comment=1;}
	$ret = $c->repost( $_POST['id'],$_POST['text'],$is_comment );
}
//评论
if($_POST['action']=='comments'){
	$ret = $c->send_comment( $_POST['id'],$_POST['text'] );
	if(isset($_POST['forward'])){//评论的同时进行转发
		$ret = $c->repost( $_POST['id'],$_POST['text'] );
	}
}
$url=$_SERVER['HTTP_REFERER'];
if(strpos($url,'?')<1){$url.='?';}

//删除微博
if(isset($_GET['del'])&&$_GET['del']==1){
	$ret = $c->delete($_GET['id']);
	$msg='成功删除一条微博';
	$url='weibolist.php?u=myself';
}


//返回错误消息
if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) {
	$msg= "操作失败，错误：{$ret['error_code']}:{$ret['error']}";
}
//exit($p);
header("location:".$url.'&saveMsg='.$msg);
?>
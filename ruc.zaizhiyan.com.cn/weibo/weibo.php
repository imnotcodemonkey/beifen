<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php

session_start();

include_once( 'inc/config.php' );
include_once( 'inc/saetv2.ex.class.php' );
include_once( 'inc/wbface.php' );


$u='';
if(isset($_GET['u'])) $u=$_GET['u'];

$id=0;//微博id
$action='reposts';
$saveMsg='';
if(isset($_GET['id'])) $id=$_GET['id'];
if(isset($_GET['action'])) $action=$_GET['action'];
if(isset($_GET['saveMsg'])) $saveMsg='<p id=msg>'.$_GET['saveMsg'].'</p>';


$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );

$uid_get = $c->get_uid();
$uid = $uid_get['uid'];
$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息

//显示单条微博内容
$show=$c->show_status($id);

//显示转发或者评论内容
if($action=='reposts')
	$cm=$c->repost_timeline($id);
else
	$cm=$c->get_comments_by_sid($id);
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>回复、评论</title>
<link rel="stylesheet" type="text/css" href="emotion/jquery.sinaEmotion-1.1.min.css" />
<link rel="stylesheet" type="text/css" href="res/list.css" media="all" />
<script type="text/javascript" src="http://res.xschu.com/js/jquery.min.js"></script>
<script type="text/javascript" src="emotion/jquery.sinaEmotion-1.1.min.js"></script>
<script type="text/javascript" src="res/list.js"></script>
<script type="text/javascript">
jQuery(function(){
	n=140;
	$('#cont').artTxtCount($('#contTip'), n);//带url为119 否则微博允许输入140字
});
</script>
</head>

<body>
<?php
include_once( 'inc/header.php' );
?>

<div id="show">
	<div class="msg">
	<?php 
		if($uid!=$show['user']['id']){
			echo '<h2><a href="http://weibo.com/'.$show['user']['id'].'/profile" target="_blank"">'.$show['user']['screen_name'].'</a>';
			echo '<span class="gray"> (关注:'.$show['user']['followers_count'];
			echo ' 粉丝:'.$show['user']['friends_count'];
			echo ' 微博:'.$show['user']['statuses_count'].')</span></h2>';
		}
		echo $show['text'];
	?>
    </div>
	<?php if(isset($show['bmiddle_pic'])){?>
    <div class="img"><a href="<?php echo $show['original_pic'];?>" target="_blank">
	<img src="<?php echo $show['bmiddle_pic'];?>" alt="" /></a></div>
    <?php }?>
	<div class="info">
    	<label for="" class="l">
        	<span><?php echo date('m月d日 H:i',strtotime($show['created_at']));?></span>
            <span>来自：<?php echo $show['source'];?></span>
        </label>
        <label for="" class="r">
            <span><a href="?action=reposts&id=<?php echo $id;?>">转发(<?php echo $show['reposts_count'];?>)</a></span>
        	<?php
            if($uid==$show['user']['id']){
				echo '<span><a href="javascript:if(confirm(\'确实要删除吗?\'))location=\'save.php?action='.$action.'&id='.$id.'&del=1\'">删除</a></span>';
            }?>
            <span>表态(<?php echo $show['attitudes_count'];?>)</span>
            <span><a href="?action=comments&id=<?php echo $id;?>">评论(<?php echo $show['comments_count'];?>)</a></span>
        </label>
    </div>
</div>
<?php
//echo $id;
//print_r($show);
if($action=='reposts'){
	$rClass='Hover';
	$cClass='';
	$btn='转发';
	$amsg='同时评论给 '.$show['user']['screen_name'];
}
else{
	$rClass='';
	$cClass='Hover';
	$btn='评论';
	$amsg='同时转发到我的微博';
}
?>
<form action="save.php" name="f1" method="post" enctype="multipart/form-data" onsubmit="return check()">
    <div id="nav">
    	<a class="<?php echo $cClass;?>" href="?action=comments&id=<?php echo $id;?>">评论(<?php echo $show['comments_count'];?>)</a>
    	<a class="<?php echo $rClass;?>" href="?action=reposts&id=<?php echo $id;?>">转发(<?php echo $show['reposts_count'];?>)</a>
    </div>
    <p><textarea name="text" id="cont" rows="4"></textarea></p>
    <div id="t">
    	<tt class="l">
        	<a href="###" id="face">表情</a> |
            <input type="checkbox" name="forward" value="0" id="do" /><label for="do"><?php echo $amsg;?></label>
            <span id="contTip"></span>
        </tt>
    	<label class="r"><input id="smt" type="submit" value="<?php echo $btn;?>微博" /></label>
        <input type="hidden" name="action" value="<?php echo $action;?>" />
        <input type="hidden" name="id" value="<?php echo $id;?>" />
    </div>
	
	<script type="text/javascript">
	$('#face').sinaEmotion({
		target: $('#cont')
	});
	</script>
<?php
?>
	</form>
<div id="list">
<?php
//print_r($cm);exit();
if( is_array( $cm[$action] ) ): 

foreach( $cm[$action] as $item ): 
	$tit ='昵称：'.$item['user']['screen_name'].' ('.($item['user']['gender']=='f'?'女':'男').')&#13;';
	$tit.='来自：'.$item['user']['location'].'&#13;';
	$tit.='关注：'.$item['user']['followers_count'].'　';
	$tit.='粉丝：'.$item['user']['friends_count'].'　';
	$tit.='微博：'.$item['user']['statuses_count'].'&#13;';
	$tit.='简介：'.$item['user']['description'];
?>
<dl>
	<dt><a href="http://weibo.com/<?=$item['user']['id'];?>" target="_blank" title="<?=$tit;?>">
    	<img src="<?=$item['user']['profile_image_url'];?>" alt="" title="<?=$tit;?>" /></a></dt>
	<dd>
    	<p><a href="http://weibo.com/<?=$item['user']['id'];?>" title="<?=$tit;?>"><?=$item['user']['screen_name'];?></a><tt class="vt<?=$item['user']['verified_type'];?>"></tt>:
		<span><?=strtr($item['text'],$face);?> </span>
        <dfn class="date">(<?=date('m月d日 H:i',strtotime($item['created_at']));?>)</dfn>
        </p>
        <div class="info">
        <label class="r">
            <?php if($action=='comments'){
				echo '<a href="javascript:repost(\'回复@'.$item['user']['screen_name'].': \')">回复</span>';
			}else{
				echo '<a href="javascript:repost(\'//@'.$item['user']['screen_name'].': '.$item['text'].'\')">转发</span>';
			}
            ?>
        </label>
        </div>
    </dd>
</dl>
<?php
endforeach; 
endif; ?>
</div>
<script type="text/javascript">
formartCont();
</script>
</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php

session_start();

include_once( 'inc/config.php' );
include_once( 'inc/saetv2.ex.class.php' );
include_once( 'inc/wbface.php' );


$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );

$type='';
if(isset($_GET['type'])) $type=$_GET['type'];
if($type=='atme'){
	$ms  = $c->comments_mentions();
	$tit = '@我的评论';
}
else{
	$ms  = $c->comments_to_me(); // done
	$tit = '收到的评论';
}

$uid_get = $c->get_uid();
$uid = $uid_get['uid'];
if(!isset($uid)){header("Location: ".baseURL."/weibo/index.php"); };

//根据ID获取用户等基本信息
$user_message = $c->show_user_by_id( $uid);

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>微博列表V2</title>
<link rel="stylesheet" type="text/css" href="emotion/jquery.sinaEmotion-1.1.min.css" />
<link rel="stylesheet" type="text/css" href="res/list.css" media="all" />
<script type="text/javascript" src="http://res.xschu.com/js/jquery.min.js"></script>
<script type="text/javascript" src="emotion/jquery.sinaEmotion-1.1.min.js"></script>
<script type="text/javascript" src="res/list.js"></script>
<script type="text/javascript">
</script>
<base target="_self" />
</head>

<body>
<?php
include_once( 'inc/header.php' );

//print_r($ms);exit();

$i=0;
?>


<div id="list">
<?php if( is_array( $ms['comments'] ) ): ?>
<h2><?php echo $tit; ?></h2>
<?php foreach( $ms['comments'] as $item ): ?>
<dl>
	<dt><a href="http://weibo.com/<?=$item['user']['id'];?>" target="_blank"><img src="<?=$item['user']['profile_image_url'];?>" alt="" /></a></dt>
	<dd>
    	<p><a href="http://weibo.com/<?=$item['user']['id'];?>"><?=$item['user']['screen_name'];?></a><tt class="vt<?=$item['user']['verified_type'];?>"></tt>:
		<span><?=strtr($item['text'],$face);?> </span>
		</p>
<?php if($type!='atme'){ ?>
        <div class="gray">评论 我 的微博：
            “<a class="self" href="weibo.php?id=<?=$item['status']['idstr'];?>"><?=subString2($item['status']['text'],0,80);?></a>”
        </div>
<?php 
}else{ ?>
        <blockquote>
            <p><a href="http://weibo.com/<?=$item['status']['user']['id'];?>"><?=$item['status']['user']['screen_name'];?></a><tt class="vt<?=$item['status']['user']['verified_type'];?>"></tt>:
                <span><?=$item['status']['text'];?> </span></p>            	
            <?php if(!empty($item['status']['thumbnail_pic']))
            echo '<div class="pic"><a href="'.$item['status']['original_pic'].'"><img src="'.$item['status']['thumbnail_pic'].'" alt="" /></a></div>';?>
            <div class="info">
            <label class="l">
                <span><?=date('m月d日 H:i',strtotime($item['status']['created_at']));?></span>
                <span>来自<?=$item['status']['source'];?></span>
            </label>
            <label class="r">
                <span><a class="self" href="weibo.php?action=reposts&id=<?php echo $item['status']['idstr']; ?>">转发<? if($item['status']['reposts_count']>0)echo '('.$item['status']['reposts_count'].')';?></a></span>
                <span><a class="self" href="weibo.php?action=comments&id=<?php echo $item['status']['idstr']; ?>">评论<? if($item['status']['comments_count']>0)echo '('.$item['status']['comments_count'].')';?></a></span>
            </label>
            </div>
        </blockquote>
<?php } ?>
        <div class="info">
        <label class="l">
        <span><?=date('m月d日 H:i',strtotime($item['created_at']));?></span>
        <span>来自<?=$item['status']['source'];?></span>
        </label>
        <label class="r">
<?php if($type!='atme'){ ?>
            <span><a class="self" href="javascript:commentForm(<?php echo $i;?>,'回复@<?=$item['user']['screen_name'];?>: ','<?php echo $item['status']['idstr']; ?>')">回复</a></span>
<?php 
}else{ ?>
            <span><a class="self" href="weibo.php?action=reposts&id=<?php echo $item['idstr']; ?>">转发</a></span>
            <span><a class="self" href="weibo.php?action=comments&id=<?php echo $item['idstr']; ?>">评论</a></span>
<?php } ?>
        </label>
        </div>
    </dd>
</dl>
<div class="forms" id="form<?php echo $i;?>"></div>
<?php
$i++;
endforeach; 
endif; ?>
</div>
<script type="text/javascript">
formartCont();
</script>

</body>
</html>

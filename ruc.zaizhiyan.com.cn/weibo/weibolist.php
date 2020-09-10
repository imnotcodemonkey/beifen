<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php

session_start();

include_once( 'inc/config.php' );
include_once( 'inc/saetv2.ex.class.php' );
include_once( 'inc/wbface.php' );


$u='';
if(isset($_GET['u'])) $u=$_GET['u'];

//从CMS后台调用文章摘要和缩略图
$url='';
$pic='';
$txt='';
$url2='';
if(isset($_GET['url'])) $url=$_GET['url'];
if(isset($_GET['txt'])) $txt=mb_convert_encoding($_GET['txt'], "utf-8", "GBK");
if(isset($_GET['pic'])) $pic=mb_convert_encoding($_GET['pic'], "utf-8", "GBK");
if(isset($_GET['url2'])) $url2=$_GET['url2'];


$saveMsg='';
if(isset($_GET['saveMsg'])) $saveMsg='<p id=msg>'.$_GET['saveMsg'].'</p>';

$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
//if(isset($_GET['q'])){$uid_get = $c->end_session();}//退出


//读取微博数据 我关注的/我的微博
if($u=='all')
	$ms  = $c->home_timeline(); // done
elseif($u=='comments')
	$ms  = $c->comments_to_me();
else
	$ms  = $c->user_timeline_by_id(); // done
	//$ms  = $c->timeline_batch_by_name('北京小升初网,北京小升初论坛,八人教育');
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
//可输入字符判断
jQuery(function(){
// 批量
//$('.autoTxtCount').each(function(){
//$(this).find('.text').artTxtCount($(this).find('.tips'), 140);
//});
// 单个
	url="<?=$url;?>";
	if(url.length>10)n=130;
	else n=140;
	$('#cont').artTxtCount($('#contTip'), n);//带url为119 否则微博允许输入140字
	
	setInterval("showImg()",1000);
});
</script>
<base target="_self" />
</head>

<body>
<?php
include_once( 'inc/header.php' );
?>

<form action="save.php" name="f1" method="post" enctype="multipart/form-data" onsubmit="return check()">
    <p><textarea name="text" id="cont" rows="4"><?php echo $txt;?></textarea></p>
    <div style="<?php if(empty($url))echo 'display:none';?>;padding:5px 0 0 0"><input type="text" name="url" id="url" readonly="readonly" value="<?=$url;?>" />
    	<a href="<?php echo $url;?>" target="_blank">查看原文→</a>
    </div>
    <div id="t">
    	<label class="l">
        	<a href="###" id="face">表情</a> |
        	<a href="javascript:showPic(1)">网络图片</a> |
            <a href="javascript:showPic(2)">服务器图片</a> |
	        <a href="javascript:changweibo('<?=$url2;?>')">长微博</a> |
            <span id="contTip"></span></label>
    	<label class="r"><input id="smt" type="submit" value="发布微博" /></label>
    </div>
	<?php if(empty($pic)){?>
    <div id="btns" class="none"></div>
    <div id="showpic" class="none"></div>
	<?php }else{?>
	<div id="btns">图片地址：<input name="picname" onchange="showImg()" value="<?php echo $pic;?>" id="imgurl" /></div>
    <div id="showpic"><img src="<?php echo $pic;?>" alt="" /></div>
	<?php }?>
	<input type="hidden" name="action" value="new" />
	<script type="text/javascript">
	$('#face').sinaEmotion({
		target: $('#cont')
	});
	</script>
<?php
echo $saveMsg;
?>
</form>

<div id="list">
<?php
//print_r($ms['statuses']);exit();
if( is_array( $ms['statuses'] ) ): 
foreach( $ms['statuses'] as $item ): ?>
<dl>
	<dt><a href="http://weibo.com/<?=$item['user']['id'];?>" target="_blank"><img src="<?=$item['user']['profile_image_url'];?>" alt="" /></a></dt>
	<dd>
    	<p><a href="http://weibo.com/<?=$item['user']['id'];?>"><?=$item['user']['screen_name'];?></a><tt class="vt<?=$item['user']['verified_type'];?>"></tt>:
		<span><?=strtr($item['text'],$face);?> </span></p>
		<?php if(!empty($item['thumbnail_pic']))
		echo '<div class="pic"><a href="'.$item['original_pic'].'"><img src="'.$item['thumbnail_pic'].'" alt="" /></a></div>';?>
<?php if(!empty($item['retweeted_status'])){ ?>
        	<blockquote>
            <?php if(!empty($item['retweeted_status']['user'])){ ?>
                <p><a href="http://weibo.com/<?=$item['retweeted_status']['user']['id'];?>"><?=$item['retweeted_status']['user']['screen_name'];?></a><tt class="vt<?=$item['retweeted_status']['user']['verified_type'];?>"></tt>:
                    <span><?=$item['retweeted_status']['text'];?> </span></p>            	
				<?php if(!empty($item['retweeted_status']['thumbnail_pic']))
                echo '<div class="pic"><a href="'.$item['retweeted_status']['original_pic'].'"><img src="'.$item['retweeted_status']['thumbnail_pic'].'" alt="" /></a></div>';?>
                <div class="info">
                <label class="l">
                    <span><?=date('m月d日 H:i',strtotime($item['retweeted_status']['created_at']));?></span>
                    <span>来自<?=$item['retweeted_status']['source'];?></span>
                </label>
                <label class="r">
                    <span><a class="self" href="weibo.php?action=reposts&id=<?php echo $item['retweeted_status']['idstr']; ?>">转发<? if($item['retweeted_status']['reposts_count']>0)echo '('.$item['retweeted_status']['reposts_count'].')';?></a></span>
                    <span><a class="self" href="weibo.php?action=comments&id=<?php echo $item['retweeted_status']['idstr']; ?>">评论<? if($item['retweeted_status']['comments_count']>0)echo '('.$item['retweeted_status']['comments_count'].')';?></a></span>
                </label>
                </div>
            <?php }else echo '<p><span>'.$item['retweeted_status']['text'].'</span></p><div class="info"></div>';?>
            </blockquote>
<?php } ?>
        <div class="info">
        <label class="l">
            <span><?=date('m月d日 H:i',strtotime($item['created_at']));?></span>
            <span>来自<?=$item['source'];?></span>
        </label>
        <label class="r">
            <span><a class="self" href="weibo.php?action=reposts&id=<?php echo $item['idstr']; ?>">转发<? if($item['reposts_count']>0)echo '('.$item['reposts_count'].')';?></a></span>
            <span><a class="self" href="weibo.php?action=comments&id=<?php echo $item['idstr']; ?>">评论<? if($item['comments_count']>0)echo '('.$item['comments_count'].')';?></a></span>
        </label>
        </div>
    	<?php //print_r($item); ?>
    </dd>
</dl>
<?php endforeach; ?>
<?php endif; ?>
</div>
<script type="text/javascript">
formartCont();
</script>
</body>
</html>

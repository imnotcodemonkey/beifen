<h3 align="left">
	<div>
    <label class="l">
		帐号:<a href="weibolist.php?u=myself"><?=$user_message['screen_name'];?></a> |
        <a href="weibolist.php?u=all">我关注的</a> |
        <a href="comments.php">查看评论</a> |
        <a href="comments.php?type=atme">@我</a> |
        <a href="exit.php">退出</a>
        <a title="进入微博主页" href="http://weibo.com/<?=$uid?>/profile" target="_blank"><img src="http://res.xschu.com/img/ico/weibo.com.png" alt="" /></a>
    </label>
	<label class="r">
    	<a href="https://api.weibo.com/oauth2/authorize?client_id=<?=WB_AKEY;?>&redirect_uri=<?=urlencode(WB_CALLBACK_URL);?>&response_type=code">登录检测</a> |
    	<a href="<?php
        $refresh=$_SERVER['REQUEST_URI'];
		if(strpos($refresh,'?')<1){$refresh.='?';}

		echo $refresh.'&'.time();?>">刷新本页</a> |
        来自：<?='<a href="'.WB_URL.'" target="_blank">'.WB_WeiBa.'</a>';?>
    </label>
	</div>
</h3>
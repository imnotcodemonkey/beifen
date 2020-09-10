<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><?php include template('search', 'header'); ?>
	<div class="clr sr_body sr_list">
    	<div class="sr_main">
        	

            <div class="wrap sr_logo">
            	<a href="index.php?m=search&siteid=<?php echo $siteid;?>" class="l"><img src="<?php echo IMG_PATH;?>web/logo.png" style='margin-top: 40px;margin-right: 20px;' width="218" height="120" /></a>
                <div class="l">
                	<div class="sr_frm_box">
						<form name="search" type="get">
                        <div class="sr_frmipt">
						  <input type="hidden" name="m" value="search"/>
						  <input type="hidden" name="c" value="index"/>
						  <input type="hidden" name="a" value="init"/>
						  <input type="hidden" name="typeid" value="<?php echo $typeid;?>" id="typeid"/>
						  <input type="hidden" name="siteid" value="<?php echo $siteid;?>" id="siteid"/>
						<input type="text" name="q" class="ipt" id="q" value="<?php echo $search_q;?>">
						<div class="sp" id="aca">▼</div><input type="submit" class="ss_btn" value="搜 索"></div>
						</form>
						<div id="sr_infos" class="wrap sr_infoul">
						</div>
                    </div>
                  
                </div>
            </div>
            <div class="brd1s" style='margin-top: 20px;'></div>
             <div style='margin-top: 50px;font-size: 30px;' class="jg">获得约 <?php echo $totalnums;?> 条结果 （用时<?php echo sprintf("%01.2f", $execute_time);?> 秒）</div>
      </div>
	  
<script type="text/javascript" src="<?php echo JS_PATH;?>search_history.js"></script>
<?php if($setting['suggestenable']) { ?>
<script type="text/javascript" src="<?php echo JS_PATH;?>jquery.suggest.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>search_suggest.js"></script>
<?php } ?>


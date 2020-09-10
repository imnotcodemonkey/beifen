<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><div class="gg" >
	
				<style>
						.wrap2 { position: relative; width: 1200px; margin: 0 auto;margin-top: 20px;}
					.centered-btns_nav {
						position: absolute;
						top: 50%;
						z-index: 2;
						width: 38px;
						height: 61px;
						margin-top: -40px;
						line-height: 200px;
						background-image: url(images/arrow.gif);
						overflow: hidden;
						opacity: .7;
					}
					.next {
						right: 0;
						background-position: right 0;
					}

					.centered-btns_nav:active {
						opacity: 1;
					}

					.centered-btns_tabs {
						margin-top: 15px;
						text-align: center;
						font-size: 0;
						list-style-type: none;
					}

					.centered-btns_tabs li {
						display: inline-block;
						margin: 0 3px;
						*display: inline;
						*zoom: 1;
					}

					.centered-btns_tabs a {
						display: inline-block;
						width: 9px;
						height: 9px;
						border-radius: 50%;
						line-height: 20px;
						background-color: rgba(0, 0, 0, .3);
						background-color: #ccc\9;
						overflow: hidden;
						*display: inline;
						*zoom: 1;
					}

					.centered-btns_tabs .centered-btns_here a {
						background-color: rgba(0, 0, 0, .8);
						background-color: #666\9;
					}
				</style>
				<div style='clear:both;'></div>
				<div class="wrap2">
					<ul class="rslides" id="dowebok2">
						<li><a href="/xueyuan/gongguanxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g1.jpg" alt=""></a></li>
						<li><a href="/xueyuan/guoxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g2.jpg" alt=""></a></li>
						<li><a href="/xueyuan/jiaoyuxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g3.jpg" alt=""></a></li>
						<li><a href="/xueyuan/ldrsxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g4.jpg" alt=""></a></li>
						<li><a href="/xueyuan/lishixueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g5.jpg" alt=""></a></li>
						<li><a href="/xueyuan/mkszyxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g6.jpg" alt=""></a></li>
						<li><a href="/xueyuan/shangxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g7.jpg" alt=""></a></li>
						<li><a href="/xueyuan/shyrkxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g8.jpg" alt=""></a></li>
						<li><a href="/xueyuan/tongjixueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g9.jpg" alt=""></a></li>
						<li><a href="/xueyuan/waiguoyuxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g10.jpg" alt=""></a></li>
						<li><a href="/xueyuan/xinwenxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g11.jpg" alt=""></a></li>
						<li><a href="/xueyuan/xinxixueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g12.jpg" alt=""></a></li>
						<li><a href="/xueyuan/yishuxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/g13.jpg" alt=""></a></li>
					</ul>
				</div>
				<div style='clear:both;'></div>
				<script>
					$(function() {
						$('#dowebok2').responsiveSlides({
							auto: true,
							pager: true,
							nav: true,
							namespace: 'centered-btns',
						});
					});
				</script>
			</div>
<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
		<title>中国人民大学课程研修班招生信息网</title>
		<link href="<?php echo CSS_PATH;?>web/index.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSS_PATH;?>web/qiehuan.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSS_PATH;?>web/5.css" rel="stylesheet" type="text/css" />
		<link rel="alternate" media="only screen and (max-width: 640px)" href="http://m.rucyan.cn">
		<script src="<?php echo JS_PATH;?>web/jquery-2.1.4.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo JS_PATH;?>search_common.js"></script>
		<style type="text/css">
			* {
				margin: 0;
				padding: 0;
			}

			img {
				display: block;
				border: none;
			}

			ul,
			li {
				list-style: none;
			}

			.lubomc {
				width: 100%;
				min-width: 1000px;
				clear: both;
				position: relative;
				height: 400px;
			}

			.lubomc_box {
				position: relative;
				width: 100%;
				height: 368px;
			}

			.lubomc_box li {
				float: left;
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 400px;
				opacity: 0;
				filter: alpha(opacity=0);
			}

			.lubomc_box li a {
				display: block;
				width: 100%;
				height: 100%;
			}

			.lubomc_box li img {
				width: 100%;
				height: 400px;
			}

			/*圆点*/
			.cir_box {
				overflow: hidden;
				position: absolute;
				z-index: 100;
			}

			.cir_box li {
				float: left;
				width: 30px;
				height: 5px;
				margin: 0 5px;
				cursor: pointer;
				background: #fff;
				opacity: 0.8;
				filter: alpha(opacity=80);
			}

			.cir_on {
				background: #000 !important;
			}

			/*按钮*/
			.lubomc_btn {
				position: absolute;
				width: 100%;
				top: 140px;
			}

			.left_btn,
			.right_btn {
				width: 30px;
				height: 80px;
				background: #000;
				opacity: 0.8;
				filter: alpha(opacity=80);
				cursor: pointer;
				color: #fff;
				line-height: 80px;
				font-size: 30px;
				text-align: center;
				-webkit-user-select: none;
				-moz-user-select: none;
				-ms-user-select: none;
				user-select: none;
			}

			.left_btn {
				float: left;
			}

			.right_btn {
				float: right;
			}
		</style>
		<link rel="stylesheet" href="<?php echo CSS_PATH;?>web/responsiveslides.css">
		<style>
		.wrap { position: relative; width: 1920px; margin: 0 auto;}
		#slider-pager { position: absolute; bottom: 15px;right:50px; z-index: 10; width: 100%; margin: 0; padding: 0; text-align: right; list-style-type: none;}
		#slider-pager li { display: inline-block; margin-left: 10px;width:5px;height:5px; border: 2px solid #999; *display: inline; *zoom: 1;}
		#slider-pager li a{display: inline-block; width:5px;height:5px; vertical-align: top;}
		#slider-pager .rslides_here { border: 2px solid #fff; color:#fff;}
		
		</style>
	</head>
	<body style="overflow-x:hidden">
		<div class="head-wrap">
			<div class="header">
				<a href="/" class="logo"><img src="<?php echo IMG_PATH;?>web/logo.png" alt="中国人民大学在职研究生招生简章-中国人民大学在职研究生招生网" /> </a>

				<form action="<?php echo APP_PATH;?>index.php" method="get" target="_blank">
					<div class="search">
						<input type="hidden" name="kwtype" value="0" />
						<input type="hidden" name="m" value="search" />
						<input type="hidden" name="c" value="index" />
						<input type="hidden" name="a" value="init" />
						<input type="hidden" name="typeid" value="<?php echo $typeid;?>" id="typeid" />
						<input type="hidden" name="siteid" value="<?php echo $siteid;?>" id="siteid" />
						<input name="q" ype="text" class="search-keyword" id="search-keyword" value="在这里搜索..." onfocus="if(this.value=='在这里搜索...'){this.value='';}"
						 onblur="if(this.value==''){this.value='在这里搜索...';}" />
						<button type="submit" class="search-submit" style="width:88px; height:36px; background-image:url(<?php echo IMG_PATH;?>web/search-botton.png); border-top:#000000 solid 1px;border-radius:5px; border-left:#000000 solid 1px;"></button>

					
					</div>
				</form>
				<style>
					.header .phone-box{position:absolute; right:10px; top:38px;}
        </style>
<div class="phone-box">
					<img src="<?php echo IMG_PATH;?>web/phone.png" />
					<div>中诚仕通教育科技（北京）有限公司</div>
				</div>
			</div>
		</div>
		<div class="nav-wrap">
			<div class="nav">
				<ul>
					<li><a class="hp" href="/">学校首页</a></li>
					<li class='lix'><a href="/xueyuan">学院介绍</a></li>
					<li class='lix'><a href="/jianzhang">专业简章</a></li>
					<li class='lix'><a href="/contact">联系我们</a></li>

					<li class="enrol lix"><a target="_blank" href="/baoming">在线咨询</a></li>
				</ul>
			</div>
		</div>
		<div class="kePublic">
			<div class="wrap">
				<ul class="rslides" id="dowebok">
					
					<li><a href="/xueyuan/shangxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/a3.jpg" alt=""></a></li>
					<li><a href="/xueyuan/xinwenxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/a4.jpg" alt=""></a></li>
					<li><a href="/xueyuan/gongguanxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/c1.jpg" alt=""></a></li>
					<li><a href="/xueyuan/guoxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/c2.jpg" alt=""></a></li>
					<li><a href="/xueyuan/jiaoyuxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/c3.jpg" alt=""></a></li>
					<li><a href="/xueyuan/ldrsxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/c4.jpg" alt=""></a></li>
					<li><a href="/xueyuan/lishixueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/c5.jpg" alt=""></a></li>
					<li><a href="/xueyuan/mkszyxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/c6.jpg" alt=""></a></li>
					<li><a href="/xueyuan/shyrkxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/c7.jpg" alt=""></a></li>
					<li><a href="/xueyuan/tongjixueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/c8.jpg" alt=""></a></li>
					<li><a href="/xueyuan/waiguoyuxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/c9.jpg" alt=""></a></li>
					<li><a href="/xueyuan/xinxixueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/c10.jpg" alt=""></a></li>
					<li><a href="/xueyuan/yishuxueyuan/" target="_blank"><img src="<?php echo IMG_PATH;?>web/c11.jpg" alt=""></a></li>
					
				</ul>
				<ul id="slider-pager">
					<li ><a href="#"></a></li>
					<li ><a href="#"></a></li>
					<li ><a href="#"></a></li>
					<li ><a href="#"></a></li>
					<li ><a href="#"></a></li>
					<li ><a href="#"></a></li>
					<li ><a href="#"></a></li>
					<li ><a href="#"></a></li>
					<li ><a href="#"></a></li>
					<li ><a href="#"></a></li>
					<li ><a href="#"></a></li>
					<li ><a href="#"></a></li>
					<li ><a href="#"></a></li>
					
				</ul>
			</div>
			<script src="<?php echo JS_PATH;?>web/responsiveslides.js"></script>
			<script>
				$(function() {
					$('#dowebok').responsiveSlides({
						manualControls: '#slider-pager'
					});
				});
			</script>
			<div class="pm-wrap">
				<div class="pm">
					<h2>热门专业：</h2>
					<ul>
						<li><a target="_blank" href="/jianzhang/1.html">国际新闻传播</a></li>
						<li><a target="_blank" href="/jianzhang/40.html">美术学</a></li>

						<li><a target="_blank" href="/jianzhang/43.html">影视从业者权利方向</a></li>
						<li><a target="_blank" href="/jianzhang/7.html">企业管理</a></li>

						<li><a target="_blank" href="/jianzhang/3.html">古代文学</a></li>
						<li><a target="_blank" href="/jianzhang/28.html">管理社会学</a></li>

					</ul>
					<a rel="nofollow" class="more" href="/jianzhang" target="_blank">更多>></a>
				</div>
			</div>
			<div style='clear:both;'></div>
			<div class="information-wrap">
				<div class="information">
					<div style='float:left;'>
						<div class="l">
							<div class="lanrenzhijia">
								<div class="tab"> <a href="javascript:;" class="on">专业简章</a> </div>
								<div class="content">
									<ul>
										<li style="display:block;" class="li-1"> <a target="_blank" href="/jianzhang" rel="nofollow">
												<div class="tp"></div>
											</a>
											<p style="width:342px; overflow:hidden;"><a target="_blank" href="/jianzhang/2.html" title="传播学专业简章">传播学专业简章</a></p>
											<p style="width:342px; overflow:hidden;"><a target="_blank" href="/jianzhang/7.html" title="企业管理专业简章">企业管理专业简章</a></p>
											<p style="width:342px; overflow:hidden;"><a target="_blank" href="/jianzhang/11.html" title="会计学专业简章">会计学专业简章</a></p>
											<p style="width:342px; overflow:hidden;"><a target="_blank" href="/jianzhang/12.html" title="统计学专业简章">统计学专业简章</a></p>
											<p style="width:342px; overflow:hidden;"><a target="_blank" href="/jianzhang/4.html" title="人力资源管理专业简章">人力资源管理专业简章</a></p>

											<div class="more"><a rel="nofollow" href="/jianzhang" target="_blank">查看更多</a></div>
										</li>

									</ul>
								</div>
							</div>
						</div>
						</div>
								</form>
								<style>
									.header .phone-box{position:absolute; right:10px; top:38px;}
						</style>
						<!--link begin-->
						<!--link end-->
						<!--footer开始-->
						<!--footer结束-->
						<!--banner js begin-->

						<!--  左边切换 结束-->
						<a rel="nofollow" href='' target='_blank'>
							<div class="r"></div>
						</a>
					</div>
					<div style='clear:both;'></div>
				</div>
			</div>
			
				<?php include template("content","gg"); ?>
			
			<div class="Brochures-wrap">
				<div class="Brochures">
					<div class="left">
						<div class="title"> <a class="zs">简章</a> <span>Enrollment Guide<a rel="nofollow" class="more" href="/jianzhang"
								 target="_blank">更多>></a></span> </div>
						<dl class="l">
							<dt><a>商学院</a></dt>
							<dd><a style=" " href="/jianzhang/8.html" title="企业管理课程研修班招生简章">企业管理课程研修班</a> <a href='/jianzhang/8.html' target="_blank"><span
									 style="float:right;">了解详情</span></a></dd>
							<dd><a style=" " href="/jianzhang/11.html" title="会计学课程研修班招生简章">会计学课程研修班</a> <a href='/jianzhang/11.html' target="_blank"><span
									 style="float:right;">了解详情</span></a></dd>
							<dt><a>劳动人事学院</a></dt>
							<dd><a style=" " href="/jianzhang/4.html" title="人力资源管理课程研修班招生简章">人力资源管理课程研修班</a> <a href='/jianzhang/4.html'
								 target="_blank"><span style="float:right;">了解详情</span></a></dd>
							<dt><a>统计学院</a></dt>
							<dd><a style=" " href="/jianzhang/12.html" title="统计学课程研修班招生简章">统计学课程研修班</a> <a href='/jianzhang/12.html' target="_blank"><span
									 style="float:right;">了解详情</span></a></dd>
							<dt><a>艺术学院</a></dt>
							<dd><a style=" " href="/jianzhang/40.html" title="美术学课程研修班招生简章">美术学课程研修班</a> <a href='/jianzhang/40.html' target="_blank"><span
									 style="float:right;">了解详情</span></a></dd>
							<dd><a style=" " href="/jianzhang/41.html" title="艺术学课程研修班招生简章">艺术学课程研修班</a> <a href='/jianzhang/41.html' target="_blank"><span
									 style="float:right;">了解详情</span></a></dd>
							<dd><a style=" " href="/jianzhang/42.html" title="设计艺术学课程研修班招生简章">设计艺术学课程研修班</a> <a href='/jianzhang/42.html'
								 target="_blank"><span style="float:right;">了解详情</span></a></dd>
							<dd><a style=" " href="/jianzhang/39.html" title="音乐学课程研修班招生简章">音乐学课程研修班</a> <a href='/jianzhang/39.html' target="_blank"><span
									 style="float:right;">了解详情</span></a></dd>
							<dt><a>社会与人口学院</a></dt>
							<dd><a style=" " href="/jianzhang/28.html" title="社会学课程研修班招生简章">社会学课程研修班</a> <a href='/jianzhang/28.html' target="_blank"><span
									 style="float:right;">了解详情</span></a></dd>
							<dt><a>教育学院</a></dt>
							<dd><a style=" " href="/jianzhang/45.html" title="行政管理课程研修班招生简章">行政管理课程研修班</a> <a href='/jianzhang/45.html'
								 target="_blank"><span style="float:right;">了解详情</span></a></dd>
							<dt><a>马克思主义学院</a></dt>
							<dd><a style=" " href="/jianzhang/26.html" title="思想政治教育课程研修班招生简章">思想政治教育课程研修班</a> <a href='/jianzhang/26.html'
								 target="_blank"><span style="float:right;">了解详情</span></a></dd>
							<dt><a>国学院</a></dt>
							<dd><a style=" " href="/jianzhang/3.html" title="国学专业课程研修班招生简章">国学专业课程研修班</a> <a href='/jianzhang/3.html' target="_blank"><span
									 style="float:right;">了解详情</span></a></dd>



						</dl><dl class="r">
							<dt><a>新闻学院</a></dt>
							<dd><a style=" " href="/jianzhang/2.html" title="传播学课程研修班招生简章">传播学课程研修班</a> <a href='/jianzhang/2.html' target="_blank"><span
									 style="float:right;">了解详情</span></a></dd>
							<dd><a style=" " href="/jianzhang/1.html" title="新闻学课程研修班招生简章">新闻学课程研修班</a> <a href='/jianzhang/1.html' target="_blank"><span
									 style="float:right;">了解详情</span></a></dd>
							<dt><a>公共管理学院</a></dt>
							<dd><a style=" " href="/jianzhang/37.html" title="公共财政与公共政策课程研修班招生简章">公共财政与公共政策课程研修班</a> <a href='/jianzhang/37.html'
								 target="_blank"><span style="float:right;">了解详情</span></a></dd>
							<dd><a style=" " href="/jianzhang/35.html" title="城乡发展与规划课程研修班招生简章">城乡发展与规划课程研修班</a> <a href='/jianzhang/35.html'
								 target="_blank"><span style="float:right;">了解详情</span></a></dd>
							<dd><a style=" " href="/jianzhang/38.html" title="行政管理课程研修班招生简章">行政管理课程研修班</a> <a href='/jianzhang/38.html'
								 target="_blank"><span style="float:right;">了解详情</span></a></dd>
							<dd><a style=" " href="/jianzhang/34.html" title="社会医学与卫生事业管理课程研修班招生简章">社会医学与卫生事业管理课程研修班</a> <a href='/jianzhang/34.html'
								 target="_blank"><span style="float:right;">了解详情</span></a></dd>
							<dd><a style=" " href="/jianzhang/33.html" title="房地产经济与管理课程研修班招生简章">房地产经济与管理课程研修班</a> <a href='/jianzhang/33.html'
								 target="_blank"><span style="float:right;">了解详情</span></a></dd>
							<dt><a>历史学院</a></dt>
							<dd><a style=" " href="/jianzhang/19.html" title="中国考古文博学课程研修班招生简章">中国考古文博学课程研修班</a> <a href='/jianzhang/19.html'
								 target="_blank"><span style="float:right;">了解详情</span></a></dd>
							<dd><a style=" " href="/jianzhang/18.html" title="世界史课程研修班招生简章">世界史课程研修班</a> <a href='/jianzhang/18.html' target="_blank"><span
									 style="float:right;">了解详情</span></a></dd>
							<dd><a style=" " href="/jianzhang/20.html" title="中国古代史课程研修班招生简章">中国古代史课程研修班</a> <a href='/jianzhang/20.html'
								 target="_blank"><span style="float:right;">了解详情</span></a></dd>
							
						
						
										
				<dt><a>外国语学院</a></dt>
					<dd><a style=" " href="/jianzhang/13.html" title="英语语言文学课程研修班招生简章">英语语言文学课程研修班</a> <a href='/jianzhang/13.html'
						 target="_blank"><span style="float:right;">了解详情</span></a></dd>
					<dd><a style=" " href="/jianzhang/17.html" title="日语语言文学课程研修班招生简章">日语语言文学课程研修班</a> <a href='/jianzhang/17.html'
						 target="_blank"><span style="float:right;">了解详情</span></a></dd>
					<dt><a>信息学院</a></dt>
					<dd><a style=" " href="/jianzhang/22.html" title="管理科学与工程课程研修班招生简章">管理科学与工程课程研修班</a> <a href='/jianzhang/22.html'
						 target="_blank"><span style="float:right;">了解详情</span></a></dd>
					<dd><a style=" " href="/jianzhang/21.html" title="计算机应用技术课程研修班招生简章">计算机应用技术课程研修班</a> <a href='/jianzhang/21.html'
						 target="_blank"><span style="float:right;">了解详情</span></a></dd>
					<dt><a>法学院</a></dt>
					<dd><a style=" " href="/jianzhang/44.html" title="民商法课程研修班招生简章">民商法课程研修班</a> <a href='/jianzhang/44.html' target="_blank"><span
							 style="float:right;">了解详情</span></a></dd>
					<dd><a style=" " href="/jianzhang/43.html" title="知识产权法课程研修班招生简章">知识产权法课程研修班</a> <a href='/jianzhang/43.html'
						 target="_blank"><span style="float:right;">了解详情</span></a></dd>
				
				
				
				
				
				
				</dl>
				</div>
				<div class="right">
						<div class="bai">
							<div class="title"> <span class="kszn">学院介绍</span> <span class="yw"> College Introduction</span> </div>
						</div>

						<ul class="guide">
							<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=fcd7649819a25b4e3b884c86c2eb8b73&action=category&catid=9&num=25\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'category')) {$data = $content_tag->category(array('catid'=>'9','limit'=>'25',));}?>
							<?php $n=1; if(is_array($data)) foreach($data AS $key => $val) { ?>
							<li><a target="_blank" href="<?php echo $val['url'];?>"><?php echo $val['catname'];?></a></li>
							<?php $n++;}unset($n); ?>
							<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
						</ul>
					</div>
					<div class="tu">
						<?php include template("content","cebian"); ?>
					</div>
					<div style='clear:both;'></div>

				</div>
			</div>
			
			<?php include template("content","footer"); ?>

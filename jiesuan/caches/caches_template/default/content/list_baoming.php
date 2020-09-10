<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
	<title>在线报名-中国人民大学课程研修班招生信息网</title>
	
		<link href="<?php echo CSS_PATH;?>web/index.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSS_PATH;?>web/qiehuan.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSS_PATH;?>web/5.css" rel="stylesheet" type="text/css" />
		<link rel="alternate" media="only screen and (max-width: 640px)" href="http://m.rucyan.cn">
		<script src="<?php echo JS_PATH;?>web/jquery-2.1.4.min.js" type="text/javascript"></script>

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
	</head>
	<body style="overflow-x:hidden">


		<div class="head-wrap">
			<div class="header">
				<a href="/" class="logo"><img src="<?php echo IMG_PATH;?>web/logo.png" alt="中国人民大学在职研究生招生简章-中国人民大学在职研究生招生网" /> </a>
				 <form action="<?php echo APP_PATH;?>index.php" method="get" target="_blank">
									<div class="search">
										<input type="hidden" name="kwtype" value="0" />
										<input type="hidden" name="m" value="search"/>
										<input type="hidden" name="c" value="index"/>
										<input type="hidden" name="a" value="init"/>
										<input type="hidden" name="typeid" value="<?php echo $typeid;?>" id="typeid"/>
										<input type="hidden" name="siteid" value="<?php echo $siteid;?>" id="siteid"/>
										<input name="q" ype="text" class="search-keyword" id="search-keyword" value="在这里搜索..." onfocus="if(this.value=='在这里搜索...'){this.value='';}"
										 onblur="if(this.value==''){this.value='在这里搜索...';}" />
										<button type="submit" class="search-submit" style="width:88px; height:36px; background-image:url(<?php echo IMG_PATH;?>web/search-botton.png); border-top:#000000 solid 1px;border-radius:5px; border-left:#000000 solid 1px;"></button>
				
									</div>
								</form>
				<style>
					.header .phone-box {
						position: absolute;
						right: 10px;
						top: 38px;
					}
				</style>
				<div class="phone-box">
					<img src="<?php echo IMG_PATH;?>web/phone.png" />
					<?php include template("content","company"); ?>
				</div>
			</div>
		</div>
		<div class="nav-wrap">
			<div class="nav">
				<ul>
					<li><a href="/">学校首页</a></li>
					<li class='lix'><a href="/xueyuan">院系专业</a></li>
					<li class='lix'><a  href="/jianzhang">专业简章</a></li>
					<li class='lix'><a href="/contact">联系我们</a></li>

					<li class="enrol lix"><a  class="hp" target="_blank" href="/baoming">在线咨询</a></li>
				</ul>
			</div>
		</div>

		<div class="Brochures-wrap">
			<div class="Brochures">
				<div class="left">
					<div class="title" style='text-align: left;margin-bottom:30px;color:#9f1e31;font-size: 30px;line-height: 70px;'>在线报名</div>
					<div class="biaodan">
						
						<span class="baomingForm" id="baomingFrom">
							<div class="biaodan-xq">
								<form action="http://www.ruczcst.com/index.php?m=formguide&c=index&a=show&formid=13&siteid=1" method="post">
									<p>姓&emsp;&emsp;名：
										<input class="ipt must" type="text" name="name" value="" rel="请输入您的真实姓名！" id="sname" />
										<span></span></p>
									<p>性&emsp;&emsp;别：
										<select name="sex">
											<option>请选择</option>
											<option value="男" id="sex1">男</option>
											<option value="woman">女</option>
										</select>
										<span></span> </p>
									<p>最后学历：
										<select name="xueli">
											<option>请选择</option>
											<option value="benke">本科</option>
											<option value="zhuanke">专科</option>
											<option value="gaozhong">高中</option>
											<option value="zikaobenke">自考本科</option>
											<option value="zaidu">在读</option>
											<option value="qita">其它</option>
										</select>
										<span></span> </p>
									<p>毕业时间：
										<input type="text" name="biyeshijian" />
										<span>*</span></p>
									<p>学&emsp;&emsp;位：
										<select name="xuewei">
											<option>请选择</option>
											<option value="yes">有</option>
											<option value="no">无</option>
										</select>
										<span></span> </p>
									<p>联系电话：
										<input class="ipt must" type="text" name="phone" value="" rel="请输入您的常用手机号！" id="phone" />
										<span>*</span></p>
									<p>专业方向：
										<input class="ipt must" type="text" name="zhuanye" rel="请输入您要报名学习的专业或方向！" value="" id="subject" />
										<span></span> </p>
									<p>电子邮箱：
										<input type="text" name="email" />
										<span></span></p>
									<p>所属行业：
										<input type="text" name="hangye" placeholder="" />
									</p>
									<p class="last"><span>附加说明：</span>
										<textarea class="ipt" name="fujia" id="beizhu"></textarea>
									</p>
							</div>
							<div class="tijiao">
								<input type="submit" name="dosubmit" id="dosubmit" value=" 提交 " />
							</div>
						</span>
						</form>


						<script type="text/javascript">
							$('body').addClass("baomingPage");
						</script>
					</div>


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
					<img src="<?php echo IMG_PATH;?>web/yishu.jpg" alt="">
					<img src="<?php echo IMG_PATH;?>web/lishi.jpg" alt="">
				</div>
				<div style='clear:both;'></div>

			</div>
		</div>
		<?php include template("content","footer"); ?>

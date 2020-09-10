
	/*
	 *  在jquery.suggest 1.1基础上针对中文输入的特点做了部分修改，下载原版请到jquery插件库
	 *  修改者：wangshuai
	 *
	 *  修改部分已在文中标注
	 *
	 *
	 *	jquery.suggest 1.1 - 2007-08-06
	 *
	 *	Uses code and techniques from following libraries:
	 *	1. http://www.dyve.net/jquery/?autocomplete
	 *	2. http://dev.jquery.com/browser/trunk/plugins/interface/iautocompleter.js
	 *
	 *	All the new stuff written by Peter Vulgaris (www.vulgarisoip.com)
	 *	Feel free to do whatever you want with this file
	 *
	 */

	(function($) {

		$.suggest = function(input, options) {
			var $input = $(input).attr("autocomplete", "off");
			var $results = $("#sr_infos");

			var timeout = false;		// hold timeout ID for suggestion results to appear
			var prevLength = 0;			// last recorded length of $input.val()

			$input.blur(function() {
				setTimeout(function() { $results.hide() }, 200);
			});

			$results.mouseover(function() {
				$("#sr_infos ul li").removeClass(options.selectClass);
			})

			// help IE users if possible
			try {
				$results.bgiframe();
			} catch(e) { }

			// I really hate browser detection, but I don't see any other way

//修改开始
//下面部分在作者原来代码的基本上针对中文输入的特点做了些修改
			if ($.browser.mozilla)
				$input.keypress(processKey2)
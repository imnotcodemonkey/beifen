/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.editorConfig = function( config )
{
    // Define changes to default configuration here. For example:
    config.language = 'zh-cn';
    //config.uiColor = '#F1F5F2';
    // file browse
	config.filebrowserBrowseUrl      = '../include/dialog/browse_soft.php';
	config.filebrowserUploadUrl      = '../include/dialog/post_other.php';
	config.filebrowserLinkBrowseUrl  = '../include/dialog/browse_soft.php';
	config.filebrowserLinkUploadUrl  = '../include/dialog/post_other.php';
	config.filebrowserImageBrowseUrl = "../include/dialog/browse_images.php";
	config.filebrowserImageUploadUrl = "../include/dialog/post_images.php";
	config.filebrowserFlashBrowseUrl = "../include/dialog/browse_flash.php";
	config.filebrowserFlashUploadUrl = "../include/dialog/post_flash.php";

    config.smiley_path   = '/res/emoticons/';
	config.smiley_images = [
//	'regular_smile.gif','sad_smile.gif','wink_smile.gif','teeth_smile.gif','confused_smile.gif','tounge_smile.gif',
//	'embaressed_smile.gif','omg_smile.gif','whatchutalkingabout_smile.gif','angry_smile.gif','angel_smile.gif','shades_smile.gif',
//	'devil_smile.gif','cry_smile.gif','lightbulb.gif','thumbs_down.gif','thumbs_up.gif','heart.gif',
//	'broken_heart.gif','kiss.gif','envelope.gif'
	'1.gif', '2.gif', '3.gif', '4.gif', '5.gif', '6.gif', '7.gif', '8.gif', '9.gif', '10.gif', 
	'11.gif', '12.gif', '13.gif', '14.gif', '15.gif', '16.gif', '17.gif', '18.gif', '19.gif', '20.gif', 
	'21.gif', '22.gif', '23.gif', '24.gif', '25.gif', '26.gif', '27.gif', '28.gif', '29.gif', '30.gif', 
	'31.gif', '32.gif', '33.gif', '34.gif', '35.gif', '36.gif', '37.gif', '38.gif', '39.gif', '40.gif', 
	'41.gif', '42.gif', '43.gif', '44.gif', '45.gif', '46.gif', '47.gif', '48.gif', '49.gif', '50.gif', 
	'51.gif', '52.gif', '53.gif', '54.gif', '55.gif', '56.gif', '57.gif', '58.gif', '59.gif', '60.gif', 
	'61.gif', '62.gif', '63.gif', '64.gif', '65.gif', '66.gif', '67.gif', '68.gif', '69.gif', '70.gif', 
	'71.gif', '72.gif', '73.gif', '74.gif', '75.gif', '76.gif', '77.gif', '78.gif', '79.gif', '80.gif', 
	'81.gif', '82.gif', '83.gif', '84.gif', '85.gif', '86.gif', '87.gif', '88.gif', '89.gif', '90.gif', 
	'91.gif', '92.gif', '93.gif', '94.gif', '95.gif', '96.gif', '97.gif', '98.gif', '99.gif', '100.gif', 
	'101.gif', '102.gif', '103.gif', '104.gif', '105.gif', '106.gif', '107.gif', '108.gif', '109.gif', '110.gif', 
	'111.gif', '112.gif', '113.gif', '114.gif', '115.gif', '116.gif', '117.gif', '118.gif', '119.gif', '120.gif', 
	'121.gif', '122.gif', '123.gif', '124.gif', '125.gif', '126.gif', '127.gif', '128.gif', '129.gif', '130.gif', 
	'131.gif', '132.gif', '133.gif', '134.gif'
	];
	config.smiley_descriptions = [
	//	'smiley', 'sad', 'wink', 'laugh', 'frown', 'cheeky', 'blush', 'surprise',
	//	'indecision', 'angry', 'angel', 'cool', 'devil', 'crying', 'enlightened', 'no',
	//	'yes', 'heart', 'broken heart', 'kiss', 'mail'
	];

	config.font_names = '宋体;楷体_GB2312;仿宋_GB2312;新宋体;黑体;隶书;幼圆;微软雅黑;华文行楷;华文隶书;';
	config.font_names+= 'Tahoma/Tahoma, Geneva, sans-serif;Verdana/Verdana, Geneva, sans-serif;';
	config.font_names+= 'Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS, cursive;';
	config.font_names+= 'Courier New/Courier New, Courier, monospace;Georgia/Georgia, serif;';
	config.font_names+= 'Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif';

	//config.templates_replaceContent = false;
	config.undoStackSize =500;
	
	//是否转换一些难以显示的字符为相应的HTML字符 plugins/entities/plugin.js
	config.entities_greek = true;
	config.entities = true;
	
	config.templates_replaceContent = false;
	
	config.autoParagraph = false;
//    config.enterMode = CKEDITOR.ENTER_P;
//	config.shiftEnterMode = CKEDITOR.ENTER_BR; 
};

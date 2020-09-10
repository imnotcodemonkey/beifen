<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $cfg_soft_lang; ?>">
<title>文档管理</title>
<link rel="stylesheet" type="text/css" href="css/base.css">
<script language="javascript" src="../include/js/dedeajax2.js"></script>
<script language="javascript" src="js/ieemu.js"></script>
<script language="javascript" src="js/context_menu.js"></script>
<script language="javascript" src="js/main.js"></script>
<script language="javascript" src="js/list.js"></script>
<script type="text/javascript" src="http://i.haiwen.net/js/jquery.min.js"></script>
<script type="text/javascript">
u=window.location.href;if(u.indexOf('?')<1) {u+='?'; }
if(window.location.href.indexOf('orderby')<1){window.location.href=u+'&orderby=pubdate'}

function showTack(aid){
	$('tr.tacklist').hide();

	if($('#tacklist'+aid+' td').html()==''){
		f='<table border="0" cellpadding="2" cellspacing="1" bgcolor="#D6D6D6" width=100%>\
	<tr align="center" bgcolor="#FBFCE2">\
    <td width="110">时间</td>\
    <td width="90">访问IP</td>\
    <td>访问页</td>\
    <td>来路页面</td>\
	</tr>{val}</table>';
		$.get('ajax_tack.php',{id:aid}, function(data, textStatus){
			$('#tacklist'+aid+' td').html(f.replace('{val}',data));
			//alert(data);
		});
	}
	if($('#tacklist'+aid).hasClass('none')){
		$('#tacklist'+aid).show();
		$('#tacklist'+aid).removeClass('none');
	}else{
		$('#tacklist'+aid).hide();
		$('#tacklist'+aid).addClass('none');
	}
}
function showWriter(t,w){
	if(t==w){document.write(w)}
	else{document.write('<span style="color:red" title="创建人：'+t+'">'+w+'</span>')}
}
</script>
<style type="text/css">
.none{display:none}
.tacklist{ background:#FFF}
.tack a{ font:bold 16px/150% Verdana; color:#06C}
.tit div{text-align:left; height:18px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; display:block;}
.cat{ height:18px; overflow:hidden; width:72px;}
.reffer {text-align:left;}
.reffer a{ float:left; margin-top:3px; padding:0 4px}
</style>
</head>
<body leftmargin="8" topmargin="8" background='images/allbg.gif' onLoad="ContextMenu.intializeContextMenu()">

<!--  快速转换位置按钮  -->
<table width="98%" border="0" cellpadding="0" cellspacing="1" bgcolor="#ccd9b9" align="center">
<tr>
 <td height="26" background="images/newlinebg3.gif">
  <table width="98%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td align="center">
  <input type='button' class="coolbg np" onClick="location='catalog_do.php?channelid=<?php echo $GLOBALS['channelid']; ?>&cid=<?php echo $GLOBALS['cid']; ?>&dopost=addArchives';" value='添加文档' />
  <?php if($adminid==$mid){ ?>
	<input type='button' class="coolbg np" onClick="location='content_list.php?cid=<?php echo $GLOBALS['cid']; ?>&mid=0';" value='全部文档' />
	<?php
}
else{
?>
	<input type='button' class="coolbg np" onClick="location='content_list.php?cid=<?php echo $GLOBALS['cid']; ?>&mid=<?php echo $cuserLogin->getUserID(); ?>';" value='我的文档' />
	<?php }  echo $GLOBALS['CheckUserSend']; ?>
 <input type='button' class="coolbg np" onClick="location='catalog_main.php';" value='栏目管理' />
 <input type='button' class="coolbg np" name='bb1' onClick="location='makehtml_list.php?cid=<?php echo $GLOBALS['cid']; ?>';" value='更新列表' />
 <input type='button' class="coolbg np" name='bb2' onClick="location='makehtml_archives.php?cid=<?php echo $GLOBALS['cid']; ?>';" value='更新文档' />
 <input type='button' class="coolbg np" name='bb1' onClick="location='recycling.php?cid=<?php echo $GLOBALS['cid']; ?>';" value='文章回收站' />
 </td>
 </tr>
</table>
</td>
</tr>
</table>
  
<!--  内容列表   -->
<form name="form2">
<table border="0" cellpadding="2" cellspacing="1" bgcolor="#CFCFCF" style="margin-top:8px;">
<tr bgcolor="#E7E7E7" >
	<td height="28" colspan="11" background="images/tbg.gif" style="padding-left:10px;">
	◆ <?php echo $GLOBALS['positionname']; ?>文档列表 &nbsp;(使用鼠标右键弹出菜单)
	| <a href="#" onclick="window.location.href=window.location.href.replace(/orderb/gi,'')+'&orderby=pubdate'" style="color:red">按对外时间排序</a>
	| <a href="#" onclick="window.location.href=window.location.href.replace(/orderb/gi,'')+'&orderby=senddate'" style="color:green">按首次录入时间排序</a>
	| <a href="#" onclick="window.location.href=window.location.href.replace(/orderb/gi,'')+'&orderby=tackid'" style="color:green">来路次数排序</a>
	</td>
</tr>
<tr align="center" bgcolor="#FBFCE2" height="25">
	<td width="32">ID</td>
	<td width="20">选</td>
	<td width="80">栏目</td>
	<td>文章标题</td>
	<td width="64">操作</td>
	<td width="66">对外时间</td>
	<td width="32">点击</td>
	<td width="39">来路</td>
	<td width="24">HTML</td>
	<td width="30">权限</td>
	<td width="40">作者</td>
</tr>
<?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
if(is_array($blockValue)){
foreach( $blockValue as $key=>$fields )
{
?>
<tr align='center' bgcolor="#FFFFFF" height="26" align="center" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
	<td nowrap>
		<?php echo $fields['id']; ?>
	</td>
	<td>
		<input name="arcID" type="checkbox" id="arcID" value="<?php echo $fields['id']; ?>" class="np" />
	</td>
	<td><div class="cat">
		<a href='content_list.php?cid=<?php echo $fields['typeid']; ?>'><?php echo GetTypename($fields['typeid']); ?></a>
		</div></td>
	<td align='left'>
		<span id="arc<?php echo $fields['id']; ?>">
			<a href='archives_do.php?aid=<?php echo $fields['id']; ?>&dopost=editArchives'
				 oncontextmenu="ShowMenu(event,this,<?php echo $fields['id']; ?>,'<?php echo str_replace('\'', '', $fields['title']); ?>')">
				<u><?php echo $fields['title']; ?></u>
			</a>
		</span>
		<?php echo IsCommendArchives($fields['flag']); ?>
	</td>
	<td>
		<img src='images/trun.gif' title="编辑属性" alt="编辑属性" onClick="QuickEdit(<?php echo $fields['id']; ?>, event, this);" style='cursor:pointer' border='0' width='16' height='16' />
		<a href="article_weibo.php?aid=<?php echo $fields['id']; ?>"><img src='http://res.xschu.com/img/ico/weibo.com.png' title="发微博" alt="发微博" style='cursor:pointer' border='0' /></a>
		<img src='images/part-list.gif' title="预览" alt="预览" onClick="viewArc(<?php echo $fields['id']; ?>);" style='cursor:pointer' border='0' width='16' height='16' />
	</td>
	<td title="创建时间: <?php echo GetDateMk($fields['senddate']); ?>"><?php echo GetDateMk($fields['pubdate']); ?></td>
	<td><?php echo $fields['click']; ?></td>
	<td class="tack" id="tack<?php echo $fields['id']; ?>"><a href="javascript:showTack(<?php echo $fields['id']; ?>)"><?php echo $fields['tackid']; ?></a></td>
	<td><?php echo IsHtmlArchives($fields['ismake']); ?></td>
	<td><?php echo GetRankName($fields['arcrank']); ?></td>
	<td><script type="text/javascript">showWriter('<?php echo GetMemberName($fields['mid']); ?>','<?php echo $fields['writer']; ?>');</script></td>
</tr>
<tr class="tacklist none" id="tacklist<?php echo $fields['id']; ?>">
	<td colspan="11"></td>
</tr>
<?php
}
}?>
<tr bgcolor="#ffffff">
<td height="36" colspan="11">
	&nbsp;
	<a href="javascript:selAll()" class="coolbg">全选</a>
	<a href="javascript:noSelAll()" class="coolbg">取消</a>
	<a href="javascript:updateArc(0)" class="coolbg">&nbsp;更新&nbsp;</a>
	<a href="javascript:checkArc(0)" class="coolbg">&nbsp;审核&nbsp;</a>
	<a href="javascript:adArc(0)" class="coolbg">&nbsp;推荐&nbsp;</a>
	<a href="javascript:;" onClick="moveArc(event,this,<?php echo (empty($channelid) ? 0 : $channelid); ?>)" class="coolbg">&nbsp;移动&nbsp;</a>
	<a href="javascript:delArc(0)" class="coolbg">&nbsp;删除&nbsp;</a>
	<?php if($channelid==1) echo "	<a href=\"javascript:kwArc(0)\" class=\"coolbg\">&nbsp;自动关键字&nbsp;</a>\r\n"; ?>
	<a href="javascript:;" onClick="cAtts('attsAdd',event,this)" class="coolbg">&nbsp;增加属性&nbsp;</a>
	<a href="javascript:;" onClick="cAtts('attsDel',event,this)" class="coolbg">&nbsp;删除属性&nbsp;</a>
</td>
</tr>

<tr align="right" bgcolor="#F9FCEF">
	<td height="36" colspan="11" align="center">
		<?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '3';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>

	</td>
</tr>
</table>
</form>
<script type="text/javascript">
$('.tack').each(function(){
    if($(this).text()=='0'){$(this).html('--');}
});
</script>
<!--  搜索表单  -->
<form name='form3' action='content_list.php' method='get'>
<input type='hidden' name='dopost' value='listArchives' />
<table width='98%'  border='0' cellpadding='1' cellspacing='1' bgcolor='#cfcfcf' align="center" style="margin-top:8px">
  <tr bgcolor='#EEF4EA'>
    <td background='images/wbg.gif' align='center'>
      <table border='0' cellpadding='0' cellspacing='0' height="32">
        <tr>
          <td width='160'>
          <select name='cid' style='width:150px'>
          <option value='0'>选择栏目...</option>
          	<?php echo $GLOBALS['optionarr']; ?>
          </select>
        </td >
        <td nowrap>
          关键字：
        </td>
        <td width='130'>
          	<input type='text' name='keyword' value='<?php echo $GLOBALS['keyword']; ?>' style='width:120px' />
        </td>
        <td width='90'>
    		<select name='orderby' style='width:80px'>
            <option value='id'>排序...</option>
            <option value='pubdate'>更新时间</option>
            <option value='sortrank'>置顶权值</option>
            <option value='click'>点击量</option>
            <option value='scores'>评论积分</option>
            <option value='lastpost'>最后评论</option>
      	</select>
      </td>
      <td width='90'>
    		<select name='flag' style='width:80px'>
            <option value=''>属性...</option>
            <?php echo $GLOBALS['flagsArr']; ?>
      	</select>
      </td>
       <td>
          <input name="imageField" type="image" src="images/button_search.gif" width="60" height="22" border="0" class="np" />
       </td>
      </tr>
     </table>
   </td>
  </tr>
</table>
</form>

</body>
</html>
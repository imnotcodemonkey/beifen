<style type="text/css">
.phpinfo{ width:100%; border-collapse:collapse; font:12px/18px SimSun;}
.phpinfo td{ border:1px gray solid;padding:2px 4px}
.phpinfo thead{ font-weight:bold; text-align:center;}
td.w300{ table-layout:fixed; word-break:break-all; word-wrap:break-word; width:400px;}
</style>
<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/filter.inc.php");
require_once(DEDEINC."/customfields.func.php");

$msg="";

$d = empty($d)? 21 : intval(preg_replace("/[^-\d]+[^\d]/",'', $d));
$t=time()-86400*$d;

$query = "SELECT * FROM `#@__zixunhui` WHERE  dtime>{$t} ORDER BY id DESC Limit 0,50";
$dsql->Execute('fb', $query);

echo '<table class="phpinfo" style="font-size:12px"><thead><tr>
<td>日期</td>
<td>姓名</td>
<td>手机</td>
<td>专业方向</td>
<td>报名页面</td>
<td>数据来源</td></tr></thead>';
while($fields = $dsql->GetArray('fb')){
	$linkstr= "<td>".MyDate('Y-m-d H:i',$fields['dtime'])."</td>";
	$linkstr.= "<td>".$fields['uname']."</td>";
	$linkstr.= "<td>".$fields['mid']."</td>";
	$linkstr.= "<td>".$fields['title']."</td>";
	$linkstr.= "<td><a href=".$fields['currentPage']." target='_blank'>".$fields['currentPage']."</a></td>";
	$linkstr.= "<td class='w300'><a href=".$fields['fromUrl']." target='_blank'>".$fields['fromUrl']."</a></td>";
    $linkstr=mb_convert_encoding($linkstr, "UTF-8", "GBK");
    echo '<tr>'.$linkstr.'</tr>';
}
echo '</table>';
//echo $query;

?>
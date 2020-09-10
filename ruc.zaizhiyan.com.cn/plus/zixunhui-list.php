<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/filter.inc.php");
require_once(DEDEINC."/customfields.func.php");

$msg="";

$d = empty($d)? 7 : intval(preg_replace("/[^-\d]+[^\d]/",'', $d));
$t=time()-86400*$d;

$query = "SELECT * FROM `#@__zixunhui` WHERE  dtime>{$t} ORDER BY id DESC Limit 0,50";
$dsql->Execute('fb', $query);

echo '<table class="phpinfo" style="font-size:12px"><tr style="text-align:center !important">
<td style="width:36px !important">姓名</td>
<td style="width:148px !important">专业方向</td>
<td style="width:96px !important">日期</td>
<td style="width:66px !important">手机</td>
<td>留言</td></tr>';
while($fields = $dsql->GetArray('fb')){
	$linkstr = "<td>".$fields['uname']."</td>";
	$linkstr.= "<td>".$fields['title']."</td>";
	$linkstr.= "<td>".MyDate('Y-m-d H:i',$fields['dtime'])."</td>";
	$linkstr.= "<td>".$fields['mid']."</td>";
	$linkstr.= "<td>".$fields['msg']."</td>";
    $linkstr=mb_convert_encoding($linkstr, "UTF-8", "GBK");
    echo '<tr>'.$linkstr.'</tr>';
}
echo '</table>';
//echo $query;

?>
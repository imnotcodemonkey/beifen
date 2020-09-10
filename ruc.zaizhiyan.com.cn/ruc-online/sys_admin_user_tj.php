<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_User');

//获取用户的统计信息
if(isset($dopost) && $dopost=='getone')
{
		$row = $dsql->GetOne("Select userid,uname,tname From `#@__admin` where id='$uid'; ");
		$userid = $row['userid'];
		$y = intval(MyDate('Y', time()));
		$m = intval(MyDate('m', time()));
		$d = intval(MyDate('d', time()));
		$name=$row['tname'];
		if($row['tname']=='')$name=$row['uname'];
		//全部
		$sql="SELECT addtable FROM `#@__channeltype` WHERE issystem='-1'";
		$dsql->Execute('me', $sql);
		while($frow = $dsql->GetArray('me'))
		{
			$dd=empty($dd)? "0" : $dd;
			$cc=empty($cc)? "0" : $cc;
			$row = $dsql->GetOne("Select count(aid) as dd,sum(click) as cc From `{$frow['addtable']}` where mid='$uid'; ");
			$dd += $row['dd'];
			$cc += $row['cc'];
		}
		$row = $dsql->GetOne("Select count(id) as dd,sum(click) as cc From `#@__archives` where mid='$uid'; ");
		$dd = $row['dd'] + $dd;
		$cc = $row['cc'] + $cc;
		$pp = $dd==0||$cc==0?0:intval($cc/$dd);
		
		//本年
		$starttime = 0;
		$starttime = $y."-01-01 00:00:00";
		$istarttime = GetMkTime($starttime);
		$sql="SELECT addtable FROM `#@__channeltype` WHERE issystem='-1'";
		$dsql->Execute('me', $sql);
		while($frow = $dsql->GetArray('me'))
		{
			$dds=empty($dds)? "0" : $dds;
			$ccs=empty($ccs)? "0" : $ccs;
			$row = $dsql->GetOne("Select count(aid) as dd,sum(click) as cc From `{$frow['addtable']}` where senddate>$istarttime And mid='$uid'; ");
			$ddY += $row['dd'];
			$ccY += $row['cc'];
		}
		$row = $dsql->GetOne("Select count(id) as dd,sum(click) as cc From `#@__archives` where senddate>$istarttime And mid='$uid'; ");
		$ddY = $row['dd'] + $ddY;
		$ccY = $row['cc'] + $ccY;
		$ppY = $ddY==0||$ccY==0?0:intval($ccY/$ddY);

		//季度
		$starttime = 0;
		if( ereg("[123]", $m) && $m < 10) $starttime = $y."-01-01 00:00:00";
		else if( ereg("[456]", $m) ) $starttime = $y."-04-01 00:00:00";
		else if( ereg("[789]", $m) ) $starttime = $y."-07-01 00:00:00";
		else  $starttime = $y."-10-01 00:00:00";
		$istarttime = GetMkTime($starttime);
		$sql="SELECT addtable FROM `#@__channeltype` WHERE issystem='-1'";
		$dsql->Execute('me', $sql);
		while($frow = $dsql->GetArray('me'))
		{
			$dds=empty($dds)? "0" : $dds;
			$ccs=empty($ccs)? "0" : $ccs;
			$row = $dsql->GetOne("Select count(aid) as dd,sum(click) as cc From `{$frow['addtable']}` where senddate>$istarttime And mid='$uid'; ");
			$dds += $row['dd'];
			$ccs += $row['cc'];
		}
		$row = $dsql->GetOne("Select count(id) as dd,sum(click) as cc From `#@__archives` where senddate>$istarttime And mid='$uid'; ");
		$dds = $row['dd'] + $dds;
		$ccs = $row['cc'] + $ccs;
		$pps = $dds==0||$ccs==0?0:intval($ccs/$dds);

		//上月
		$starttime = ($m==1?$y-1:$y)."-".($m==1?12:$m-1)."-01 00:00:00";
		$istarttime = GetMkTime($starttime);
		$sy=($m==1?$y-1:$y)."-".($m==1?12:$m-1);
		$endtime = $y."-{$m}-01 00:00:00";
		$isendtime = GetMkTime($endtime );
		$sql="SELECT addtable FROM `#@__channeltype` WHERE issystem='-1'";
		$dsql->Execute('me', $sql);
		while($frow = $dsql->GetArray('me'))
		{
			$dlm=empty($ddm)? "0" : $dlm;
			$clm=empty($ccm)? "0" : $clm;
			$row = $dsql->GetOne("Select count(aid) as dd,sum(click) as cc From `{$frow['addtable']}` where (senddate>$istarttime and senddate<$isendtime) And mid='$uid'; ");
			$dlm += $row['dd'];
			$clm += $row['cc'];
		}
		$row = $dsql->GetOne("Select count(id) as dd,sum(click) as cc From `#@__archives` where (senddate>$istarttime and senddate<$isendtime) And mid='$uid'; ");
		$dlm = $row['dd'] + $dlm;
		$clm = $row['cc'] + $clm;
		$plm = $dlm==0||$clm==0?0:intval($clm/$dlm);
		
		//当月
		$starttime = $y."-{$m}-01 00:00:00";
		$istarttime = GetMkTime($starttime);
		$sql="SELECT addtable FROM `#@__channeltype` WHERE issystem='-1'";
		$dsql->Execute('me', $sql);
		while($frow = $dsql->GetArray('me'))
		{
			$ddm=empty($ddm)? "0" : $ddm;
			$ccm=empty($ccm)? "0" : $ccm;
			$row = $dsql->GetOne("Select count(aid) as dd,sum(click) as cc From `{$frow['addtable']}` where senddate>$istarttime And mid='$uid'; ");
			$ddm += $row['dd'];
			$ccm += $row['cc'];
		}
		$row = $dsql->GetOne("Select count(id) as dd,sum(click) as cc From `#@__archives` where senddate>$istarttime And mid='$uid'; ");
		$ddm = $row['dd'] + $ddm;
		$ccm = $row['cc'] + $ccm;
		$ppm = $ddm==0||$ccm==0?0:intval($ccm/$ddm);
		
		//上周
		$today = time();
		$w = date('w',$today);
		$szy=MyDate('Y-m-d',$today-86400*($w+6));
		$szr=MyDate('Y-m-d',$today-86400*($w));
        $starttime = $szy." 00:00:00";
		$istarttime = GetMkTime($starttime);
        $endtime = $szr." 23:59:59";
		$isendtime = GetMkTime($endtime );

		$sql="SELECT addtable FROM `#@__channeltype` WHERE issystem='-1'";
		$dsql->Execute('me', $sql);
		while($frow = $dsql->GetArray('me'))
		{
			$dlw=empty($dlw)? "0" : $dlw;
			$clw=empty($clw)? "0" : $clw;
			$row = $dsql->GetOne("Select count(aid) as dd,sum(click) as cc From `{$frow['addtable']}` where (senddate>$istarttime and senddate<$isendtime) And mid='$uid'; ");
			$dlw+= $row['dd'];
			$clw+= $row['cc'];
		}
		$row = $dsql->GetOne("Select count(id) as dd,sum(click) as cc From `#@__archives` where (senddate>$istarttime and senddate<$isendtime) And mid='$uid'; ");
		$dlw= $row['dd'] + $dlw;
		$clw= $row['cc'] + $clw;
		$plw = $dlw==0||$clw==0?0:intval($clw/$dlw);
		
		
		//最近七天
		$starttime = $y."-{$m}-{$d} 00:00:00";
		$istarttime = GetMkTime($starttime) - (7*24*3600);

		//本周
		$bz=MyDate('Y-m-d',$today-86400*($w));
        $starttime = $bz." 00:00:00";
		$istarttime = GetMkTime($starttime);

		$sql="SELECT addtable FROM `#@__channeltype` WHERE issystem='-1'";
		$dsql->Execute('me', $sql);
		while($frow = $dsql->GetArray('me'))
		{
			$ddw=empty($ddw)? "0" : $ddw;
			$ccw=empty($ccw)? "0" : $ccw;
			$row = $dsql->GetOne("Select count(aid) as dd,sum(click) as cc From `{$frow['addtable']}` where senddate>$istarttime And mid='$uid'; ");
			$ddw += $row['dd'];
			$ccw += $row['cc'];
		}
		$row = $dsql->GetOne("Select count(id) as dd,sum(click) as cc From `#@__archives` where senddate>$istarttime And mid='$uid'; ");
		$ddw = $row['dd'] + $ddw;
		$ccw = $row['cc'] + $ccw;
		$ppw = $ddw==0||$ccw==0?0:intval($ccw/$ddw);

		//昨天
        $starttime = MyDate('Y-m-d',$today-86400)." 00:00:00";
		$istarttime = GetMkTime($starttime);
        $endtime = $y."-{$m}-{$d} 00:00:00";
		$isendtime = GetMkTime($endtime );

		$sql="SELECT addtable FROM `#@__channeltype` WHERE issystem='-1'";
		$dsql->Execute('me', $sql);
		while($frow = $dsql->GetArray('me'))
		{
			$ddy=empty($ddy)? "0" : $ddy;
			$ccy=empty($ccy)? "0" : $ccy;
			$row = $dsql->GetOne("Select count(aid) as dd,sum(click) as cc From `{$frow['addtable']}` where (senddate>$istarttime and senddate<$isendtime) And mid='$uid'; ");
			$ddy+= $row['dd'];
			$ccy+= $row['cc'];
		}
		$row = $dsql->GetOne("Select count(id) as dd,sum(click) as cc From `#@__archives` where (senddate>$istarttime and senddate<$isendtime) And mid='$uid'; ");
		$ddy= $row['dd'] + $ddy;
		$ccy= $row['cc'] + $ccy;
		$ppy = $ddy==0||$ccy==0?0:intval($ccy/$ddy);


		//当天
		$starttime = $y."-{$m}-{$d} 00:00:00";
		$istarttime = GetMkTime($starttime);
		$sql="SELECT addtable FROM `#@__channeltype` WHERE issystem='-1'";
		$dsql->Execute('me', $sql);
		while($frow = $dsql->GetArray('me'))
		{
			$ddd=empty($ddd)? "0" : $ddd;
			$ccd=empty($ccd)? "0" : $ccd;
			$row = $dsql->GetOne("Select count(aid) as dd,sum(click) as cc From `{$frow['addtable']}` where senddate>$istarttime And mid='$uid'; ");
			$ddd += $row['dd'];
			$ccd += $row['cc'];
		}
		$row = $dsql->GetOne("Select count(id) as dd,sum(click) as cc From `#@__archives` where senddate>$istarttime And mid='$uid'; ");
		$ddd = $row['dd'] + $ddd;
		$ccd = $row['cc'] + $ccd;
		$ppd = $ddd==0||$ccd==0?0:intval($ccd/$ddd);
		
		$msg = "<table border='0' class='db' cellpadding='0' cellspacing='0'>
        <tr align='center'>
          <td class=uname><strong title='{$userid}'>{$name}</strong></td>
          <td class='num'><span>{$cc}</span>  <tt>{$dd}</tt>  <em>{$pp}</em></td>
          <td class='num'><span>{$ccY}</span> <tt>{$ddY}</tt> <em>{$ppY}</em></td>
          <td class='num'><span>{$ccs}</span> <tt>{$dds}</tt> <em>{$pps}</em></td>
          <td class='num' title='{$sy}'><span>{$clm}</span> <tt>{$dlm}</tt> <em>{$plm}</em></td>
          <td class='num'><span>{$ccm}</span> <tt>{$ddm}</tt> <em>{$ppm}</em></td>
          <td class='num' title='{$szy}~{$szr}'><span>{$clw}</span> <tt>{$dlw}</tt> <em>{$plw}</em></td>
          <td class='num' title='{$bz}~now'><span>{$ccw}</span> <tt>{$ddw}</tt> <em>{$ppw}</em></td>
          <td class='num'><span>{$ccy}</span> <tt>{$ddy}</tt> <em>{$ppy}</em></td>
         <td class='num'><span>{$ccd}</span> <tt>{$ddd}</tt> <em>{$ppd}</em></td>

        </tr>
    </table>\r\n";
    AjaxHead();
    echo $msg;
    exit();
}


include DedeInclude('templets/sys_admin_user_tj.htm');

?>
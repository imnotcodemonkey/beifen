<?php
require_once(dirname(__FILE__).'/config.php');
CheckPurview('admin_AllowAll');
require_once(DEDEINC.'/datalistcp.class.php');
if(empty($cid))
{
	$cid = '0';
	$whereSql = '';
}

if($dopost =='submit'){

function save_txt($filename,$dumpFile){
    header('Content-type: text/plain; charset=gb2312');
    header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
    echo($dumpFile);
    exit();
} 

// current datetime

$y = date('Y',time());
$m = date('m',time());
$d = date('d',time());
$currentdate1 = mktime (0,0,0,$m,$d,$y);
$currentdate2 = mktime (0,0,0,$m,$d+1,$y);

// �ж� radio ֵ �Զ���ʱ��
if($radiobutton=='radio2'){

    if(!empty($selectdate1)){
        $arrtime = split('-',$selectdate1);
        $currentdate1 = mktime(0,0,0,$arrtime[1],$arrtime[2],$arrtime[0]);
        unset($arrtime);
    }
    
    if(!empty($selectdate2)){
        $arrtime = split('-',$selectdate2);
        $currentdate2 = mktime(0,0,0,$arrtime[1],$arrtime[2]+1,$arrtime[0]);  
        unset($arrtime);
    }
    // if
}

$sql = "select id,FROM_UNIXTIME(dtime),select_specialty ,uname ,sex ,mobile ,email 
,address ,education ,diploma ,graduate_school ,learning_specialty ,language ,questioninfo 
  FROM `#@__guestbook_italy`
  WHERE dtime>=$currentdate1  AND dtime< $currentdate2
  ORDER BY dtime ASC";

$dsql = new DedeSql(false);
$dsql->Query('select',$sql);
$txtstr = " , , , , , , , , , , , , , "."\r\n";
$txtstr = $txtstr."ID,¼��ʱ��,������רҵ,����,�Ա�,�ֻ�����,Email,��ַ,���ѧ��,ѧʿѧλ,��ҵԺУ,��ѧרҵ,��������,��ѯ����"."\r\n";

while($rowArr = $dsql->GetArray('select')){
    $rowArr['questioninfo'] = str_replace("\r","<br/>",$rowArr['questioninfo']);
    $rowArr['questioninfo'] = str_replace("\n","",$rowArr['questioninfo']);
    $txtstr = $txtstr.'"'.join('","',$rowArr).'"'."\r\n";
}
$dsql->FreeResult('select');
$dsql->Close();

$filename = date('Y-m-d_H_i_s',time()).'_Export_'.date('Y-m-d_H:i:s',$currentdate1).'_to_'.date('Y-m-d_H:i:s',$currentdate2);
save_txt($filename,$txtstr);

} // if


include(DEDEADMIN."/templets/report_italy.htm");
exit();
?>
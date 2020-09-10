<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 
 * @version        2011/2/11  ɳ�� $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2011, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 *
 **/
 
class browser extends Control
{	
	function browser()
	{
		parent::__construct();
		//�������
		$this->helper('question',DEDEASK);
	}
	
	function ac_index()
	{
		$tid = request('tid', '');
		$tid  = is_numeric($tid)? $tid : 0;
		$tid2 = request('tid2', '');
		$tid2  = is_numeric($tid2)? $tid2 : 0;
		$lm = request('lm', '');
		$lm  = is_numeric($lm)? $lm : 0;
		$appname = request('appname', '');
		$page = request('page', '');
		$page  = is_numeric($page)? $page : 0;
		$subtypeinfos = array();
		$tidstr = $nav = $navtitle = $multistr = $wheresql = '';
		
		if($tid == 0 && $tid2 == 0 && $lm == 0 && $appname == 0)
		{
			ShowMsg("�ύ����������!","index.php");
            exit();
		}
		if($tid)
		{
			$this->dsql->Execute('me',"SELECT * FROM `#@__asktype` WHERE id='{$tid}'");
			if(!$typeinfo = $this->dsql->getarray())
			{
				ShowMsg('ָ����Ŀ�����ڣ��뷵��','index.php');
				exit;
			}
			$wheresql .= "tid='{$tid}' ";
			$multistr .="tid={$tid}";
			$tidstr = "tid={$tid}";
			$navtitle = $typeinfo['name'];
			$nav = " <a href=\"?ct=browser&tid={$tid}\">".$typeinfo['name'].'</a>'.$GLOBALS['cfg_ask_symbols'];
			$toptypeinfo = $typeinfo;
		
		}elseif($tid2){
			$this->dsql->Execute('me',"SELECT * FROM `#@__asktype` WHERE id='{$tid2}' ");
			if(!$typeinfo = $this->dsql->getarray())
			{
				ShowMsg('ָ����Ŀ�����ڣ��뷵��','index.php');
				exit;
			}
			$wheresql .= "tid2='{$tid2}'";
			$multistr .="tid2={$tid2}";
			$tidstr = "tid2={$tid2}";
			$toptypeinfo = $this->dsql->getone("SELECT id, name, asknum FROM `#@__asktype` WHERE id='".$typeinfo['reid']."' LIMIT 1");
			$navtitle = $typeinfo['name'].' '.$toptypeinfo['name'];
			$nav .= " <a href=\"?ct=browser&tid=".$toptypeinfo['id']."\">".$toptypeinfo['name']."</a> {$GLOBALS['cfg_ask_symbols']} <a href=\"?ct=browser&tid2=".$tid2."\">".$typeinfo['name']."</a>".$GLOBALS['cfg_ask_symbols'];
		}
		
		if($tid || $tid2)
		{
			$query = "SELECT id, name, asknum FROM #@__asktype WHERE reid='".$toptypeinfo['id']."' ORDER BY disorder asc, id asc";
			$this->dsql->Execute('me',$query);
			while($row = $this->dsql->getarray())
			{
				$subtypeinfos[] = $row;
			}
		}
		
		if(!empty($appname))
		{
		    $wheresql .= " and appname = '{$appname}'";
		    $apname = '';
		    if($appname == 1) $apname = 'DedeCMS';
		    else if($appname == 2) $apname = 'DedeEIMS';
		    else if($appname == 3) $apname = '֯���Ա���';
		    $nav .= ' '.$GLOBALS['cfg_ask_symbols'].' '.$apname;
		    if(!$tid && !$tid2)
		    {
		        $toptypeinfo['name'] = $apname;
		        $multistr .= $mulappname ="appname={$appname}";
		    }else{
		        $multistr .= $mulappname ="&appname={$appname}";
		    }
		}else{
		    $mulappname = "";
		}
				
		$orderby = 'ORDER BY';
		$all = array( 0 => "",1 => "",2 => "",3 => "",4 => "",5 => "",6 => "");
		if(empty($lm))
		{
			$wheresql .= ' and status>=0';
			$orderby .= ' disorder DESC, dateline DESC';
			$all[0] = ' class="select"';
		}elseif($lm == 1){
			//��������
			$wheresql .= ' and digest=1';
			$orderby .= ' replies DESC, dateline DESC';
			$nav .= ' �����Ƽ�';
			$all[1] = ' class="thisclass"';
			if(!$tid && !$tid2) $toptypeinfo['name'] = '�����Ƽ�';
		}elseif($lm == 2){
			//�����
			$wheresql .= ' and status=0';
			$orderby .= ' disorder DESC, dateline DESC';
			$nav .= ' ���������';
			$all[2] = ' class="select"';
			if(!$tid && !$tid2) $toptypeinfo['name'] = '���������';
		}elseif($lm == 3){
			//�ѽ��
			$wheresql .= ' and status=1';
			$orderby .= ' solvetime DESC';
			$nav .= ' ���������';
			$all[3] = ' class="select"';
			if(!$tid && !$tid2) $toptypeinfo['name'] = '���������';
		}elseif($lm == 4){
			//�߷�
			$wheresql .= ' and status=0';
			$orderby .= ' reward DESC';
			$nav .= ' �߷�����';
			$all[4] = ' class="select"';
			if(!$tid && !$tid2) $toptypeinfo['name'] = '�߷�����';
		}elseif($lm == 5){
			//��ش�
			$wheresql .= ' and replies=0 and status=0';
			$orderby .= ' disorder DESC, dateline DESC';
			$nav .= ' ��ش�����';
			$all[5] = ' class="select"';
			if(!$tid && !$tid2) $toptypeinfo['name'] = '��ش�����';
		}elseif($lm == 6){
			//�쵽��
			$wheresql .= ' and status=0';
			$orderby .= ' expiredtime asc, dateline DESC';
			$nav .= ' �쵽������';
			$all[6] = ' class="select"';
			if(!$tid && !$tid2) $toptypeinfo['name'] = '�쵽������';
		}else{
			ShowMsg('ָ����Ŀ�����ڣ��뷵��','index.php');
			exit;
		}
		
		if(!empty($lm) && ($tid || $tid2 || $appname)) $multistr .="&lm={$lm}";
		else if(!empty($lm)) $multistr .="lm={$lm}";
		$navtitle = $navtitle == '' ? $GLOBALS['cfg_ask_sitename'] : $navtitle.' '.$GLOBALS['cfg_ask_sitename'];
		$nav = $GLOBALS['cfg_ask_position'].$nav;
		
		$wheresql = trim($wheresql);
		if(preg_match("#^and#",$wheresql)) $wheresql = substr($wheresql,3);
		$wheresql = 'WHERE '.trim($wheresql);

		$row = $this->dsql->GetOne("SELECT count(*) as dd FROM `#@__ask` $wheresql");
		$askcount = $row['dd'];
		$realpages = @ceil($askcount/$GLOBALS['cfg_ask_tpp']);
		if($page > $realpages) $page = $realpages;
		$page = isset($page) ? max(1, intval($page)) : 1;
		$start_limit = ($page - 1) * $GLOBALS['cfg_ask_tpp'];
		$multipage = multi($askcount, $GLOBALS['cfg_ask_tpp'], $page, "?ct=browser&$multistr");
		
		$query = "SELECT id, tid, tidname, tid2, tid2name, title, reward, dateline, status, expiredtime solvetime, replies
		FROM `#@__ask` $wheresql $orderby LIMIT $start_limit, {$GLOBALS['cfg_ask_tpp']}";
		
		$this->dsql->Execute('me',$query);
		$asks = array();
		while($row = $this->dsql->getarray())
		{
			if($row['status'] == 1) $row['status'] = 'qa_ico_2.jpg'; //�ѽ��
			else if($row['status'] == 2) $row['status'] = 'qa_ico_2.jpg'; //�ر�
			else if($row['status'] == 3) $row['status'] = 'qa_ico_2.jpg'; //����
            else $row['status'] = 'qa_ico_1.gif'; //����
            if($GLOBALS['cfg_ask_rewrite'] == 'Y') $row['qurl'] = $row['id'].'.html';
            else $row['qurl'] = '?ct=question&askaid='.$row['id'];
			$asks[] = $row;
		}
		
		//�趨����ֵ
        $arrs = array('asks','toptypeinfo','subtypeinfos','nav','tid','tid2','all','mulappname','tidstr',
		              'multipage','appname');
		foreach ($arrs as $val) {
            $GLOBALS[$val] = $$val;  
		}
		//����ģ��
		$this->SetTemplate('browser.htm');
        $this->Display();
	}
		
}

?>
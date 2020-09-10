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
 
class search extends Control
{
    function search()
	{
	    parent::__construct();
	    global $cfg_ml;
		if(empty($cfg_ml->M_ID))
		{
			ShowMsg('����δ��¼�����ȵ�¼',$GLOBALS['cfg_ask_member']);
			exit;
		}
		if($cfg_ml->M_Spacesta < 0)
		{
			ShowMsg('����û��ͨ�����,��ʱ��������,�����ĵ�....','-1');
			exit;
		}
		//�������
		$this->helper('question',DEDEASK);
	}
	
	//����
    function ac_index()
    {
		$q = request('q', '');
		$tid = request('tid', '');
		$page = request('page', '');
		$q = strip_tags($q);
		$page  = is_numeric($page)? $page : 0;
		//��ȥ�ո��жϳ���
		$key = str_replace(' ','',$q); 
		if($key =='' || strlen($key) < 4 || strlen($key) > 80)
		{
			ShowMsg("�ؼ��ֳ��ȱ���Ҫ4-80�ֽ�֮�䣡","index.php");
			exit();
		}
		$ks = explode(' ',$q);
		$kwsql = '';
        $kwsqls = array();
		foreach($ks as $k)
        {
            $k = trim($k);
            $k = addslashes($k);
            $kwsqls[] = " title LIKE '%$k%' ";
        }
        if(isset($kwsqls[0])) $kwsql = join(' OR ',$kwsqls);
		$row = $this->dsql->GetOne("SELECT count(*) as dd FROM `#@__ask` WHERE {$kwsql}");
		$askcount = $row['dd'];
		if($askcount <= 0)
		{
		    ShowMsg("û�в���ƥ������ݣ�","-1");
			exit();    
		}
		//���ɷ�ҳ�б�
    	$realpages = @ceil($askcount/$GLOBALS['cfg_ask_tpp']);
		if($page > $realpages) $page = $realpages;
		$page = isset($page) ? max(1, intval($page)) : 1;
		$start_limit = ($page - 1) * $GLOBALS['cfg_ask_tpp'];
		$multipage = multi($askcount, $GLOBALS['cfg_ask_tpp'], $page, "?ct=search&q=$q");
		$query = "SELECT id, tid, tidname, tid2, tid2name, uid, title, reward, dateline, status, replies FROM #@__ask WHERE {$kwsql} LIMIT $start_limit, {$GLOBALS['cfg_ask_tpp']}";
		$this->dsql->setquery($query);
		$this->dsql->execute();
		$searchs = array();
		while($row = $this->dsql->getarray())
		{
		    $row["title"] = $this->GetRedKeyWord($q,$row["title"]);
		    if($GLOBALS['cfg_ask_rewrite'] == 'Y') $row['qurl'] = $row['id'].'.html';
            else $row['qurl'] = '?ct=question&askaid='.$row['id'];
			$searchs[] = $row;
		}
		//��ǰλ��
		$nav = $GLOBALS['cfg_ask_position']." ����";
		$toptypeinfo = "���� <font color='red'>".$q."</font> ���";
		
		//�趨����ֵ
        //�趨����ֵ
        $arrs = array('nav','searchs','toptypeinfo','multipage');
		foreach ($arrs as $val) {
            $GLOBALS[$val] = $$val;  
		}
		//����ģ��
		$this->SetTemplate('search.htm');
        $this->Display();
    }
    
    //��������
	function ac_ask_search()
	{
        $title = request('title', '');
        $title = addslashes(preg_replace("[\"\r\n\t\*\?\(\)\$%'><]"," ",stripslashes(trim($title))));
        if(!empty($title) && strlen($title) >= 8)
        {   
            $query = "SELECT id,title,content,bestanswer FROM `#@__ask`
                      WHERE title like '%$title%' LIMIT 5";
    		$this->dsql->setquery($query);
    		$this->dsql->execute();
    		$searchs = array();
    		while($row = $this->dsql->getarray())
    		{
    		    $row["title"] = $this->GetRedKeyWord($title,$row["title"]);
    		    $row['content'] = cn_substr(strip_tags($row['content']),300);
    			$searchs[] = $row;
    		}
    		if(count($searchs) > 0)
    		{
    		    //�趨����ֵ
                $GLOBALS['searchs'] = $searchs;
    		    //����ģ��
        		$this->SetTemplate('ask2.htm');
                $this->Display();
    		}else{
                Header("Location: ?ct=question&ac=ask_complete&title=$title");
                exit;
            }
        }else{
            ShowMsg('�������Ʋ���Ϊ�ջ��߲�����!',"-1");
            exit; 
        }
	}
    
    function GetRedKeyWord($q,$fstr)
    {
        $ks = explode(' ',$q);
        foreach($ks as $k)
        {
            $k = trim($k);
            if($k=='') continue;
            if(ord($k[0]) > 0x80 && strlen($k) < 4) continue;
            $fstr = str_replace($k, "<font color='red'>$k</font>", $fstr);
        }
        return $fstr;
    }
}
?>
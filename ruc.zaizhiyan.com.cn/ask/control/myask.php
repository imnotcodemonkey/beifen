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
 
class myask extends Control
{
    function myask()
	{
	    parent::__construct();
	    
		global $cfg_ml,$menutype,$cfg_mb_other_open,$myurl,$menutype_son;
		$this->cfg_ml = $cfg_ml;
		if(empty($this->cfg_ml->M_ID))
		{
			ShowMsg('����δ��¼�����ȵ�¼',$GLOBALS['cfg_ask_member']);
			exit;
		}
		$menutype = 'mydede';
        $menutype_son = '';
        $cfg_mb_other_open = "N";
        $myurl = "/member/index.php?uid=".$this->cfg_ml->M_LoginID;
	}
	
	//�ҵ�����
    function ac_index()
    {
        $query = "SELECT id, tid, tidname, tid2, tid2name, uid, title, digest, reward, dateline, expiredtime,
                  solvetime, status, replies, lastanswer FROM `#@__ask`
                  WHERE uid='{$this->cfg_ml->M_ID}' ORDER BY lastanswer DESC";
        $dlist = new datalistcp();
        $dlist->pageSize = 20;
        $dlist->SetParameter('ct',"myask");
        $dlist->SetTemplate(DEDEAPPTPL.'/'.$this->style.'/'.'member_myask.htm');
        $dlist->SetSource($query);
        $dlist->Display();
    }
    
    //�ҵĻش�
    function ac_answer()
    {
        $query = "SELECT s.dateline as aswtime,a.id,a.title,a.tid,a.tidname,a.tid2,a.tid2name,a.uid,m.userid
                 FROM `#@__askanswer` AS s 
                 LEFT JOIN `#@__ask` AS a ON s.askid = a.id 
                 LEFT JOIN`#@__member` AS m ON m.mid = a.uid 
                 WHERE s.uid = '{$this->cfg_ml->M_ID}' ORDER BY aswtime DESC";
        $dlist = new datalistcp();
        $dlist->pageSize = 20;
        $dlist->SetParameter('ct',"myask");
        $dlist->SetParameter('ac',"answer");
        $dlist->SetTemplate(DEDEAPPTPL.'/'.$this->style.'/'.'member_myanswer.htm');
        $dlist->SetSource($query);
        $dlist->Display();
    }
    
    //������Ϣ
    function ac_view()
    {
        $mid = request('mid', '');
        $mid  = is_numeric($mid)? $mid : 0;
        $row = $this->dsql->GetOne("SELECT  * FROM `#@__member` WHERE mid='{$mid}' ");
        if(is_array($row)){
            /** ������ **/
            $asknum =  $this->dsql->GetOne("SELECT COUNT(id) as dd FROM `#@__ask` WHERE uid='{$mid}'");
            $asknum = empty($asknum['dd'])? 0 : $asknum['dd'];
            
            /** �ش��� **/
            $answernum =  $this->dsql->GetOne("SELECT COUNT(id) as dd FROM `#@__askanswer` WHERE uid='{$mid}'");
            $answernum = empty($answernum['dd'])? 0 : $answernum['dd'];
            
            /** ������ **/
            $adoptnum = $this->dsql->GetOne("SELECT COUNT(id) as dd FROM `#@__askanswer` WHERE uid='{$mid}' AND ifanswer = 1");
            if(empty($adoptnum['dd'])) $adoptrate  = 0; 
            else $adoptrate = $adoptnum['dd'] / $answernum * 100;
            
             /** ��ѯ��Աǩ�� **/
            $moodmsg = $this->dsql->GetOne("SELECT * FROM #@__member_msg WHERE mid='{$mid}' ORDER BY dtime desc");
            
            //ͷ��
            if(empty($row['face'])){
               $row['face'] = ($row['sex'] == 'Ů')? "static/images/dfgirl.png" : "static/images/dfboy.png";
            }
            
        }else{
            ShowMsg('�����ڸ��û���','-1');
    		exit;
        }
        $GLOBALS['row'] = $row;
        $GLOBALS['moodmsg'] = $moodmsg;
        $this->SetVar('asknum',$asknum);
        $this->SetVar('answernum',$answernum);
        $this->SetVar('adoptrate',$adoptrate);
        $this->SetTemplet('member_person.htm');
        $this->Display();
    }
    
    //���˻ش�
    function ac_reply()
    {
        $mid = request('mid', '');
        $mid  = is_numeric($mid)? $mid : 0;
        $sql = "SELECT a.id,a.title,a.tidname,a.tid2name,a.dateline
                 FROM `#@__askanswer` AS s 
                 LEFT JOIN `#@__ask` AS a ON s.askid = a.id 
                 WHERE s.uid = '{$mid}' AND ifcheck = 1 LIMIT 10";
        $this->dsql->SetQuery($sql);
        $this->dsql->Execute();
        $feeds = array();
        while ($row = $this->dsql->GetArray()) {
            $row['htmlurl'] = "?ct=question&askaid=".$row['id'];
            $row['senddate'] = MyDate('Y-m-d H:i',$row['dateline']);
            $row['title'] = gb2utf8($row['title']);
            $feeds[] = $row;
        }    
        $output = json_encode($feeds);
        print($output);  
    }  
    
    //��������
    function ac_ask()
    {
        $mid = request('mid', '');
        $mid  = is_numeric($mid)? $mid : 0;
        $sql = "SELECT * FROM `#@__ask` WHERE uid = '{$mid}' AND status >= 0 LIMIT 10";
        $this->dsql->SetQuery($sql);
        $this->dsql->Execute();
        $feeds = array();
        while ($row = $this->dsql->GetArray()) {
            $row['htmlurl'] = "?ct=question&askaid=".$row['id'];
            $row['senddate'] = MyDate('Y-m-d H:i',$row['dateline']);
            $row['title'] = gb2utf8($row['title']);
            $feeds[] = $row;
        } 
        $output = json_encode($feeds);
        print($output);  
    }    
    
    
    
}

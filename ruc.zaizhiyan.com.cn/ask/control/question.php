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
 
class question extends Control
{
    function question()
	{
	    parent::__construct();
		global $cfg_ml,$cfg_ask_guestview,$cfg_ask_guestask;
		$this->cfg_ml = $cfg_ml;
		if($cfg_ask_guestview == 'N' && empty($this->cfg_ml->M_ID))
		{
			ShowMsg('����δ��¼�����ȵ�¼',$GLOBALS['cfg_ask_member']);
			exit;
		}

		//�������
		$this->helper('question',DEDEASK);
		//����ģ��
        $this->question = $this->Model('mquestion');
        $this->type = $this->Model('mtype');
        $this->scores = $this->Model('mscores');
        $this->answer = $this->Model('askanswer');
        $this->comment = $this->Model('askcomment');
        
	}
	
	//�������
    function ac_index()
    {
        $askaid = request('askaid', '');
        $askaid = is_numeric($askaid)? $askaid : 0;
        if(!is_numeric($askaid))
        {
            ShowMsg("���ύ�Ĳ���������",'-1');
            exit;  
        }
		$question = $this->question->get_info($askaid);
		if(!is_array($question))
		{
            ShowMsg("����������ⲻ��,�����²���!",'-1');
            exit;  
		}
		if($question)
		{
			if($question['status'] == 1)
			{
				$question['dbstatus'] = 1;
				$question['status'] = 'solved';
			}else if($question['expiredtime'] < $GLOBALS['cfg_ask_timestamp']){
				$question['dbstatus'] = 2;
				$question['status'] = 'epired';
				//����һ�������ѹ���
				$set = "solvetime=expiredtime, status = '2'";
				$wheresql = "id='{$askaid}'";
				$this->question->update_ask($set,$wheresql);
			}else if($question['status'] == -1 && $question['uid'] != $this->cfg_ml->M_ID){
				ShowMsg('�����⻹δͨ�����,�����ĵȴ�...', '-1');
			    exit;
			}else{
				$question['dbstatus'] = 0;
				$question['status'] = 'no_solve';
			}
			$question['toendsec'] = $question['expiredtime'] - $GLOBALS['cfg_ask_timestamp'];
			$question['toendday'] = floor($question['toendsec']/86400);
			$question['toendhour'] = floor(($question['toendsec']%86400)/3600);
			//ͷ��
			$question['face'] = empty($question['face'])? 'static/images/user.gif' : $question['face'];
			//�ж������Ƿ����ڵ�ǰ��½��
			$publisher = 0;
			if($question['uid'] == $this->cfg_ml->M_ID) $publisher = 1;
		}else{
			ShowMsg('�ش�����ⲻ����', '-1');
			exit;
		}
	
		//��ȡ����ͷ��
		$question['honor'] = gethonor($question['scores']);
		//��վtitle
		$navtitle = $question['title'].'-'.$GLOBALS['cfg_ask_sitename'];
		//��ǰλ��
		$nav = $GLOBALS['cfg_ask_position'].' <a href="?ct=browser&tid='.$question['tid'].'">'.$question['tidname'].'</a>';
		if($question['tid2'])
		{
			$nav .= ' '.$GLOBALS['cfg_ask_symbols'].' <a href="?ct=browser&tid2='.$question['tid2'].'">'.$question['tid2name'].'</a>';
			$navtitle .= ' '.$question['tid2name'];
		}
		$nav .=' '.$GLOBALS['cfg_ask_symbols'].' '.$question['title'];		
	    
	    //��ȡ����Ĵ�
		$rows = $this->answer->get_answers($askaid);
		$answers = array();
		$first = $goodrateper = $badrateper = $goodrate = $badrate = $ratenum = $answernum = $myanswer = 0;
		if(count($rows) > 0){
    		foreach ($rows as $key => $row) {
                //��ȡ�ش��ߵĻ��ֵȼ�
    			$row['honor'] = gethonor($row['scores']);
    			//�ж������Ƿ����Լ��ش������
    			if($this->cfg_ml->M_ID == $row['uid']) $myanswer = 1;
    			//�ж��Ƿ��Ѿ�����Ѵ���
    			if($row['id'] == $question['bestanswer'])
    			{
    				$digestanswer = $row;
    				$ratenum = $row['goodrate'] + $row['badrate'];
    				$goodrate = $row['goodrate'];
    				$badrate = $row['badrate'];
    				$goodrateper = @ceil($goodrate*100/$ratenum);
    				$badrateper = 100 - $goodrateper;
    				//�趨����ֵ
                    $arrs = array('digestanswer','goodrate','badrate','goodrateper','badrateper','ratenum');
            		foreach ($arrs as $val) {
                        $GLOBALS[$val] = $$val;  
            		}
    			}else{
    			    $answernum = $answernum + 1;
    			    $row['floor'] = $answernum;
    				$answers[] = $row;
    			}
    		}
    	}
    	//�趨����ֵ
        $arrs = array('nav','navtitle','question','publisher','myanswer','answernum','answers');
		foreach ($arrs as $val) {
            $GLOBALS[$val] = $$val;  
		}
		//����ģ��
		$this->SetTemplate('question.htm');
        $this->Display();
    }

	//��������
	function ac_ask()
	{
        //����ģ��
		$this->SetTemplate('ask1.htm');
        $this->Display();
	}
	
	//��������
	function ac_ask_complete()
	{
	    $title = request('title', '');
	    $title = strip_tags($title);
	    //��ȡ��Ŀ��Ϣ
		$tids = "var class_level_1=new Array( \n";
		$tid2s = "var class_level_2=new Array( \n";
		foreach($GLOBALS['asktypes'] as $asktype) {
			if($asktype['reid'] == 0){
				$tids .= 'new Array("'.$asktype['id'].'","'.$asktype['name'].'"),'."\n";
			}else{
				$tid2s .= 'new Array("'.$asktype['reid'].'","'.$asktype['id'].'","'.$asktype['name'].'"),'."\n";
			}
		}
		$tids = substr($tids,0,-2)."\n";
		$tid2s = substr($tid2s,0,-2)."\n";
		$tids .= ');';
		$tid2s .= ');';
		//��վtitle
		$navtitle = '����-'.$GLOBALS['cfg_ask_sitename'];
		//��ǰλ��
		$nav = $GLOBALS['cfg_ask_position'].' ����';
        //�趨����ֵ
        $arrs = array('nav','tids','tid2s','navtitle');
		foreach ($arrs as $val) {
            $GLOBALS[$val] = $$val;  
		}
	    //����ģ��
		$this->SetTemplate('ask3.htm');
        $this->Display();
	}

	//������������
	function ac_ask_save()
	{
		$data['title'] = strip_tags(request('title', ''));
		$data['content'] = request('content', '');
		$data['anonymous'] = request('anonymous', '');
		$data['reward'] = request('reward', '');
		$data['scores'] = request('scores', '');
		$data['faqkey'] = request('faqkey', '');
		$data['vdcode'] = request('vdcode', '');
		$data['safeanswer'] = request('safeanswer', '');
		$ClassLevel1 = request('ClassLevel1', '');
		$ClassLevel2 = request('ClassLevel2', '');
		$data['uid'] = $this->cfg_ml->M_ID;
		$data['timestamp'] = $GLOBALS['cfg_ask_timestamp'];
		$data['scores'] = empty($data['scores'])? 0 : intval(preg_replace("/[\d]/",'', $data['scores']));
		//�����������
		if($data['title'] == '')
		{
			ShowMsg('�������Ʋ���Ϊ��',"-1");
			exit;
		}else if(strlen($data['title']) > 80){
			ShowMsg('���ⲻ�ܴ���80�ֽ�',"-1");
			exit;
		}else if(strlen($data['title']) < 8){
			ShowMsg('���ⲻ��С��8�ֽ�',"-1");
			exit;
		}
		//�����������
		if(empty($data['content']))
		{
			ShowMsg('����˵�����ݲ���Ϊ��!',"-1");
			exit;
		}
	    //�����֤��
		if(preg_match("#7#",$GLOBALS['safe_gdopen'])){
		    $svali = GetCkVdValue();
            if(strtolower($data['vdcode']) != $svali || $svali=='')
            {
                ResetVdValue();
                ShowMsg('��֤�����', '-1');
                exit();
            }
        }
        //�����֤����
        $faqkey = isset($data['faqkey']) && is_numeric($data['faqkey']) ? $data['faqkey'] : 0;
        if($GLOBALS['gdfaq_ask'] == 'Y')
        {
            global $safefaqs; 
            if($safefaqs[$faqkey]['answer'] != $data['safeanswer'] || $data['safeanswer'] =='')
            {
                ShowMsg('��֤����𰸴���', '-1');
                exit();
            }
        }
        $data['title'] = preg_replace("#{$GLOBALS['cfg_replacestr']}#","***",HtmlReplace($data['title'], 1));
        $data['content']  = preg_replace("#{$GLOBALS['cfg_replacestr']}#","***",HtmlReplace($data['content'], -1));
		$data['anonymous'] = (!empty($data['anonymous'])) ? 1 : 0;
		$data['tid'] = $data['tid2']  = 0;
		$data['tidname'] = $data['tid2name'] = '';
		$data['userip'] = getip();
		$data['reward'] = intval($data['reward']);
		if($data['reward'] < 0) $data['reward'] = 0;
		//�������ʱ�Կ۳���Ӧ�Ļ���
		$needscore = $data['anonymous'] * $GLOBALS['cfg_ask_anscore'] + $data['reward'];
		//�жϻ������
		if($this->cfg_ml->M_Scores < $needscore)
		{
			ShowMsg('���ֲ��㣬��˲��������','-1');
			exit;
		}
		//������Ŀ
		$ClassLevel1 = intval($ClassLevel1);
		if($ClassLevel1 < 1)
		{
			ShowMsg('δָ����Ŀ����Ŀid����ȷ���뷵��','-1');
			exit;
		}
		$ClassLevel2 = intval($ClassLevel2);
		if($ClassLevel2 != 0) $where = " WHERE id in ($ClassLevel1,$ClassLevel2)";
		else $where = "WHERE id='$ClassLevel1'";
		$rows = $this->type->get_asktype($where);
		foreach ($rows as $row) {
			if($row['id'] == $ClassLevel1)
			{
				$data['tidname'] = $row['name'];
				$data['tid'] = $row['id'];
			}elseif($row['id'] == $ClassLevel2 && $row['reid'] == $ClassLevel1){
				$data['tid2name'] = $row['name'];
				$data['tid2'] = $row['id'];
			}
		}
	
		//�������ʱ��
		$data['expiredtime'] = $GLOBALS['cfg_ask_timestamp'] + 86400 * $GLOBALS['cfg_ask_expiredtime'];
		//�������Ч�����Ƿ����ͬ��������
		$rs = $this->question->get_title($data['uid'],$data['title']);
		if($rs){
            ShowMsg('�벻Ҫ�ظ�����ͬһ����,�����ĵȴ����..', "index.php");
    		exit; 
		}
		//��������
		$rs = $this->question->save_ask($GLOBALS['cfg_ask_ifcheck'],$data);
		if($rs) 
		{
		    //��ȡ����id
		    $maxid = $this->question->get_maxid($GLOBALS['cfg_ask_timestamp']);
    		//������Ŀͳ����Ϣ
    		$this->type->update_asktype($data['tid']);
    		if($data['tid2'] > 0) $this->type->update_asktype($data['tid2']);
    		//���ִ���
    		$this->scores->update_scores($data['uid'],$needscore);
    		//�����ӵĻ��棬����idд�����ݿ�
    		clearmyaddon($maxid, $data['title']);
    		ShowMsg('�������ʳɹ������δ��ʾ���⣬˵��������Ա��δ����������', "?ct=question&askaid=".$maxid);
    		exit; 
    	}else{
    		ShowMsg('��������ʧ��', "-1");
    		exit; 
    	}	
	}
	
	//�޸�����
	function ac_edit()
	{
        global $config;
	    $askaid = request('askaid', '');
	    $askaid = is_numeric($askaid)? $askaid : 0;
		//��ȡ����Ļ�����Ϣ
		$question = $this->question->get_one("id='{$askaid}'");
		//����������ж�
		if(!is_array($question))
		{
		    ShowMsg('�Ƿ��������뷵��','-1');
			exit;
		}
		if($this->cfg_ml->isAdmin != 1)
		{
    		if($question['uid'] != $this->cfg_ml->M_ID)
    		{
    			ShowMsg('�Ƿ��������뷵��','-1');
    			exit;
    		}else if($question['expiredtime'] < $GLOBALS['cfg_ask_timestamp']){
    			ShowMsg('�����Ѿ�����','-1');
    			exit;
    		}else if($question['status'] >= 1){
    			ShowMsg('�����Ѿ���������ѹ���,���ܱ��޸�!','-1');
    			exit;
    		}
    	}
		//��վtitle
		$navtitle = '�����޸�-'.$GLOBALS['cfg_ask_sitename'];
		//��ǰλ��
		$nav = $GLOBALS['cfg_ask_position'].' �����޸�';
		//�趨����ֵ
        $arrs = array('nav','question','navtitle');
		foreach ($arrs as $val) {
            $GLOBALS[$val] = $$val;  
		}
	    //����ģ��
		$this->SetTemplate('ask_edit.htm');
        $this->Display();
	}
	
	//�����޸�����
	function ac_edit_save()
	{
	    $data['askaid'] = request('askaid', '');
	    $data['askaid'] = is_numeric($data['askaid'])? $data['askaid'] : 0;
	    $data['title'] = request('title', '');
		$data['content'] = request('content', '');
		$data['faqkey'] = request('faqkey', '');
		$data['vdcode'] = request('vdcode', '');
		$data['safeanswer'] = request('safeanswer', '');
		//��ȡ����Ļ�����Ϣ
		$question = $this->question->get_one("id='{$data['askaid']}'");
		//����������ж�
		if($question['uid'] != $this->cfg_ml->M_ID && $this->cfg_ml->isAdmin != 1)
		{
			ShowMsg('�Ƿ��������뷵��','-1');
			exit;
		}else if($question['expiredtime'] < $GLOBALS['cfg_ask_timestamp']  && $this->cfg_ml->isAdmin != 1){
			ShowMsg('�����Ѿ�����','-1');
			exit;
		}else if($question['status'] == 1 && $this->cfg_ml->isAdmin != 1){
			ShowMsg('�����Ѿ����,���ܱ��޸�!','-1');
			exit;
		}
		//�����������
		if($data['title'] == '')
		{
			ShowMsg('�������Ʋ���Ϊ��');
			exit;
		}else if(strlen($data['title']) > 80){
			ShowMsg('���ⲻ�ܴ���80�ֽ�');
			exit;
		}else if(strlen($data['title']) < 8){
			ShowMsg('���ⲻ��С��8�ֽ�');
			exit;
		}
		//�����������
		if(empty($data['content']))
		{
			ShowMsg('����˵�����ݲ���Ϊ��!');
			exit;
		}
	    //�����֤��
		if(preg_match("#7#",$GLOBALS['safe_gdopen'])){
		    $svali = GetCkVdValue();
            if(strtolower($data['vdcode']) != $svali || $svali=='')
            {
                ResetVdValue();
                ShowMsg('��֤�����', '-1');
                exit();
            }
        }
        //�����֤����
        $faqkey = isset($data['faqkey']) && is_numeric($data['faqkey']) ? $data['faqkey'] : 0;
        if($GLOBALS['gdfaq_ask'] == 'Y')
        {
            global $safefaqs; 
            if($safefaqs[$faqkey]['answer'] != $data['safeanswer'] || $data['safeanswer'] =='')
            {
                ShowMsg('��֤����𰸴���', '-1');
                exit();
            }
        }
        $data['title']  = preg_replace("#{$GLOBALS['cfg_replacestr']}#","***",HtmlReplace($data['title'], 1));
        $data['content']  = preg_replace("#{$GLOBALS['cfg_replacestr']}#","***",HtmlReplace($data['content'], -1));
        //�����޸�����
        $set = "title = '{$data['title']}',content = '{$data['content']}'";
        $wheresql = "id ='{$data['askaid']}'";
        $rs = $this->question->update_ask($set,$wheresql);
        if($rs)
        {
            //���渽����Ϣ
    		if($addition == 1) $this->question->update_additions($addi,$data['askaid']);
            clearmyaddon($data['askaid'], $data['title']);
			ShowMsg("�༭�ɹ�!","?ct=question&askaid=".$data['askaid']);
			exit;
		}else{
		    ShowMsg("�༭ʧ��!","?ct=question&askaid=".$data['askaid']);
			exit; 
		}
	}

	//ɾ������
	function ac_del()
	{
	    $askaid = request('askaid', '');
	    $askaid = is_numeric($askaid)? $askaid : 0;
		if($this->cfg_ml->isAdmin != 1)
		{
			ShowMsg('�Ƿ��������뷵��','-1');
			exit;
		}
		$rs = $this->question->del($askaid);
		if($rs) 
		{
		    $this->question->update();
			ShowMsg("ɾ���ɹ���","index.php");
			exit;
		}else{
		    ShowMsg("ɾ��ʧ�ܣ�","index.php");
			exit; 
		}
	}
	
	//�������
	function ac_upreward()
	{
	   	$askaid = request('askaid', '');
	   	$askaid = is_numeric($askaid)? $askaid : 0;
	   	$step = request('step', '');
	   	$upreward = request('upreward', '');
	   	$uid = $this->cfg_ml->M_ID;
	   	//��ȡ����Ļ�����Ϣ
        $wheresql = "id='{$askaid}' AND status='0'";
        $field = "id, uid, dateline, solvetime, status, expiredtime,reward";
		$question = $this->question->get_one($wheresql,$field);
		if($question)
		{
			if($question['uid'] != $uid)
			{
				ShowMsg('�Ƿ��������뷵��','-1');
				exit;
			}elseif($question['expiredtime'] < $GLOBALS['cfg_ask_timestamp']){
				ShowMsg('�����Ѿ�����','-1');
				exit;
			}
		}else{
			ShowMsg('�ش�����ⲻ����','-1');
			exit;
		}
	
		if(empty($step))
		{
		    //�趨����ֵ
            $GLOBALS['question'] = $question;
		    //����ģ��
    		$this->SetTemplate('upreward.htm');
            $this->Display();
		}else{
			$upreward = intval($upreward);
			$upreward = max(0,$upreward);
	
			if($upreward > $this->cfg_ml->M_Scores)
			{
				ShowMsg('���ֲ��㣬��˲��������','-1');
				exit;
			}
	        //���ִ���
    		$this->scores->update_scores($uid,$upreward);
			//�����������
			$rs = $this->question->update_ask("reward=reward+{$upreward}","id='{$askaid}'");
			if($rs){
    			ShowMsg('�޸Ļ��ֳɹ�!',"?ct=question&askaid=".$askaid);
    			exit();
    		}else{
    		    ShowMsg('�޸Ļ���ʧ��!',"?ct=question&askaid=".$askaid);
    			exit();
    		}
		}
	}
	
	//������𰸣���������
	function ac_toend()
	{
	    $askaid = request('askaid', '');
	    $askaid = is_numeric($askaid)? $askaid : 0;
	    $uid = $this->cfg_ml->M_ID;
	    //��ȡ����Ļ�����Ϣ
        $wheresql = "id='{$askaid}' AND status='0'";
        $field = "id, uid, dateline, solvetime, status, expiredtime,reward";
		$question = $this->question->get_one($wheresql,$field);
		if($question)
		{
			if($question['uid'] != $uid)
			{
				ShowMsg('�Ƿ��������뷵��','-1');
				exit;
			}elseif($question['expiredtime'] < $GLOBALS['cfg_ask_timestamp']){
				ShowMsg('�����Ѿ�����','-1');
				exit;
			}
		}else{
			ShowMsg('�ش�����ⲻ����','-1');
			exit;
		}
	    //�����������
		$rs = $this->question->update_ask("solvetime='{$GLOBALS['cfg_ask_timestamp']}', status='1'","uid='{$uid}' AND id='{$askaid}'");
		if($rs)
		{
			ShowMsg("���óɹ���","?ct=question&askaid=".$askaid);
			exit;
		}else{
		    ShowMsg("����ʧ�ܣ�","?ct=question&askaid=".$askaid);
			exit;
		}
	}
	
	//�ظ�����
	function ac_answer()
	{
		global $cfg_ask_guestask,$cfg_cmspath;
		$content = request('content', '');
		$data['askaid'] = request('askaid', '');
		$data['askaid'] = is_numeric($data['askaid'])? $data['askaid'] : 0;
		$anonymous = request('anonymous', '');
		//����Ƿ��Ѿ����ڴ�
		$rs = $this->answer->get_answer($this->cfg_ml->M_ID,$data['askaid']);
		
		if($this->cfg_ml->M_ID < 1)
		{
			$gourl = $cfg_cmspath.'/ask/?ct=question&askaid='.$data['askaid'];
			ShowMsg('����δ��¼��Ҫ��¼����ܻظ����⣡',$cfg_cmspath.'/member/login.php?gourl='.urlencode($gourl));
			exit;
		}
		if($this->cfg_ml->M_Spacesta < 0)
		{
			ShowMsg('����û��ͨ�����,��ʱ��������,�����ĵ�....','-1');
			exit;
		}
		if($rs)
		{
            ShowMsg('�����ظ��ظ�ͬһ����!','-1');
			exit; 
		}
		if($content == '')
		{
			ShowMsg('�ش���Ϊ��!','-1');
			exit;
		}else if(strlen($content) > 10000)
		{
			ShowMsg('�ش��ܴ���10000�ֽ�','-1');
			exit;
		}
		//��ȡ����Ļ�����Ϣ
        $wheresql = "id='{$data['askaid']}'";
        $field = "tid, tid2, uid, dateline, expiredtime, solvetime";
		$question = $this->question->get_one($wheresql,$field);
		if($question)
		{
			if($question['uid'] == $this->cfg_ml->M_ID)
			{
				ShowMsg('�������Լ����ܻش��Լ�������', '-1');
				exit;
			}else if($question['expiredtime'] < $GLOBALS['cfg_ask_timestamp']){
				ShowMsg('�����Ѿ�����','-1');
				exit;
			}
			$data['tid'] = $question['tid'];
			$data['tid2'] = $question['tid2'];
			$data['userip'] = getip();
		}else{
			ShowMsg('�ش�����ⲻ����','-1');
			exit;
		}
		$data['anonymous'] = 0;
		if($GLOBALS['cfg_ask_guestanswer'] == 'Y')
		{
			$data['anonymous'] = empty($anonymous)? 0 : 1;
		}
		
		$data['content'] = isset($content) ? preg_replace("#{$GLOBALS['cfg_replacestr']}#","***",HtmlReplace($content, -1)) : '';
		$data['uid'] = $this->cfg_ml->M_ID;
		$data['username'] = $this->cfg_ml->M_LoginID;
		$data['timestamp'] = $GLOBALS['cfg_ask_timestamp'];
        //����ظ�
		$rs = $this->answer->save_answer($GLOBALS['cfg_ask_ifkey'],$data);
        if(!$rs){
			ShowMsg("�ش�����ʧ��,����ϵ����Ա!","-1");
			exit;
    	}
    	//��ȡ�ظ������id
    	$maxid = $this->answer->get_maxid($data['timestamp']);
		$ids = array($data['askaid'],$maxid);
		clearmyaddon($ids, "�ظ�");
		//�ظ�������
		$rs = $this->question->update_ask("replies=replies+1","id='{$data['askaid']}'");
		$rs = $this->question->update_ask("lastanswer=".time(),"id='{$data['askaid']}'");
		$answerscore = intval($GLOBALS['cfg_ask_answerscore']);
		//ֻҪ�ش���������ӻ���
		if($GLOBALS['cfg_ask_ifanscore'] == 'Y') $this->scores->add_scores($data['uid'],$GLOBALS['cfg_ask_answerscore']);
		ShowMsg('�ش�����ɹ�,���δ��ʾ�𰸣���ȴ�������Ա���...',"?ct=question&askaid=".$data['askaid']);
		exit;
	}
	
	//�޸Ĵ�
    function ac_reply()
	{
		$id = request('id', '');
		$id  = is_numeric($id)? $id : 0;
		//��ȡ�𰸵Ļ�����Ϣ
		$row = $this->answer->get_one("id='{$id}'","uid,askid,content");
		if(is_array($row))
		{
			if($row['uid'] != $this->cfg_ml->M_ID && $this->cfg_ml->isAdmin != 1)
			{
				ShowMsg('�Ƿ��������뷵��', '-1');
				exit;
			}
		}else{
			ShowMsg('�𸴲�����','-1');
			exit;
		}
		//�趨����ֵ
        $GLOBALS['row'] = $row;
	    //����ģ��
		$this->SetTemplate('edit_reply.htm');
        $this->Display();
	}
	
	//�����޸Ĵ�
    function ac_modifyanswer()
	{
		$content = request('content', '');
		$id = request('id', '');
		$id  = is_numeric($id)? $id : 0;
		$askaid = request('askaid', '');
		$askaid = is_numeric($askaid)? $askaid : 0;		
		$content = isset($content) ? preg_replace("#{$GLOBALS['cfg_replacestr']}#","***",HtmlReplace($content, -1)) : '';
		$uid = $this->cfg_ml->M_ID;
		$username = $this->cfg_ml->M_LoginID;
		$timestamp = $GLOBALS['cfg_ask_timestamp'];
		//��ȡ�𸴾�����Ϣ
		$answer = $this->answer->get_info($id,1);
		if(is_array($answer))
		{
		    if($this->cfg_ml->isAdmin != 1)
		    {
    			if($answer['uid'] != $uid)
    			{
    				ShowMsg('�Ƿ��������뷵��', "-1");
    				exit;
    			}elseif($answer['status'] != 0){
    				ShowMsg('�����Ѿ����', "-1");
    				exit;
    			}elseif($answer['expiredtime'] < $timestamp){
    				ShowMsg('�����Ѿ�����', "-1");
    				exit;
    			}
    		}
		}else{
			ShowMsg('�ش�����ⲻ����',"-1");
			exit;
		}
		if(trim($content) == '')
		{
			ShowMsg('�ش����ݲ���Ϊ��!',"-1");
			exit;
		}else if(strlen($content) > 10000){
			ShowMsg('�ش��ܴ���10000�ֽ�',"-1");
			exit;
		}
		$rs = $this->answer->update_answer("content='{$content}'","id='{$id}'");
		if($rs)
		{
		    $ids = array($askaid,$id);
		    clearmyaddon($ids, "�ظ�");
			ShowMsg('���Ļش��Ѿ��޸ĳɹ�������������ҳ��',"?ct=question&askaid=".$askaid);
			exit;
		}else{
			ShowMsg('�޸Ĵ�ʧ�ܣ�����������ҳ��',"-1");
			exit;
		} 
	}
	
	//ɾ����
    function ac_reply_del()
	{
	    //�ж��Ƿ�Ϊ����Ա
		if($this->cfg_ml->isAdmin != 1)
		{
		    ShowMsg("��Ȩ���д������!",'-1');
		    exit;
		}
		$id = request('id', '');
		$id  = is_numeric($id)? $id : 0;
		$rs = $this->answer->del($id);
		if($rs)
		{
			ShowMsg("�ɹ�ɾ���ظ�!","member_operations.php");
			exit;
		}else{
		    ShowMsg("ɾ���ظ�ʧ�ܣ�","member_operations.php");
			exit;
		}
	}
	
	//���ɴ�
    function ac_adopt()
	{
		$id = request('id', '');
		$id  = is_numeric($id)? $id : 0;
		$answer = $this->answer->get_info_adopt($id);
		if(is_array($answer))
		{
			if($answer['uid'] != $this->cfg_ml->M_ID)
			{
				ShowMsg('�Ƿ��������뷵��', '-1');
				exit;
			}elseif($answer['status'] != 0){
				ShowMsg('�����Ѿ����', '-1');
				exit;
			}elseif($answer['expiredtime'] < $GLOBALS['cfg_ask_timestamp']){
				ShowMsg('�����Ѿ�����', '-1');
				exit;
			}
		}else{
			ShowMsg('�ش�����ⲻ����','-1');
			exit;
		}
          
		//���ʽ���+ϵͳ����
		$reward = $answer['reward'] + $GLOBALS['cfg_ask_bestanswer'];
		//����
		$set = "solvetime='{$GLOBALS['cfg_ask_timestamp']}', status='1', bestanswer='{$id}'";
		$rs = $this->question->update_ask($set,"id='{$answer['askid']}'");
		if($rs)
		{
		    $this->answer->update_answer('ifanswer = 1',"id='{$id}'");
		    //����
		    $this->scores->add_scores($answer['answeruid'],$reward);
			ShowMsg("���ɳɹ�!","?ct=question&askaid=".$answer['askid']);
			exit;
		}else{
		    ShowMsg("����ʧ��!",'-1');
			exit;  
		}
	}
	
	//����Ѵ𰸵�����
	function ac_rate()
	{
		$type = request('type', '');
		$rate = request('rate', '');
		$askaid = request('askaid', '');
        $askaid = is_numeric($askaid)? $askaid : 0;
        $type = strip_tags($type);
        $rate = strip_tags($rate);
		if($type == 'bad') $rate = 'badrate';
		else $rate = 'goodrate';
		$cookiename = 'rated'.$askaid;
		if(!isset($_COOKIE[$cookiename])) $_COOKIE[$cookiename] = 0;
		if((!$_COOKIE[$cookiename] == $askaid))
		{	
		    $this->answer->update_answer("{$rate}={$rate}+1","id='{$askaid}'");
			makecookie($cookiename,$askaid,3600);
		}
		$row = $this->answer->get_one("id='{$askaid}'","goodrate, badrate");
		$goodrate = $row['goodrate'];
		$badrate = $row['badrate'];
		if(($goodrate + $badrate) > 0)
		{
			$goodrateper = ceil($goodrate*100/($badrate+$goodrate));
			$badrateper = 100-$goodrateper;
		}else{
			$goodrateper = $badrateper = 0;
		}
		$total=$goodrate+$badrate;
		$aid=$askaid;
		AjaxHead();
		$poststr ="<dl>
					<dt><strong>��������Ѵ𰸺ò��ã� </strong></dt>
					<dd> <a href=\"#\"  onclick=\"rate('mark',$askaid,'good')\"><img src=\"static/images/mark_g.gif\" width=\"14\" height=\"16\" />��</a> <span>$goodrateper% ($goodrate)</span> </dd>
                    <dd> <a href=\"#\"  onclick=\"rate('mark',$askaid,'bad')\"><img src=\"static/images/mark_b.gif\" width=\"14\" height=\"16\" />����</a> <span>$badrateper% ($badrate)</span></dd>
                    <dt>(Ŀǰ�� $total ��������)</dt>
				   </dl>";
	   echo $poststr;
	}
}
?>
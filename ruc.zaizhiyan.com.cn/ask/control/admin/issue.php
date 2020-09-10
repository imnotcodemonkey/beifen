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
 
class issue extends Control
{
    function issue()
	{
		parent::__construct();
		$this->temp = DEDEAPPTPL.'/admin';
		$this->lurd = new Lurd('#@__ask', $this->temp, $this->temp.'/lurd');
        $this->lurd->appName = "�������";
        $this->lurd->isDebug = FALSE;  //��������ģʽ��ÿ�ζ�������ģ��
        $this->lurd->stringSafe = 2;  //Ĭ��1(ֻ���Ʋ���ȫ��HTML[script��frame��]��0--Ϊ���ޣ�2--Ϊ��֧��HTML
        //��ȡurl
        $this->currurl = GetCurUrl();
        //����ģ��
        $this->question = $this->Model('mquestion');
	}
	
    function ac_index()
    {
        //ָ��ĳ�ֶ�Ϊǿ�ƶ��������
        $this->ac_list();
    }
    
    //�г�����
    function ac_list()
    {
        $status = request('status', '');
        $tid = request('tid', '');
        $tid2 = request('tid2', '');
        if(empty($status) or !isset($status))
        {
            $status = 4;
        }
		if($status <= 3 && $status >= -1)
		{
		     $wherequery = "WHERE status =".$status;
		     $this->lurd->SetParameter('status',$status);
		}else{
		     $wherequery = "WHERE status >= 0";
		}
        if($tid2)
		{
		     $wherequery .= " and tid2 =".$tid2;
		     $this->lurd->SetParameter('tid2',$tid2);
		}else if($tid){
		     $wherequery .= " and tid = ".$tid;
		     $this->lurd->SetParameter('tid',$tid);
		}
        $orderquery = "ORDER BY id DESC ";
        //ָ��ÿҳ��ʾ��
        $this->lurd->pageSize = 20;
        //ָ��ĳ�ֶ�Ϊǿ�ƶ��������
        $this->lurd->BindType('dateline', 'TIMESTAMP', 'Y-m-d H:i');
        //��ȡ����
        $this->lurd->ListData('id,tid,tidname,tid2,tid2name,title,digest,dateline,replies,status', $wherequery, $orderquery);
        exit();
    }
    
    //���
	function ac_check()
    {
        $ids = request('id', '');
        if(!is_array($ids))
        {
            ShowMsg('δѡ��Ҫ��˵�����!','-1');
		    exit();	 
        }
		foreach($ids as $id)
		{
			if($id == "") continue;
			//�������
			$this->question->update_ask("status='0'","id='{$id}' AND status=-1");
		}
		ShowMsg("������˳ɹ���",$this->currurl);
		exit();	 
    }
	 
	//�Ƽ�
    function ac_digest()
    {
        $ids = request('id', '');
        if(!is_array($ids))
        {
            ShowMsg('δѡ��Ҫ��˵�����!','-1');
		    exit();	 
        }
		foreach($ids as $id)
		{
			if($id == "") continue;
			$this->question->update_ask("digest='1'","id='{$id}'");
		}
		ShowMsg("�ɹ�����ѡ��������Ϊ�Ƽ���",$this->currurl);
		exit();	 
    }
	 
	 //ɾ���ʴ����
	function ac_delete()
    {
        $ids = request('id', '');
        if(!is_array($ids))
        {
            ShowMsg('δѡ��Ҫ��˵�����!','-1');
		    exit();	 
        }
		foreach($ids as $id)
		{
			$id = preg_replace("#[^0-9]#","",$id);
            if($id=="") continue;
			$this->question->del($id);
		}
		$this->question->update();
		ShowMsg("�ɹ���ɾ������ѡ�����⣡",$this->currurl);
		exit();	 
    }
	 
	//����ɾ���޸ĵȲ���
    function ac_listenall()
    {
        global $ac;
        $ac = request('bc', '');
        $this->lurd->ListenAll();
        exit();
    }
}
?>
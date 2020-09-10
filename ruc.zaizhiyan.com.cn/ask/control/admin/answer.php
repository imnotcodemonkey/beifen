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
 
class answer extends Control
{
    function answer()
	{
		parent::__construct();
		$this->temp = DEDEAPPTPL.'/admin';
		$this->lurd = new Lurd('#@__askanswer', $this->temp, $this->temp.'/lurd');
        $this->lurd->appName = "�𰸹���";
        $this->lurd->isDebug = FALSE;  //��������ģʽ��ÿ�ζ�������ģ��
        $this->lurd->stringSafe = 2;  //Ĭ��1(ֻ���Ʋ���ȫ��HTML[script��frame��]��0--Ϊ���ޣ�2--Ϊ��֧��HTML
        //��ȡurl
        $this->currurl = GetCurUrl();
        //����ģ��
        $this->answer = $this->Model('askanswer');
        $this->question = $this->Model('mquestion');
	}
	
    function ac_index()
    {
        //ָ��ĳ�ֶ�Ϊǿ�ƶ��������
        $this->ac_list();
    }
    
    //�г���
    function ac_list()
    {
        $ifcheck = request('ifcheck', '2');
        $askid = request('askid', '');
        if($ifcheck == 0)
		{
		     $wherequery = "WHERE ifcheck = 0";
		     $this->lurd->SetParameter('ifcheck',0);
		}else if($ifcheck == 1){
		     $wherequery = "WHERE ifcheck = 1";
		     $this->lurd->SetParameter('ifcheck',1);
		}else{
		     $wherequery = "";
		}
		if($askid)
		{
		     $wherequery .= "WHERE askid =".$askid;
		     $this->lurd->SetParameter('askid',$askid);
		}
		$orderquery = "ORDER BY id DESC ";
        //ָ��ÿҳ��ʾ��
        $this->lurd->pageSize = 20;
        //ָ��ĳ�ֶ�Ϊǿ�ƶ��������
        $this->lurd->BindType('dateline', 'TIMESTAMP', 'Y-m-d H:i');
        //��ȡ����
        $this->lurd->ListData('id,askid,uid,username,dateline,content,ifcheck', $wherequery, $orderquery);
        exit();
    }
    
    //���
	function ac_check()
    {
        $ids = request('id', '');
        if(!is_array($ids))
        {
            ShowMsg('δѡ��Ҫ��˵Ĵ�!','-1');
		    exit();	 
        }
		$rs = $this->answer->check($ids);
		if($rs)
		{
    		ShowMsg("��˳ɹ���",$this->currurl);
    		exit();	
    	}else{
    	    ShowMsg("���ʧ�ܣ�",$this->currurl);
    		exit();	
    	} 
    }
    
    //ɾ��
	function ac_delete()
    {
        $ids = request('id', '');
        if(!is_array($ids))
        {
            ShowMsg('δѡ��Ҫɾ���Ĵ�!','-1');
		    exit();	 
        }
        foreach($ids as $id)
		{
			$id = preg_replace("#[^0-9]#","",$id);
            if($id=="") continue;
			$rs = $this->answer->del($id);
			if(!$rs)
    		{
        		ShowMsg("ɾʧ�ܣ�",$this->currurl);
        		exit();	
        	}
		}
	    $this->question->update();
	    ShowMsg("ɾ���ɹ���",$this->currurl);
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
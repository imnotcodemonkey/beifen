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
 
class index extends Control
{
    // Ϊ����PHP4��ʹ��ͬ��������Ϊ����
    function index()
    {
        //����ģ��
        parent::__construct();
        $this->question = $this->Model('mquestion');
        $this->scores = $this->Model('mscores');
    }
    
    function ac_index()
    { 
        global $cfg_soft_lang;
        $row = 8;
        //�Ƽ�����
        $digests = $this->question->get_digests(7);
       
        //�����������
        $notoks = $this->question->get_all('status=0','ORDER BY disorder DESC, dateline DESC',$row);
        
        //�½��������
        $solutions = $this->question->get_all('status=1','ORDER BY solvetime DESC',$row);
		 
		//�߷���������
        $rewards = $this->question->get_all('status=0','ORDER BY reward DESC',$row);
		
		//��ȡ������
		$solvenum = $this->question->get_total();
		
		//��ҳ�õ�Ƭ
		if(file_exists(DEDEASK."/data/cache/slide.inc")) {
            require_once(DEDEASK."/data/cache/slide.inc");
            if($cfg_soft_lang == 'utf-8')
            {
                $row = AutoCharset(unserialize(utf82gb($data)));
            }else{
                $row = unserialize($data); 
            } 
        }
        
        //�������ӵ�ַ
	    if($GLOBALS['cfg_ask_rewrite'] == 'Y')
	    {
	        $digests = makerewurl($digests,'id');
	        $notoks = makerewurl($notoks,'id');
	        $solutions = makerewurl($solutions,'id');
	        $rewards = makerewurl($rewards,'id');
	    }else{
	        $digests = makeurl($digests,'id');
	        $notoks = makeurl($notoks,'id');
	        $solutions = makeurl($solutions,'id');
	        $rewards = makeurl($rewards,'id');
	    }
        
        //�趨����ֵ
        $GLOBALS['row'] = $row;
		$GLOBALS['digests'] = $digests;
		$GLOBALS['notoks'] = $notoks;
		$GLOBALS['rewards'] = $rewards;
		$GLOBALS['solutions'] = $solutions;
		$GLOBALS['solvenum'] = $solvenum;
		//����ģ��
		$this->SetTemplate('index.htm');
        $this->Display();
    }
    
    //�����ܻ�������
    function ac_scores()
    {
        $memberlists = $this->scores->get_scores();
        if(count($memberlists) > 0)
        {   
            $row = serialize($memberlists);
            $configstr = "<"."?php\r\n\$memberlists = '".$row."';";
            file_put_contents(DEDEASK.'/data/cache/scores.inc', $configstr);	
        }
    }
}
?>
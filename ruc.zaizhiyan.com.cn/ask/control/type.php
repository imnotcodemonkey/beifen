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
 
class type extends Control
{
    function type()
	{
	    parent::__construct();
		$this->type = $this->Model('mtype');
	}
	
    function ac_index()
    {
        $asktypes = $this->type->get_alltype();
        //��ǰλ��
		$nav = $GLOBALS['cfg_ask_position'].'<a href="#">ȫ������</a>';
		if(!count($asktypes) > 0)
		{
            ShowMsg('Ŀǰ��û�з��࣬������������ݣ�','-1');
    	    exit(); 
		}
		//�趨����ֵ
		$GLOBALS['nav'] = $nav;
		$GLOBALS['asktypes'] = $asktypes;
		//����ģ��
		$this->SetTemplate('type.htm');
        $this->Display();
    }
}
?>
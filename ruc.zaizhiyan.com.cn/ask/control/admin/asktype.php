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
 
class asktype extends Control
{
    function asktype()
	{
		parent::__construct();
        //��ȡurl
        $this->currurl = GetCurUrl();
        //��ȡ���
        require_once DEDEASK.'/data/asktype.inc.php';
        $this->asktypes = $asktypes;
        $this->style = 'admin';
        //����ģ��
        $this->type = $this->Model('mtype');
	}
    
    function ac_index()
    {
        $asktypes = array_filter($this->asktypes, array(&$this, 'oneeven'));
        $asktype_sons = array_filter($this->asktypes, array(&$this, 'twoeven'));
        foreach ($asktypes as $key => $asktype) {
            $son = "";
            foreach ($asktype_sons as $asktype_son) {
                if($asktype_son['reid'] == $asktypes[$key]['id']){
                    $son .= '<tr>
                    <td align="center">'.$asktype_son['id'].'</td>
                    <td> |--'.$asktype_son['name'].'</td>
                    <td><input type="text" name="disorders['.$asktype_son['id'].']" value="'.$asktype_son['disorder'].'" /></td>
                    <td align="center"><a href="?ct=asktype&ac=edit&amp;id='.$asktype_son['id'].'&height=200&amp;width=450" class=\'thickbox\'>�޸�</a> 
                    <a href="#" onClick="javascript:del('.$asktype_son['id'].')">ɾ��</a></td>
                    </tr>';
                }      
            } 
            $asktypes[$key]['son'] = $son;
        }
        //�趨����ֵ
        $GLOBALS['asktypes'] = $asktypes;
		//����ģ��
		$this->SetTemplate('asktype_list.htm');
        $this->Display();
    }
    
    //�������鵥Ԫ,��ȡһ������
    function oneeven($var)
    {
       return($var['reid'] == 0);
    }
    
    //�������鵥Ԫ,��ȡ��������
    function twoeven($var)
    {
       return($var['reid'] != 0);
    }
    
    //��������
    function ac_update()
    {
        $disorders = request('disorders', '');
    	$rs = $this->type->update($disorders);
    	if($rs)
    	{
    	    $this->updatecache();
    		ShowMsg("��������ɹ�!","?ct=asktype");
    		exit();
    	}else{
    		ShowMsg('��������ʧ��','-1');
    		exit();
    	}
    }
    
    //�༭
    function ac_edit()
    {
        $id = request('id', '');
    	$asktype = $this->type->get_onetype($id);
    	if(is_array($asktype))
    	{
    	    $sectorscache = $this->type->get_optiontype(1,$id,$asktype['reid']);
    	    //�趨����ֵ
            $GLOBALS['id'] = $id;
            $GLOBALS['asktype'] = $asktype;
            $GLOBALS['sectorscache'] = $sectorscache;
    		//����ģ��
    		$this->SetTemplate('asktype_edit.htm');
            $this->Display();
	    }else{
	        ShowMsg('�༭���಻����','-1');
    		exit();
	    }
    }
    
    //����༭
    function ac_edit_save()
    {
        $data['id'] = request('id', '');
        $data['name'] = request('name', '');
        $data['reid'] = request('reid', '');
        $data['disorder'] = request('disorder', '');
        if($data['name'] == "")
        {
            ShowMsg('�������Ʋ���Ϊ��','?ct=asktype');
			exit();
        }
        $rs = $this->type->save_edit($data);
		if($rs)
		{
		    $this->updatecache();
			ShowMsg('�༭����ɹ��������ط������ҳ��','?ct=asktype');
			exit();
		}else{
			ShowMsg('�༭����ɹ��������ط������ҳ��','?ct=asktype');
			exit();
		}
    }
    
    //ɾ��
    function ac_delete()
    {
        $id = request('id', '');
        $rs = $this->type->del_type($id);
		if($rs)
		{
		    $this->updatecache();
			ShowMsg('ɾ������ɹ��������ط������ҳ��', '?ct=asktype');
			exit();
		}else{
			ShowMsg('ɾ������ʧ�ܣ������ط������ҳ�� ','?ct=asktype');
			exit();
		}
    }
    
    //���ӷ���
    function ac_add()
    {
        $ml = request('ml', '');
        $id = request('id', '');
        $name = request('name', '');
        if($ml != 1) $sectorscache = $this->type->get_optiontype(2);
        //�趨����ֵ
        $GLOBALS['ml'] = $ml;
        $GLOBALS['id'] = $id;
        $GLOBALS['name'] = $name;
        $GLOBALS['sectorscache'] = $sectorscache;
		//����ģ��
		$this->SetTemplate('asktype_add.htm');
        $this->Display();
    }
    
    //���ӷ���
    function ac_add_save()
    {
        $data['name'] = request('name', '');
        $data['reid'] = request('reid', '');
        if(empty($data['name']))
        {
            ShowMsg('�������Ʋ���Ϊ��', '?ct=asktype');
			exit();  
        }
        $rs = $this->type->save_add($data);
        if($rs)
		{
		    $this->updatecache();
			ShowMsg('���ӷ���ɹ��������ط������ҳ��','?ct=asktype');
			exit();
		}else{
			ShowMsg('���ӷ���ɹ��������ط������ҳ��','?ct=asktype');
			exit();
		}

    }
    
    //������Ŀ����
    function updatecache()
    {
        $asktypes = $this->type->get_alltype();
        $path = DEDEASK."/data/cache/asktype.inc";
        $row = serialize($asktypes);
        $configstr = "<"."?php\r\n\$asktypes = '".$row."';";
        file_put_contents($path, $configstr);	
    }
   
}
?>
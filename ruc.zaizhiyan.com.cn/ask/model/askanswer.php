<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * ��ͨ����Ĵ�
 *
 * @version        $Id: question.php 2010/12/3  shaxian $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
class askanswer extends Model
{
   
    /**
     *  ��ȡ����Ĵ�
     *
     * @param     int    $askaid
     * @return    string
     */
    function get_answers($askaid)
    {
        if($askaid)
        {
            $query = "SELECT answer.*,m.scores FROM #@__askanswer answer 
                      LEFT JOIN `#@__member` m ON m.mid=answer.uid 
                      WHERE askid='{$askaid}' AND ifcheck='1' ORDER BY dateline ASC";
            $this->dsql->SetQuery($query);
            $this->dsql->Execute();
            $rows = array();
            while($row = $this->dsql->GetArray())
            {
                $rows[] = $row;
            }
            return $rows;
    	}else{
            return FALSE;
    	}
    }
    
   /**
     *  ��ȡһ���𰸵Ļ�����Ϣ
     *
     * @param     string    $wheresql
     * @param     string    $field
     * @return    array
     */
    function get_one($wheresql = "",$field = '*')
    {
        if($field)
        {
            $row = $this->dsql->GetOne("SELECT $field FROM `#@__askanswer` WHERE $wheresql");
    		return $row;
    	}else{
            return FALSE;
    	}
    }
    
    /**
     *  ��ȡһ���𰸵ľ�����Ϣ
     *
     * @param     int    $id
     * @return    array
     */
    function get_info($id)
    {
        if($id)
        {
            $row = $this->dsql->GetOne("SELECT a.uid, k.dateline, k.solvetime, k.status, k.expiredtime FROM `#@__askanswer` a
                                        LEFT JOIN #@__ask k ON k.id=a.askid WHERE a.id='{$id}'");
    		return $row;
    	}else{
            return FALSE;
    	}
    }
    
   /**
     *  ��ȡһ���𰸵ľ�����Ϣ(���ɴ�)
     *
     * @param     int    $id
     * @return    array
     */
    function get_info_adopt($id)
    {
        if($id)
        {
            $row = $this->dsql->GetOne("SELECT a.askid,a.uid as answeruid,k.uid,k.reward,k.status,k.expiredtime
		                                FROM `#@__askanswer` a LEFT JOIN `#@__ask` k ON k.id=a.askid 
		                                WHERE a.id='{$id}'");
    		return $row;
    	}else{
            return FALSE;
    	}
    }
    
    /**
     * ����Ƿ��Ѿ����ڻظ�
     *
     * @param     int       $uid 
     * @param     string    $askid
     * @return    string
     */
    function get_answer($uid = "",$askid = "")
    {
        if($uid && $askid)
        {
            $row = $this->dsql->GetOne("SELECT id FROM `#@__askanswer` WHERE uid = '{$uid}' AND askid = '{$askid}'");
    		if(is_array($row)) return TRUE;
    		else return FALSE;
    	}else{
            return TRUE;
    	}
    }
    
   /**
     *  ����ظ�
     *
     * @param     array    $ids
     * @return    string
     */
    function save_answer($type = "",$data = array())
    {
        if(is_array($data))
        {
            if($type == 'Y') $status = "0";
            else $status = "1";
		    $query = "INSERT INTO `#@__askanswer` (askid, tid, tid2, uid, username, anonymous, userip, dateline, content, ifcheck)
			          VALUES ('{$data['askaid']}','{$data['tid']}', '{$data['tid2']}', '{$data['uid']}', '{$data['username']}', '{$data['anonymous']}', '{$data['userip']}', '{$data['timestamp']}', '{$data['content']}', '{$status}')";
    		if($this->dsql->ExecuteNoneQuery($query)) return TRUE;
    		else return FALSE;
    	}else{
            return FALSE;
    	}
    }
    
    /**
     *  ��ȡ����id
     *
     * @param     time    $timestamp
     * @return    string
     */
    function get_maxid($timestamp)
    {
        if($timestamp)
        {
            $row = $this->dsql->GetOne("SELECT max(id) AS id FROM `#@__askanswer` WHERE dateline = '{$timestamp}'");
    		return $row['id'];
    	}else{
            return FALSE;
    	}
    }
    
   /**
     *  ������ж�#@__askanswer���update��Ϊ
     *
     * @param     string    $set
     * @param     string    $wheresql
     * @return    int
     */
    function update_answer($set = "",$wheresql = "")
    {
        if($wheresql && $set)
        {
            $query = "UPDATE `#@__askanswer` SET $set WHERE $wheresql";
            if($this->dsql->ExecuteNoneQuery($query)) return TRUE;
            else return FALSE;
    	}else{
            return FALSE;
    	}
    }
    
    /**
     *  ���
     *
     * @param     array    $ids
     * @return    string
     */
    function check($ids = array())
    {
        if(count($ids) > 0)
        {
            foreach($ids as $id)
        	{
        		$id = preg_replace("#[^0-9]#","",$id);
        		if($id=="") continue;
        		$query = "UPDATE `#@__askanswer` SET ifcheck='1' WHERE id='{$id}' AND ifcheck='0'";
    		    $this->dsql->ExecuteNoneQuery($query);
        	}
    		return TRUE;
    	}else{
            return FALSE;
    	}
    }
    
    /**
     *  ɾ��
     *
     * @param     int    $id
     * @return    string
     */
    function del($id)
    {
        if($id)
        {
    		$row = $this->dsql->GetOne("SELECT askid FROM `#@__askanswer` WHERE id='{$id}'");
    		$query = "DELETE FROM #@__askanswer WHERE id='{$id}'";
		    if($this->dsql->ExecuteNoneQuery($query))
		    {
		        $query2 = "UPDATE `#@__ask` SET replies = replies - 1 WHERE id='{$row['askid']}'";
    		    $this->dsql->ExecuteNoneQuery($query2);
    		    global $cfg_basedir,$cfg_remote_site;
    		    //����Զ��վ���򴴽�FTP��
                if($cfg_remote_site == 'Y')
                {
                    require_once(DEDEINC.'/ftp.class.php');
                    if(file_exists(DEDEDATA."/cache/inc_remote_config.php"))
                    {
                        require_once DEDEDATA."/cache/inc_remote_config.php";
                    }
                    if(empty($remoteuploads)) $remoteuploads = 0;
                    if(empty($remoteupUrl)) $remoteupUrl = '';
                    //��ʼ��FTP����
                    $ftpconfig = array(
                        'hostname' => $rmhost, 
                        'port' => $rmport,
                        'username' => $rmname,
                        'password' => $rmpwd
                
                    );
                    $ftp = new FTP; 
                    $ftp->connect($ftpconfig);
                }
                $query = "SELECT url FROM `#@__uploads_ask` WHERE rid ='{$id}' AND type = 0";
                $this->dsql->SetQuery($query);
                $this->dsql->Execute();
                while($row = $this->dsql->GetArray())
                {
                    if($cfg_remote_site == 'Y' && $remoteuploads == 1)
                    {
                        $ftp->delete_file($row['url']);
                    }else{
                        @unlink($cfg_basedir.$row['url']); 
                    }
                }
                $this->dsql->ExecuteNoneQuery("DELETE FROM `#@__uploads_ask` WHERE rid ='{$id}' AND type = 0");
                return TRUE;
            }else{
                return FALSE;  
            }
    	}else{
            return FALSE;
    	}
    }
}
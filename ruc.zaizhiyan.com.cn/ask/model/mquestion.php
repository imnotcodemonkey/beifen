<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * ��ͨ����
 *
 * @version        $Id: question.php 2010/12/3  shaxian $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
class mquestion extends Model
{   
    
    /**
     *  ��ȡһ������Ļ�����Ϣ
     *
     * @param     string    $wheresql
     * @param     string    $field
     * @return    array
     */
    function get_one($wheresql = "",$field = '*')
    {
        if($field)
        {
            $row = $this->dsql->GetOne("SELECT $field FROM `#@__ask` WHERE $wheresql");
    		return $row;
    	}else{
            return FALSE;
    	}
    }
    
    /**
     *  ��ȡ��Ӧ��������������
     *
     * @param     string    $wheresql
     * @param     string    $field
     * @param     int       $row
     * @return    array
     */
    function get_all($wheresql = "",$orderby = "",$row = '10',$field = 'id, tid, tidname, tid2, tid2name,title,reward,replies')
    {
        if($field)
        {
            $arrays = array();
            $query = "SELECT $field FROM `#@__ask` WHERE $wheresql $orderby limit 0,$row";
            $this->dsql->SetQuery($query);
            $this->dsql->Execute();
    		while($arr = $this->dsql->GetArray())
    		{
    			$arrays[] = $arr; 
    		}
    		return $arrays;
    	}else{
            return FALSE;
    	}
    }
    
    /**
     *  ��ȡ��Ӧ�������Ƽ���������
     *
     * @param     int       $row
     * @return    array
     */
    function get_digests($row = '10')
    {
        $arrays = array();
        $query = "SELECT a.id, a.title,m.userid FROM `#@__ask` a 
                  LEFT JOIN `#@__member` m ON m.mid=a.uid 
                  WHERE a.digest = 1 ORDER BY dateline DESC LIMIT 0,$row";
        $this->dsql->SetQuery($query);
        $this->dsql->Execute();
		while($arr = $this->dsql->GetArray())
		{
			$arrays[] = $arr; 
		}
		return $arrays;
    }
    
    
   /**
     *  ��ȡһ������Ļ�����Ϣ������������Ϣ
     *
     * @param     int    $askaid
     * @return    string
     */
    function get_info($askaid,$rs = "")
    {
        if($askaid)
        {
            if($rs == 1) $wheresql = "AND ask.status = 0";
            else $wheresql = "";
            $query = "SELECT ask.*, mem.userid as username, mem.scores,mem.mtype,mem.face FROM `#@__ask` ask 
                      LEFT JOIN `#@__member` mem ON mem.mid=ask.uid 
                      WHERE ask.id='{$askaid}' {$wheresql}";
    		$this->dsql->ExecuteNoneQuery($query);
    		return $this->dsql->GetOne($query);
    	}else{
            return FALSE;
    	}
    }
    
   /**
     *  ��ȡ���������
     *
     * @return    string
     */
    function get_total()
    {
        $data['solving'] = 0; //δ�����������
		$data['solved'] = 0;  //�ѽ����������
		$query = "SELECT status,COUNT(status) AS dd FROM `#@__ask` GROUP BY status ";
		$this->dsql->Execute('me',$query);
		while($tmparr = $this->dsql->GetArray())
		{
			if($tmparr['status']==0)
			{
				$data['solving'] = $tmparr['dd'];
			}else{
				$data['solved'] += $tmparr['dd'];
			}
		}
		return $data;
    }
    
   /**
     *  ������ж�#@__ask���update��Ϊ
     *
     * @param     string    $set
     * @param     string    $wheresql
     * @return    int
     */
    function update_ask($set = "",$wheresql = "")
    {
        if($wheresql && $set)
        {
            $query = "UPDATE #@__ask SET $set WHERE $wheresql";
            if($this->dsql->ExecuteNoneQuery($query)) return TRUE;
            else return FALSE;
    	}else{
            return FALSE;
    	}
    }
   
     
   /**
     * �������Ч�����Ƿ����ͬ��������
     *
     * @param     int       $uid 
     * @param     string    $title
     * @return    string
     */
    function get_title($uid = "",$title = "")
    {
        if($uid && $title)
        {
            $row = $this->dsql->GetOne("SELECT id FROM `#@__ask` WHERE uid = '{$uid}' AND title = '{$title}' AND dateline < expiredtime");
    		if(is_array($row)) return TRUE;
    		else return FALSE;
    	}else{
            return TRUE;
    	}
    }
		
    /**
     *  ���������ӵ�����
     *
     * @param     string      $type
     * @param     array    $data
     * @return    string
     */
    function save_ask($type = "",$data = array())
    {
        if(is_array($data))
        {
            if($type == 'Y') $status = "-1";
            else $status = "0";
		    $query = "INSERT INTO `#@__ask`(tid, tidname, tid2, tid2name, uid, anonymous, status, title, reward, dateline, expiredtime, ip ,content) VALUES ('{$data['tid']}', '{$data['tidname']}', '{$data['tid2']}', '{$data['tid2name']}', '{$data['uid']}', '{$data['anonymous']}', '{$status}', '{$data['title']}', '{$data['reward']}', '{$data['timestamp']}', '{$data['expiredtime']}', '{$data['userip']}', '{$data['content']}')";
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
            $row = $this->dsql->GetOne("SELECT max(id) AS id FROM `#@__ask` WHERE dateline = '{$timestamp}'");
    		return $row['id'];
    	}else{
            return FALSE;
    	}
    }
    
    
   /**
     *  ��ȡ�ҵ�����
     *
     * @param     int    $uid
     * @param     int    $start
     * @param     int    $end
     * @return    string
     */
    function get_myask($uid = "",$start= "",$end = "")
    {
        $query = "SELECT id, tid, tidname, tid2, tid2name, uid, title, digest, reward, dateline, expiredtime, 
	              solvetime, status, replies FROM `#@__ask` WHERE uid='{$uid}'
	              ORDER BY dateline DESC LIMIT {$start},{$end}";
	    $this->dsql->SetQuery($query);
		$this->dsql->Execute();
		$myasks = array();
		while($row = $this->dsql->GetArray())
		{
			$myasks[] = $row;
		}
		return $myasks;
    }
    
    /**
     *  ����ɾ��һ������
     *
     * @param     int    $askaid
     * @return    string
     */
    function del($askaid)
    {
        if($askaid){
            $query = "DELETE FROM `#@__ask` WHERE id='{$askaid}'";
    		if($this->dsql->ExecuteNoneQuery($query))
    		{
    		    $this->dsql->ExecuteNoneQuery("DELETE FROM `#@__askanswer` WHERE askid='{$askaid}'");
    		    $this->dsql->ExecuteNoneQuery("DELETE FROM `#@__askcomment` WHERE askid='{$askaid}'");
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
                $query = "SELECT url FROM `#@__uploads_ask` WHERE arcid='{$askaid}' AND type = 0";
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
                $this->dsql->ExecuteNoneQuery("DELETE FROM `#@__uploads_ask` WHERE arcid ='{$askaid}' AND type = 0");
                return  TRUE;
    		}else{
    		    return  FALSE;
    		}
    	}else{
    	    return  FALSE;
    	}
    }
    
    /**
     * ����ͳ����Ϣ
     * @return    string
     */
    function update()
    {
        $query = "SELECT id, reid FROM `#@__asktype`";
	    $this->dsql->SetQuery($query);
        $this->dsql->Execute();
        while($row = $this->dsql->GetArray())
        {
            if($row['reid'] == 0)
    		{
    			$this->dsql->SetQuery("SELECT COUNT(*) AS dd FROM `#@__ask` WHERE tid='{$row['id']}' ");
    			$this->dsql->Execute('top');
    			$asknum = $this->dsql->GetArray('top');
    			$this->dsql->ExecuteNoneQuery("UPDATE `#@__asktype` SET asknum='{$asknum['dd']}' WHERE id='{$row['id']}' ");
    		}else{
    			$this->dsql->SetQuery("SELECT COUNT(*) as dd FROM `#@__ask` WHERE tid2='{$row['id']}' ");
    			$this->dsql->Execute('sub');
    			$asknum = $this->dsql->GetArray('sub');
    			$this->dsql->ExecuteNoneQuery("UPDATE `#@__asktype` SET asknum='{$asknum['dd']}' WHERE id='{$row['id']}' ");
    		}
        }
    }
}
<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * ���ִ���
 *
 * @version        $Id: question.php 2010/12/3  shaxian $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
class mscores extends Model
{   
    /**
     * ��������ʱ�۳�����
     *
     * @param     int    $uid
     * @param     int    $needscore
     * @return    array
     */
    function update_scores($uid = "" ,$needscore = 0)
    {
        if($uid){
            $query = "UPDATE `#@__member` SET scores=scores-$needscore WHERE mid='$uid'";
            if($this->dsql->ExecuteNoneQuery($query)) return TRUE;
            else return FALSE;
        }else{
            return FALSE;
        }
    }
    
    /**
     * ����ظ�ʱ���ӻ���
     *
     * @param     int    $uid
     * @param     int    $needscore
     * @return    array
     */
    function add_scores($uid = "" ,$needscore = 0)
    {
        if($uid){
            $query = "UPDATE `#@__member` SET scores=scores+$needscore WHERE mid='$uid'";
            if($this->dsql->ExecuteNoneQuery($query)) return TRUE;
            else return FALSE;
        }else{
            return FALSE;
        }
    }
    
    /**
     * ��ȡ�ܻ�������
     *
     * @return    array
     */
    function get_scores($row = 10)
    {
        $memberlists = array();
        $query = "SELECT mb.*,ms.spacename,ms.sign FROM `#@__member` mb
        LEFT JOIN `#@__member_space` ms ON ms.mid = mb.mid
        WHERE mb.spacesta>-1 AND mb.matt<10 ORDER BY mb.scores DESC LIMIT 0,$row";
        $this->dsql->Execute('me',$query);
        while($arr = $this->dsql->GetArray())
        {
        	$memberlists[] = $arr; 
        }
        return $memberlists;
    }
    
}
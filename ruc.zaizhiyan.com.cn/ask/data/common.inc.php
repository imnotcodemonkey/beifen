<?php  if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 
 * @version        2011/2/11  ɳ�� $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2011, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 *
 **/
@set_magic_quotes_runtime(0);
require_once DEDEINC.'/memberlogin.class.php';
//�������
require_once DEDEASK.'/data/asktype.inc.php';
//�����������
require_once DEDEASK.'/data/scores.inc.php';
//�������function
require_once DEDEASK.'/data/functions.inc.php';

@ob_start();
if($cfg_ask == 'N')
{
	showmsg('�ʴ�ϵͳ�ѹرգ��뷵��', '-1');
	exit;
}
//��Ա��Ϣ
$cfg_ml = new MemberLogin();
//�ʴ�ģ���б���ʾ������
$cfg_ask_tpp = $cfg_ask_tpp = 20;
$cfg_ask_tpp = max(1,$cfg_ask_tpp);
//�ʴ�ģ��������Ч��
$cfg_ask_expiredtime = isset($cfg_ask_expiredtime) && is_numeric($cfg_ask_expiredtime) ? $cfg_ask_expiredtime : 20;
$cfg_ask_expiredtime = max($cfg_ask_expiredtime, 1);
//��ǰϵͳʱ��
$cfg_ask_timestamp = time();
//��ȡ��ַ
$cfg_ask_curl = geturl(1);
//�Ի�ȡ�ĵ�ַ���д����������
$callurl = str_replace('&','^',$cfg_ask_curl);
//��Աע����ת��ַ
$cfg_ask_member = $cfg_basehost.'/member/login.php?gourl='.$callurl;
//�ʴ�ǰĿ¼
$cfg_ask_directory = $cfg_cmspath.$cfg_ask_directory;
//�ʴ���������վ���Ե�ַ
$cfg_ask_basehost = empty($cfg_cmspath)? $cfg_basehost : $cfg_basehost.$cfg_cmspath;
//��ǰλ��
$cfg_ask_position = '<a href="'.$cfg_ask_directory.'">'.$cfg_ask_sitename.'</a> '.$cfg_ask_symbols;
//������ö�����������ֹ����Ŀ¼����
if($cfg_ask_isdomain == 'Y')
{
    if(!preg_match ("#$cfg_ask_domain#i",$callurl))
    {
        showmsg('�Ƿ�����',$cfg_ask_domain);
	    exit;
    } 
}
?>
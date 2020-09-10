<?php
/**
 * 安全联盟网站安全管家 v1.0
 * 2014.04.10
 */
//ks_anquan.php

ob_start();
define('KS_SAFE', 'POWERED BY KNOWNSEC');
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~ E_NOTICE);
set_time_limit(0);
ignore_user_abort(1);
ini_set('memory_limit', '128M');
date_default_timezone_set("GMT");

define('KS_SAFE_VERSION',               '1.3.5');
define('KS_SAFE_APP_ID',                'KS_ANQUAN_APP_ID');
define('KS_SAFE_SITE_ID',               '53abdfdda4dd1340d82f5a26');
define('KS_SAFE_TASK_ID',               'KS_ANQUAN_TASK_ID');
define('KS_SAFE_SECRET_ID',             'rDmpr3mIdc5RuvD0ezRqJQKeQSJXyaJfFunzXUcoRbE0HFL8Pa');
define('KS_SAFE_DOMAIN',                'http://zhanzhang.anquan.org/');
define('KS_SAFE_PLATFORM',              KS_SAFE_DOMAIN.'anquan/');
define('KS_SAFE_LIB_PLATFORM',          KS_SAFE_DOMAIN.'guanjia/'); 
define('KS_SAFE_GET_APP_URL',           KS_SAFE_PLATFORM.'fetch-app/');
define('KS_SAFE_CHK_UPGRADE_URL',       KS_SAFE_PLATFORM.'check-upgrade/');
define('KS_SAFE_GET_UPGRADE_URL',       KS_SAFE_PLATFORM.'client-upgrade/');
define('KS_SAFE_GET_CONFIG_URL',        KS_SAFE_PLATFORM.'get-task-config/');
define('KS_SAFE_RET_BACKUP_HASH_URL',   KS_SAFE_PLATFORM.'receive-backup-hash/');
define('KS_SAFE_RET_BACKUP_URL',        KS_SAFE_PLATFORM.'receive-backup/');
define('KS_SAFE_RET_RESULT_URL',        KS_SAFE_PLATFORM.'task-result/');
define('KS_SAFE_RET_LOG_URL',           KS_SAFE_PLATFORM.'record-task-log/');
define('KS_SAFE_GET_LIB_URL',           KS_SAFE_LIB_PLATFORM.'filelist'); 

(@__DIR__ == '__DIR__') && define('__DIR__', realpath(dirname(__FILE__))); 
define('KS_SAFE_ROOT_DIR',              __DIR__);
define('KS_SAFE_KSAFE_DIR',             KS_SAFE_ROOT_DIR.DIRECTORY_SEPARATOR.'ksafe');
define('KS_SAFE_KSAFE_APP_DIR',         KS_SAFE_KSAFE_DIR.DIRECTORY_SEPARATOR.'app');
define('KS_SAFE_KSAFE_TMP_DIR',         KS_SAFE_KSAFE_DIR.DIRECTORY_SEPARATOR.'tmp');
define('KS_SAFE_KSAFE_LIB_DIR',         KS_SAFE_KSAFE_DIR.DIRECTORY_SEPARATOR.'lib');
define('KS_SAFE_KSAFE_BAK_DIR',         KS_SAFE_KSAFE_DIR.DIRECTORY_SEPARATOR.'bak');

/**
 * do json compat here...
 */
/**
 * Implementation of function json_encode on PHP
 * 
 * @author Alexander Muzychenko
 * @link https://github.com/alexmuz/php-json
 * @see http://php.net/json_encode
 * @license GNU Lesser General Public License (LGPL) http://www.gnu.org/copyleft/lesser.html
 */
if (!function_exists('json_encode')) {  
    function json_encode($value) 
    {
        if (is_int($value)) {
            return (string)$value;   
        } elseif (is_string($value)) {
            $value = str_replace(array('\\', '/', '"', "\r", "\n", "\b", "\f", "\t"), 
            array('\\\\', '\/', '\"', '\r', '\n', '\b', '\f', '\t'), $value);
            return '"' . $value . '"';
        } elseif (is_float($value)) {
            return str_replace(",", ".", $value);         
        } elseif (is_null($value)) {
            return 'null';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_array($value)) {
            $with_keys = false;
            $n = count($value);
            for ($i = 0, reset($value); $i < $n; $i++, next($value)) {
                if (key($value) !== $i) {
                    $with_keys = true;
                    break;
                }
            }
        } elseif (is_object($value)) {
            $with_keys = true;
        } else {
            return '';
        }
        $result = array();
        if ($with_keys) {
            foreach ($value as $key => $v) {
                $result[] = json_encode((string)$key) . ':' . json_encode($v);    
            }
            return '{' . implode(',', $result) . '}';                
        } else {
            foreach ($value as $key => $v) {
                $result[] = json_encode($v);    
            }
            return '[' . implode(',', $result) . ']';
        }
    } 
}

/**
 * Implementation of function json_decode on PHP
 *
 * @author Alexander Muzychenko
 * @link https://github.com/alexmuz/php-json
 * @see http://php.net/json_decode
 * @license GNU Lesser General Public License (LGPL) http://www.gnu.org/copyleft/lesser.html
 */
if (!function_exists('json_decode')) {

    function json_decode($json, $assoc = false)
    {
        $i = 0;
        $n = strlen($json);
        try {
            $result = json_decode_value($json, $i, $assoc);
            while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
            if ($i < $n) {
                return null;
            }
            return $result;
        } catch (Exception $e) {
            return null;
        }
    }

    function json_decode_value($json, &$i, $assoc = false)
    {
        $n = strlen($json);
        while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;

        switch ($json[$i]) {
            // object
        case '{':
            $i++;
            $result = $assoc ? array() : new stdClass();
            while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
            if ($json[$i] === '}') {
                $i++;
                return $result;
            }
            while ($i < $n) {
                $key = json_decode_string($json, $i);
                while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
                if ($json[$i++] != ':') {
                    throw new Exception("Expected ':' on ".($i - 1));
                }
                if ($assoc) {
                    $result[$key] = json_decode_value($json, $i, $assoc);
                } else {
                    $result->$key = json_decode_value($json, $i, $assoc);
                }
                while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
                if ($json[$i] === '}') {
                    $i++;
                    return $result;
                }
                if ($json[$i++] != ',') {
                    throw new Exception("Expected ',' on ".($i - 1));
                }
                while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
            }
            throw new Exception("Syntax error");
            // array
        case '[':
            $i++;
            $result = array();
            while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
            if ($json[$i] === ']') {
                $i++;
                return array();
            }
            while ($i < $n) {
                $result[] = json_decode_value($json, $i, $assoc);
                while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
                if ($json[$i] === ']') {
                    $i++;
                    return $result;
                }
                if ($json[$i++] != ',') {
                    throw new Exception("Expected ',' on ".($i - 1));
                }
                while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
            }
            throw new Exception("Syntax error");
            // string
        case '"':
            return json_decode_string($json, $i);
            // number
        case '-':
            return json_decode_number($json, $i);
            // true
        case 't':
            if ($i + 3 < $n && substr($json, $i, 4) === 'true') {
                $i += 4;
                return true;
            }
            // false
        case 'f':
            if ($i + 4 < $n && substr($json, $i, 5) === 'false') {
                $i += 5;
                return false;
            }
            // null
        case 'n':
            if ($i + 3 < $n && substr($json, $i, 4) === 'null') {
                $i += 4;
                return null;
            }            
        default:
            // number
            if ($json[$i] >= '0' && $json[$i] <= '9') {
                return json_decode_number($json, $i);
            } else {
                throw new Exception("Syntax error");
            };
        }
    }

    function json_decode_string($json, &$i)
    {
        $result = '';
        $escape = array('"' => '"', '\\' => '\\', '/' => '/', 'b' => "\b", 'f' => "\f", 'n' => "\n", 'r' => "\r", 't' => "\t");
        $n = strlen($json);
        if ($json[$i] === '"') {
            while (++$i < $n) {
                if ($json[$i] === '"') {
                    $i++;
                    return $result;
                } elseif ($json[$i] === '\\') {
                    $i++;
                    if (isset($escape[$json[$i]])) {
                        $result .= $escape[$json[$i]];
                    } else {
                        break;
                    }
                } else {
                    $result .= $json[$i];
                }
            }
        }
        throw new Exception("Syntax error");
    }

    function json_decode_number($json, &$i)
    {
        $result = '';
        if ($json[$i] === '-') {
            $result = '-';
            $i++;
        }
        $n = strlen($json);
        while ($i < $n && $json[$i] >= '0' && $json[$i] <= '9') {
            $result .= $json[$i++];
        }

        if ($i < $n && $json[$i] === '.') {
            $result .= '.';
            $i++;
            while ($i < $n && $json[$i] >= '0' && $json[$i] <= '9') {
                $result .= $json[$i++];
            }
        }
        if ($i < $n && ($json[$i] === 'e' || $json[$i] === 'E')) {
            $result .= $json[$i];
            $i++;
            if ($json[$i] === '-' || $json[$i] === '+') {
                $result .= $json[$i++];
            }
            while ($i < $n && $json[$i] >= '0' && $json[$i] <= '9') {
                $result .= $json[$i++];
            }
        }

        return (0 + $result);
    }
}

if(!function_exists('error_get_last'))
{
    set_error_handler(
        create_function(
            '$errno,$errstr,$errfile,$errline,$errcontext',
            '
                global $__error_get_last_retval__;
                $__error_get_last_retval__ = array(
                    \'type\'        => $errno,
                    \'message\'        => $errstr,
                    \'file\'        => $errfile,
                    \'line\'        => $errline
                );
                return false;
            '
        )
    );
    function error_get_last() {
        global $__error_get_last_retval__;
        if( !isset($__error_get_last_retval__) ) {
            return null;
        }
        return $__error_get_last_retval__;
    }
}


if (!class_exists('KsFunc')) {
    class KsFunc {
        static function ks_include($strFileName) {
            return include($strFileName);
        }

        static function ks_include_once($strFileName) {
            return include_once($strFileName);
        }

        static function ks_is_file($strFileName) {
            return is_file($strFileName);
        }

        static function ks_is_dir($strDirName) {
            return is_dir($strDirName);
        }

        static function ks_file_exists($strFilePath) {
            return file_exists($strFilePath);
        }

        static function ks_unlink($strFilePath) {
            if (KsFunc::ks_is_file($strFilePath)) {
                return unlink($strFilePath);
            } else if (KsFunc::ks_is_dir($strFilePath)) {
                $arFiles = array_diff(scandir($strFilePath), array('.', '..'));
                foreach ($arFiles as $strEachFilePath) {
                    self::ks_unlink($strFilePath.DIRECTORY_SEPARATOR.$strEachFilePath);
                }
                return rmdir($strFilePath);
            } else {
                return !self::ks_file_exists($strFilePath);
            }
        }

        static function ks_touch($strFileName) {
            return touch($strFileName);
        }

        static function ks_chmod($strFilePath, $nMode) {
            return chmod($strFilePath, $nMode);
        }

        static function ks_mkdir($strDirName, $nMode=0777, $bRecursive=false) {
            return mkdir($strDirName, $nMode, $bRecursive);
        }

        static function ks_is_writable($strFilePath) {
            if(is_dir($strFilePath)) {
                if (substr($strFilePath, -1)!='/' or substr($strFilePath, -1)!='\\') {
                    $strFilePath = $strFilePath.DIRECTORY_SEPARATOR;
                }
                $strTmpFile = $strFilePath.md5(mt_rand().time());
                while (is_file($strTmpFile)) {
                    $strTmpFile = $strFilePath.md5(mt_rand().time());
                }
                if($fp = @fopen($strTmpFile, 'w')) {
                    fclose($fp);
                    self::ks_unlink($strTmpFile);
                    $bWritable = 1;
                } else {
                    $bWritable = 0;
                }
            } else {
                if($fp = @fopen($strFilePath, 'a+')) {
                    fclose($fp);
                    $bWritable = 1;
                } else {
                    $bWritable = 0;
                }
            }
            return $bWritable;
        }

        static function ks_is_readable($strFilePath) {
            return is_readable($strFilePath);
        }

        static function ks_file_get_contents($strFileName, $bUseIncludePath=false, $rContext=null) {
            // 请不要使用该函数做网络请求
            return file_get_contents($strFileName, $bUseIncludePath, $rContext);
        }

        static function ks_file_put_contents($strFileName, $strData, $nFlag=0, $rContext=null) {
            if($strFileName){
                return file_put_contents($strFileName, $strData, $nFlag, $rContext);
            }
        }

        # 以下这两个网络请求，不适合下载or上传大文件，V2可以包含自定义库
        static function ks_send_post_request($strUrl, $arData=array()) {
            $strData = http_build_query($arData);
            $content = '';
            if (function_exists('curl_init')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $strUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_TIMEOUT, 300);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $strData);
                $content = curl_exec($ch);
                curl_close($ch);
            }
            if(!$content && ini_get('allow_url_fopen'))
            {
                $arOpts = array(
                    'http' => array(
                        'method' => "POST",
                        'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                                    "User-Agent: Mozilla/5.0\r\n",
                        'content' => $strData
                    )
                );
                $rContext = stream_context_create($arOpts);
                $content = file_get_contents($strUrl, 0, $rContext);
            }
            return $content;
        }

        static function ks_send_get_request($strUrl, $arData=array()) {
            $strData = http_build_query($arData);
            if ($strData) {
                $strUrl = $strUrl.'?'.$strData;
            }
            $content = '';
            if (function_exists('curl_init')) {
                $ch = curl_init($strUrl) ;
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1) ;
                $content = curl_exec($ch) ;
                curl_close($ch);
            }
            if (!$content && ini_get('allow_url_fopen')){
                $arOpts = array(
                    'http' => array(
                        'method' => "GET",
                        'header' => "User-Agent: Mozilla/5.0\r\n",
                    )
                );
                $rContext = stream_context_create($arOpts);
                $content = file_get_contents($strUrl, 0, $rContext);
            }
            return $content;
        }

        static function ks_log($strContent) {
            KsFunc::ks_file_put_contents(KsVar::$strLogPath, $strContent."\r\n", FILE_APPEND);
        }

        static function ks_progress($nProgress) {
            KsFunc::ks_file_put_contents(KsVar::$strProgressPath, $nProgress);
        }

        static function ks_md5_file($strFileName) {
            $strFileContent = KsFunc::ks_file_get_contents($strFileName);
            $strFileContent = str_replace(array("\n", "\r"), "", $strFileContent);
            return md5($strFileContent);
        }

        static function ks_md5_folder($dir) {
            if (!is_dir($dir)) {
                return false;
            }
            $filemd5s = array();
            $d = dir($dir);
            while (false !== ($entry = $d->read())) {
                if ($entry != '.' && $entry != '..' && $entry != '.svn') {
                    if (is_dir($dir.'/'.$entry)) {
                        $filemd5s[] = KsFunc::ks_md5_folder($dir.'/'.$entry);
                    } else {
                        $filemd5s[] = KsFunc::ks_md5_file($dir.'/'.$entry);
                    }
                }
            }
            $d->close();
            return md5(implode('', $filemd5s));
        }

        static function ks_get_app_abspath($nAppId) {
            return KsVar::$strKsafeAppDir.DIRECTORY_SEPARATOR.md5(KsVar::$strSiteId.$nAppId);
        }

        static function ks_create_tmp_file() {
            $strTmpFileName = KsVar::$strKsafeTmpDir.DIRECTORY_SEPARATOR.md5(mt_rand().time());
            while (KsFunc::ks_is_file($strTmpFileName)) {
                $strTmpFileName = KsVar::$strKsafeTmpDir.DIRECTORY_SEPARATOR.md5(mt_rand().time());
            }
            return $strTmpFileName;
        }

        static function ks_create_bak_file() {
            $strBakFileName = KsVar::$strKsafeBakDir.DIRECTORY_SEPARATOR.md5(mt_rand().time());
            while (KsFunc::ks_is_file($strBakFileName)) {
                $strBakFileName = KsVar::$strKsafeBakDir.DIRECTORY_SEPARATOR.md5(mt_rand().time());
            }
            return $strBakFileName;
        }
    }
}


if (!class_exists('KsVar')) {
    class KsVar {
        static public $strVersion           = KS_SAFE_VERSION;
        static public $strAppId             = KS_SAFE_APP_ID;
        static public $strSiteId            = KS_SAFE_SITE_ID;
        static public $strTaskId            = KS_SAFE_TASK_ID;
        static public $strSecretId          = KS_SAFE_SECRET_ID;

        static public $strDomain            = KS_SAFE_DOMAIN;

        static public $strGetAppUrl         = KS_SAFE_GET_APP_URL;
        static public $strChkUpdateUrl      = KS_SAFE_CHK_UPGRADE_URL;
        static public $strGetUpgradeUrl     = KS_SAFE_GET_UPGRADE_URL;
        static public $strGetConfigUrl      = KS_SAFE_GET_CONFIG_URL;
        static public $strRetBackupHashUrl  = KS_SAFE_RET_BACKUP_HASH_URL;
        static public $strRetBackupUrl      = KS_SAFE_RET_BACKUP_URL;
        static public $strRetResultUrl      = KS_SAFE_RET_RESULT_URL;
        static public $strRetLogUrl         = KS_SAFE_RET_LOG_URL;
        static public $strGetLibUrl         = KS_SAFE_GET_LIB_URL;

        static public $strRootDir           = KS_SAFE_ROOT_DIR;
        static public $strKsafeDir          = KS_SAFE_KSAFE_DIR;
        static public $strKsafeAppDir       = KS_SAFE_KSAFE_APP_DIR;
        static public $strKsafeTmpDir       = KS_SAFE_KSAFE_TMP_DIR;
        static public $strKsafeBakDir       = KS_SAFE_KSAFE_BAK_DIR;
        static public $strKsafeLibDir       = KS_SAFE_KSAFE_LIB_DIR;

        static public $strLogPath           = '';
        static public $strProgressPath      = '';
        static public $bRunning             = 0;

        static public $arAppResult          = array();
        static public $bAppStatus           = 0;
        static public $arAppConfig          = array();
        static public $arAppParam           = array();
        static public $strBackupMethod      = 'local';
        static public $arBackupFiles        = array();

        static public function chkSiteId() {
            if (empty($_POST['site_id'])) {
                die('KS_ANQUAN');
            } else if($_POST['site_id'] !== KsVar::$strSiteId) {
                die('{"status":"0","info":"site_id not match!"}');
            }
        }

        static public function init() {
            KsVar::chkSiteId();

            KsVar::$strTaskId               = isset($_POST['task_id'])? $_POST['task_id']: '';
            KsVar::$strAppId                = isset($_POST['app_id'])? $_POST['app_id']: '';
            KsVar::$strLogPath              = KsVar::$strKsafeTmpDir.DIRECTORY_SEPARATOR.md5(KsVar::$strTaskId);
            KsVar::$strProgressPath         = KsVar::$strKsafeTmpDir.DIRECTORY_SEPARATOR.md5(md5(KsVar::$strTaskId));

            $arDirList  = array(
                KsVar::$strKsafeDir,
                KsVar::$strKsafeTmpDir,
                KsVar::$strKsafeLibDir,
                KsVar::$strKsafeBakDir,
                KsVar::$strKsafeAppDir
            );

            foreach($arDirList as $strDirName) {
                if (!KsFunc::ks_is_dir($strDirName)) {
                    KsFunc::ks_mkdir($strDirName, 0755, 1);
                }
                if (KsFunc::ks_is_dir($strDirName)) {
                    KsFunc::ks_chmod($strDirName, 0755);
                    KsFunc::ks_touch($strDirName.DIRECTORY_SEPARATOR.'index.html');
                }
            }

            if (KsFunc::ks_is_file(KsVar::$strProgressPath)) {
                KsVar::$bRunning = 1;
            }

            KsFunc::ks_touch(KsVar::$strLogPath);
            KsFunc::ks_touch(KsVar::$strProgressPath);
        }
    }
}


if (!function_exists('catchFatalRuntimeError')) {
    /**
     * php 5.2.0以上可以使用register_shutdown_function 捕获致命异常
     * 在触发致命异常的时候，可以直接返回异常结果
     */
    function catchFatalRuntimeError() {
        if ($arError = error_get_last()) {
            if ($arError['type'] == E_ERROR) {
                $jAppResult = json_encode(array(
                    'info'      => '发生致命错误，请联系管理员:'.$arError['file']. $arError['line']. $arError['message'],
                    'extinfo'   => array(
                                    array('文件', '行号', '错误信息'),
                                    array('file', 'line', 'message'),
                                    array($arError['file'], $arError['line'], $arError['message'])
                                   )
                ));

                $strResultHash  = md5('0'.KsVar::$strSecretId.
                    KsVar::$strSiteId.KsVar::$strTaskId.$jAppResult);

                $jSendData = json_encode(array(
                    'status'        => intval(KsVar::$bAppStatus),
                    'result'        => $jAppResult,
                    'site_id'       => KsVar::$strSiteId,
                    'task_id'       => KsVar::$strTaskId,
                    'result_hash'   => $strResultHash
                ));

                KsFunc::ks_send_post_request(KsVar::$strRetResultUrl, array('result'=> $jSendData));
            }
        }
    }
    register_shutdown_function('catchFatalRuntimeError');
}


if (!class_exists('KsAnquan')) {
    class KsAnquan {

        function __construct() {
            KsVar::init();
        }

        function __destruct() {
            /**
             * 如果之前有同样的任务在跑，则不由这个任务删除文件
             */
            if (!KsVar::$bRunning) {
                if (KsFunc::ks_is_file(KsVar::$strLogPath)) {
                    KsFunc::ks_unlink(KsVar::$strLogPath);
                }
                if (KsFunc::ks_is_file(KsVar::$strProgressPath)) {
                    KsFunc::ks_unlink(KsVar::$strProgressPath);
                }
            }
        }

        public function upgrade() {
            // 触发升级操作
            $arResult = array(
                'status'=> 0 
            );

            if (!KsFunc::ks_is_writable(__FILE__)) {
                $arResult['info'] = __FILE__.' 文件不可写，请将文件权限改为777(linux平台)或去掉只读属性(windows平台)';
                return json_encode($arResult);
            }

            $jSendData = json_encode(array(
                'site_id'       => KsVar::$strSiteId,
                'name'          => $_POST['name'],
                'upgrade_hash'  => md5(KsVar::$strSiteId.KsVar::$strSecretId)
            ));

            $arContent = json_decode(
                KsFunc::ks_send_post_request(
                    KsVar::$strGetUpgradeUrl, array("result"=> $jSendData)),
                1
            );

            $strUpdateHash = md5(md5($arContent['code'].KsVar::$strSecretId));
            if ($strUpdateHash === $arContent['upgrade_hash']) {
                KsFunc::ks_file_put_contents(__FILE__, base64_decode($arContent['code']));
                return json_encode(array(
                    'status'    => 1,
                    'chksum'    => $arContent['md5sum'],
                    'version'   => $arContent['version']
                ));
            } else {
                $arResult['info'] = __FILE__.' 自动升级写文件失败，请手工上传到网站根目录下。';
                return json_encode($arResult);
            }
        }

        public function initEnv() {
            $arResult = array(
                'status'=> -1,
                'info'=> __FILE__.':'.__LINE__.':'.__METHOD__.'()'
            );
            $arLibFile = json_decode(KsFunc::ks_send_get_request(KsVar::$strGetLibUrl), 1);

            foreach ($arLibFile as $strFileName=> $strDownLoadUrl) {
                $strAppFileName = KsFunc::ks_get_app_abspath($strFileName);
                $bStatus = KsFunc::ks_file_put_contents(
                    $strAppFileName,
                    KsFunc::ks_send_get_request($strDownLoadUrl)
                );
                if (!$bStatus) {
                    return json_encode($arResult);
                }
            }

            return json_encode($arResult);
        }

        public function chkSelf() {
            $arResult = array(
                'status'=> -1,
            );

            if (!isset($_POST['md5'])) {
                $arResult['info'] = __FILE__.':'.__LINE__.':'.__METHOD__.'()';
                return json_encode($arResult);
            }

            $strFileMd5 = KsFunc::ks_md5_file(__FILE__);
            if ($strFileMd5 === $_POST['md5']) {
                $arResult['status'] = 1;
                return json_encode($arResult);
            } else {
                $arResult['info'] = __FILE__.':'.__LINE__.':'.__METHOD__.'():filemd5='.$strFileMd5.', expected md5='.$_POST['md5'];
                return json_encode($arResult);
            }
        }

        public function installApp() {
            $arResult = array(
                'status'=> -1,
            );

            $jSendData = json_encode(array(
                'app_id'    => KsVar::$strAppId,
                'site_id'   => KsVar::$strSiteId,
                'my_app'    => $_POST['my_app']
            ));

            $arContent = json_decode(KsFunc::ks_send_post_request(KsVar::$strGetAppUrl, array("result"=> $jSendData)),1);

            if (!$arContent) {
                $arResult['info'] = __FILE__.':'.__LINE__.':'.__METHOD__.'()';
                return json_encode($arResult);
            }

            $strAppFileName = KsFunc::ks_get_app_abspath(KsVar::$strAppId);
            $bStatus = KsFunc::ks_file_put_contents($strAppFileName, base64_decode($arContent['content']));

            if (!$bStatus) {
                $arResult['info'] = __FILE__.':'.__LINE__.':'.__METHOD__.'()';
                return json_encode($arResult);
            }

            $arResult['status'] = 1;
            return json_encode($arResult);
        }

        public function chkApp() {
            $arResult = array(
                'status'=> 0
            );

            $strAppFileName = KsFunc::ks_get_app_abspath(KsVar::$strAppId);

            if (!KsFunc::ks_file_exists($strAppFileName)) {
                $arResult['info'] = 'File not exists:'.$strAppFileName;
                return json_encode($arResult);
            } else if (KsFunc::ks_md5_file($strAppFileName) != $_POST['file_hash']) {
                $arResult['info'] = 'File hash not match current:'.KsFunc::ks_md5_file($strAppFileName).',expected:'.$_POST['file_hash'];
                return json_encode($arResult);
            } else {
                $arResult['status'] = 1;
                return json_encode($arResult);
            }
        }

        public function uninstallApp() {
            $arResult = array(
                'status'=> 0
            );

            $strAppFileName = KsFunc::ks_get_app_abspath(KsVar::$strAppId);

            if (!KsFunc::ks_file_exists($strAppFileName)) {
                $arResult['info'] = __FILE__.':'.__LINE__.':'.__METHOD__.'()';
                return json_encode($arResult);
            }
            if (KsFunc::ks_unlink($strAppFileName)) {
                if ($_POST['name']=='filter') {
                    KsFunc::ks_unlink(KS_SAFE_KSAFE_APP_DIR."/ksafe_filter.php");
                }
                $arResult['info'] = 'success';
                $arResult['status'] = 1;
                return json_encode($arResult);
            }else{
                $arResult['info'] = __FILE__.':'.__LINE__.':'.__METHOD__.'()';
                return json_encode($arResult);
            }

        }

        public function chkProgress() {
            $arResult = array(
                'status'    => 0,
                'progress'  => 0
            );

            if (KsVar::$bRunning) {
                $strProgress = KsFunc::ks_file_get_contents(KsVar::$strProgressPath);
                $ft = filemtime(KsVar::$strProgressPath);
                if(time()-$ft>60) {
                    KsVar::$bRunning = 0;
                    $arResult['status'] = -1;
                    $arResult['progress'] = intval($strProgress);
                } else {
                    $arResult['status'] = 1;
                    $arResult['progress'] = intval($strProgress);
                }
                return json_encode($arResult);
            } else {
                $arResult['status'] = 0;
                $arResult['progress'] = 100;
                return json_encode($arResult);
            }
        }

        public function chkEnv() {
            $this->_chkEnv();
            return json_encode(KsVar::$arAppResult);
        }

        private function _chkEnv() {
            ks_debug($_POST);
            KsFunc::ks_log("[*] 开始检测环境");
            $arResult = array(
                'php_version'       => PHP_VERSION,
                'file_writable'     => '1',
                'dir_writable'      => '1',
                'network_status'    => '1',
                'set_time_limit'    => '1',
                'ignore_user_abort' => '1',
                'safe_mode'         => '0',
                'status'            => '1',
                'info'              => '',
              );

            if (!KsFunc::ks_is_writable(__FILE__)) {
                chmod(__FILE__,777);
                $arResult['file_writable'] = (string)KsFunc::ks_is_writable(__FILE__);
              }
            if (!(ini_get('allow_url_fopen') || function_exists('curl_init'))) {
                $arResult['network_status'] = '0';
                $arResult['status'] = 0;
              }
            $strDisabledFuncs = ini_get('disable_functions');
            if(strpos($strDisabledFuncs,'set_time_limit')!==false) {
                $arResult['set_time_limit'] = '0';
              }
            if(strpos($strDisabledFuncs,'ignore_user_abort')!==false) {
                $arResult['ignore_user_abort'] = '0';
              }

            $arResult['safe_mode'] = (int)ini_get('safe_mode');

            if (!KsFunc::ks_is_dir(KsVar::$strKsafeDir) || !KsFunc::ks_is_writable(KsVar::$strKsafeDir) ||
                !KsFunc::ks_is_dir(KsVar::$strKsafeTmpDir) || !KsFunc::ks_is_writable(KsVar::$strKsafeTmpDir) ||
                !KsFunc::ks_is_dir(KsVar::$strKsafeLibDir) || !KsFunc::ks_is_writable(KsVar::$strKsafeLibDir) ||
                !KsFunc::ks_is_dir(KsVar::$strKsafeBakDir) || !KsFunc::ks_is_writable(KsVar::$strKsafeBakDir) ||
                !KsFunc::ks_is_dir(KsVar::$strKsafeAppDir) || !KsFunc::ks_is_writable(KsVar::$strKsafeAppDir)) {
                $arResult['dir_writable'] = '0';
                $arResult['status'] = 0;
              }
              /*
            if (!$arResult['file_writable']) {
                $arResult['info'] = "ks_anquan.php无法写入，请您修改其权限";
              }
              */

            if (version_compare($arResult['php_version'],'5.1','<')) {
                $arResult['status'] = '0';
                $arResult['info'] .= "php版本({$arResult['php_version']})过低,建议升级到php5.2以上;";
              }
            if (!$arResult['dir_writable']) {
                $arResult['info'] .= "无法在根目录下创建ksafe目录，请您手动创建ksafe目录并设置权限为可读可写;";
              }
            if (!$arResult['network_status']) {
                $arResult['info'] .= "无法使用网络库，请您修改配置文件，启用curl扩展或将allow_url_fopen开启;";
              }
            if (!$arResult['set_time_limit']) {
                $arResult['info'] .= "set_time_limit函数被禁用，可能导致某些App超时出错，请您修改php.ini配置文件，把set_time_limit函数从disabled_functions中移除;";
              }
            if (!$arResult['ignore_user_abort']) {
                $arResult['info'] .= "ignore_user_abort函数被禁用，可能导致某些App超时出错，请您修改php.ini配置文件，把ignore_user_abort函数从disabled_functions中移除;";
              }
            if ($arResult['safe_mode']) {
                $arResult['info'] .= "safe_mode被启用，可能会导致某些App执行出错，请您修改php.ini配置文件，把safe_mode设置为Off;";
              }
            KsVar::$bAppStatus = $arResult['status'];
            KsVar::$arAppResult = $arResult;
            KsFunc::ks_log("[*] 检测环境结束");
            return $arResult['status'];
         }

        public function start() {
              //下面6个函数，只要任意一个出错，就不再往下执行，但是最后的
            while(true){
                if(!$this->_chkEnv()){
                    break;
                }
                if(!$this->_getConfig()){
                    break;
                }
                if(!$this->_parseConfig()){
                    break;
                }
                if(!$this->_execApp()){
                    break;
                }
                if(!$this->_uploadBackupHash()){
                    break;
                }
                if(!$this->_uploadBackupFile()){
                    break;
                }
                break;
            }
            $this->_retResult();
            $this->_retLog();
            return 'start';
        }

       private function _getConfig() {
            KsFunc::ks_log("[*] 正在下载配置文件");

            $jSendData = json_encode(array(
                'site_id' => KsVar::$strSiteId,
                'task_id' => KsVar::$strTaskId
            ));

            $arContent = json_decode(
                KsFunc::ks_send_post_request(
                    KsVar::$strGetConfigUrl, array("result"=> $jSendData)
                ),
                1
            );

            KsFunc::ks_log("[*] 下载配置文件结束");
            if ($arContent) {
                KsVar::$arAppConfig = $arContent;
                return 1;
            } else {
                KsVar::$bAppStatus  = 0;
                KsVar::$arAppResult = array('info'=> '下载配置文件出错，配置文件内容为空');
                return 0;
            }
        }

        private function _parseConfig() {
            KsFunc::ks_log("[*] 正在处理配置文件");
            $arConfig          = KsVar::$arAppConfig;
            KsVar::$bAppStatus = 0;
            KsVar::$strAppId   = KsVar::$arAppConfig['app_id'];
            $strAppFileName    = KsFunc::ks_get_app_abspath(KsVar::$strAppId);

            if (!KsFunc::ks_file_exists($strAppFileName)) {
                KsFunc::ks_log("[-]  插件不存在，请下载该插件");
                KsVar::$arAppResult = array("info"=> "插件不存在，请下载该插件");
                return 0;
            }

            if (md5(md5($arConfig['param_code']).KsVar::$strSecretId) != $arConfig['param_hash']) {
                KsFunc::ks_log("[-] 更新的校验值不正确");
                KsFunc::ks_log("[-] 本地校验hash:  ".md5(md5($arConfig['param_code']).KsVar::$strSecretId));
                KsFunc::ks_log("[-] 远程校验hash:  ".$arConfig['param_hash']);
                KsVar::$arAppResult = array("info"=> "插件参数代码的校验代码不正确，请您重新下发任务");
                return 0;
            }

            KsVar::$arAppParam = json_decode(base64_decode($arConfig['param_code']), 1);
            KsFunc::ks_log("[*] 处理配置文件结束");
            return 1;
        }

        private function _execApp() {
            KsFunc::ks_log("[*] 正在执行插件");
            $strAppFileName = KsFunc::ks_get_app_abspath(KsVar::$strAppId);
            KsFunc::ks_file_exists($strAppFileName) && KsFunc::ks_include_once($strAppFileName);
            $app = new App();
            $app->run();

            if (isset(KsVar::$arAppParam['backup_method']) &&
                KsVar::$arAppParam['backup_method'] == 'server') {
                KsVar::$strBackupMethod = 'server';
            } else {
                KsVar::$strBackupMethod = 'local';
            }
            KsFunc::ks_log("[*] 插件执行结束");
            return 1;
        }

        private function _uploadBackupHash() {
            KsFunc::ks_log("[*] 开始上传备份文件哈希");

            $strSiteId              = KsVar::$strSiteId;
            $arBackupFiles          = KsVar::$arBackupFiles;
            $strRetBackupHashUrl    = KsVar::$strRetBackupHashUrl;

            foreach ($arBackupFiles as $nFileId=> $arFileInfo) {

                if (is_dir($arFileInfo['backup_path'])) {
                    $strFileHash = KsFunc::ks_md5_folder($arFileInfo['backup_path']);
                }else{
                    $strFileHash = KsFunc::ks_md5_file($arFileInfo['backup_path']);
                }

                $jSendData = json_encode(array(
                    'site_id'       => $strSiteId,
                    'file_hash'     => $strFileHash
                ));

                $arContent = json_decode(
                    KsFunc::ks_send_post_request(
                      $strRetBackupHashUrl, array("result"=> $jSendData)
                    ),
                    1
                );

                if ($arContent) {
                    $arContent = array('status'=> 0);
                }

                $arFileInfo['file_hash']        = $strFileHash;
                $arFileInfo['backup_exists']    = $arContent['status'];
                KsVar::$arBackupFiles[$nFileId] = $arFileInfo;
            }

            KsFunc::ks_log("[*] 上传备份文件哈希结束");
            return 1;
        }

        private function _uploadBackupFile() {
            KsFunc::ks_log("[*] 开始上传备份文件");
            foreach (KsVar::$arBackupFiles as $arFileInfo) {
                if (!(KsFunc::ks_is_file($arFileInfo['backup_path']) ||
                    KsFunc::ks_is_dir($arFileInfo['backup_path']))) {
                    continue;
                }

                if (KsVar::$strBackupMethod == 'server' && $arFileInfo['backup_exists'] == '0' &&
                    !KsFunc::ks_is_dir($arFileInfo['backup_path'])) {
                    $strFileContent = base64_encode(KsFunc::ks_file_get_contents($arFileInfo['backup_path']));
                } else {
                    $strFileContent = '';
                }

                $strOriginPath  = str_replace(KsVar::$strRootDir, '', $arFileInfo['origin_path']);
                $strBackupPath  = str_replace(KsVar::$strRootDir, '', $arFileInfo['backup_path']);
                $strFileHash    = $arFileInfo['file_hash'];
                $strBackupDesc  = isset($arFileInfo['backup_desc'])? $arFileInfo['backup_desc']: '';
                $strBackupHash  = md5(KsVar::$strSiteId.KsVar::$strSecretId.$strOriginPath.
                    $strBackupPath.$strFileContent.$strFileHash.KsVar::$strTaskId
                );

                $jSendData = json_encode(array(
                    'site_id'       => KsVar::$strSiteId,
                    'task_id'       => KsVar::$strTaskId,
                    'backup_hash'   => $strBackupHash,
                    'file_content'  => $strFileContent,
                    'file_hash'     => $strFileHash,
                    'backup_path'   => $strBackupPath,
                    'origin_path'   => $strOriginPath,
                    'backup_desc'   => $strBackupDesc
                ));

                KsFunc::ks_send_post_request(KsVar::$strRetBackupUrl, array('result'=> $jSendData));
            }

            KsFunc::ks_log("[*] 上传备份文件结束");
            return 1;
        }

        private function _retResult() {
            KsFunc::ks_log("[*] 开始上传APP执行结果");

            $jAppResult     = json_encode(KsVar::$arAppResult);
            $strResultHash  = md5(strval(intval(KsVar::$bAppStatus)).KsVar::$strSecretId.
                KsVar::$strSiteId.KsVar::$strTaskId.$jAppResult);

            $jSendData = json_encode(array(
                'status'        => intval(KsVar::$bAppStatus),
                'result'        => $jAppResult,
                'site_id'       => KsVar::$strSiteId,
                'task_id'       => KsVar::$strTaskId,
                'result_hash'   => $strResultHash
            ));

            KsFunc::ks_send_post_request(KsVar::$strRetResultUrl, array('result'=> $jSendData));
            KsFunc::ks_log("[*] 结束上传插件执行结果");
        }

        private function _retLog() {
            $strLogContent  = base64_encode(KsFunc::ks_file_get_contents(KsVar::$strLogPath));
            $strLogHash     = md5(KsVar::$strSiteId.KsVar::$strTaskId.$strLogContent.KsVar::$strSecretId);

            $jSendData = json_encode(array(
                'site_id'   => KsVar::$strSiteId,
                'task_id'   => KsVar::$strTaskId,
                'log'       => $strLogContent,
                'log_hash'  => $strLogHash,
            ));

            KsFunc::ks_send_post_request(KsVar::$strRetLogUrl, array('result'=> $jSendData));
        }
    }
}
function ks_dump(){
    ob_start();
    var_dump(func_get_args());
    return ob_get_clean();
}
function ks_debug($v,$msg='')
{
    if(!trim($_POST['ks_debug']))return;
    $ks_debug_file = KS_SAFE_KSAFE_DIR.DIRECTORY_SEPARATOR.'ks_debug.txt';
    file_put_contents($ks_debug_file, date("Y-m-d H:i:s").":ks_debug={$_POST['ks_debug']};msg=$msg\n".ks_dump($v)."\n", FILE_APPEND) or KsFunc::ks_unlink($ks_debug_file);
}
$ks = new KsAnquan();
if (isset($_POST['act'])) {
    switch ($_POST['act']) {
        case 'upgrade':
            $ret = $ks->upgrade();
            break;
        case 'init_env':
            $ret = $ks->initEnv();
            break;
        case 'chk_md5':
            $ret = $ks->chkSelf();
            break;
        case 'get_app':
            $ret = $ks->installApp();
            break;
        case 'del_app':
            $ret = $ks->uninstallApp();
            break;
        case 'chk_app':
            $ret = $ks->chkApp();
            break;
        case 'chk_progress':
            $ret = $ks->chkProgress();
            break;
        case 'chk_env':
            $ret = $ks->chkEnv();
            break;
    }
} else if (isset($_POST['task_id'])){
    $ret = $ks->start();
}
$errors = ob_get_clean();
ks_debug($errors);
die($ret);

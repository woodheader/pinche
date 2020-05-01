<?php
class help {

    public static function isFromWechat() {
        if(strpos(help::getUserAgent(), 'MicroMessenger') !== false){
            return true;
        }
        return false;
    }

    public static function getAuthorization() {
        return $_SERVER['HTTP_AUTHORIZATION'];
    }

    public static function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public static function generateToken($userAgent, $ip) {
        return md5($userAgent . '-' . $ip . '-wechat-catch-message-!@#123');
    }

    static function addslashes_deep($value)
    {
        if (empty($value))
        {
            return $value;
        }
        elseif(is_array($value) && (array_key_exists('pic1',$value) || array_key_exists('pic2',$value)))
        {
            return $value;
        }    
        else
        {
            if (!get_magic_quotes_gpc())
            {
            $value=is_array($value) ? array_map("help::addslashes_deep", $value) : help::mystrip_tags(addslashes($value));
            }
            else
            {
            $value=is_array($value) ? array_map("help::addslashes_deep", $value) : help::mystrip_tags($value);
            }
            return $value;
        }
    }
    /**
     * 防xss函数，在不影响cpu占用率情况下可以使用
     */
    // static function remove_xss($string)
    // {  
    //     require_once(QISHI_ROOT_PATH.'include/library/HTMLPurifier.auto.php');
    //     $config = HTMLPurifier_Config::createDefault();
    //     $config->set('HTML.AllowedElements', array('div'=>true, 'table'=>true, 'tr'=>true, 'td'=>true, 'br'=>true,"src"=>true,"strong"=>true,"em"=>true,"img"=>true,"p"=>true,"span"=>true));
    //     $config->set('HTML.Doctype', 'XHTML 1.0 Transitional'); 
    //     if(!isset($_SERVER["HTTP_X_REQUESTED_WITH"]) || strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])!="xmlhttprequest"){
    //         $config->set('Core.Encoding', QISHI_CHARSET);
    //     }
    //     $purifier = new HTMLPurifier($config);
    //     return $purifier->purify($string);
    // }
    static function remove_xss($string) { 
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

        $parm1 = Array('javascript', 'union','vbscript', 'expression', 'applet', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'base');

        $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload','href','action','location','background','src','poster');
        
        $parm3 = Array('alert','sleep','load_file','confirm','prompt','benchmark','select','update','insert','delete','alter','drop','truncate','script','eval','outfile','dumpfile');

        $parm = array_merge($parm1, $parm2, $parm3); 

        for ($i = 0; $i < sizeof($parm); $i++) { 
            $pattern = '/'; 
            for ($j = 0; $j < strlen($parm[$i]); $j++) { 
                if ($j > 0) { 
                    $pattern .= '('; 
                    $pattern .= '(&#[x|X]0([9][a][b]);?)?'; 
                    $pattern .= '|(&#0([9][10][13]);?)?'; 
                    $pattern .= ')?'; 
                }
                $pattern .= $parm[$i][$j]; 
            }
            $pattern .= '/i';
            $string = preg_replace($pattern, '****', $string); 
        }
        return $string;
    }
    static function mystrip_tags($string)
    {
        $string =  help::new_html_special_chars($string);
        $string =  help::remove_xss($string);
        return $string;
    }
    static function new_html_special_chars($string) {
        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;','&#'), array('&', '"', '<', '>','***'), $string);
        return $string;
    }
    // 实体出库
    static function htmlspecialchars_($value)
    {
        if (empty($value))
        {
            return $value;
        }
        else
        {
            if(is_array($value)){
                foreach ($value as $k => $v) {
                    $value[$k] = self::htmlspecialchars_($v);
                }
            }else{
                $value = htmlspecialchars($value,ENT_QUOTES, 'utf-8');
            }
            return $value;
        }
    }

    static function getip()
    {
        if (getenv('HTTP_CLIENT_IP') and strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')) {
            $onlineip=getenv('HTTP_CLIENT_IP');
        }elseif (getenv('HTTP_X_FORWARDED_FOR') and strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')) {
            $onlineip=getenv('HTTP_X_FORWARDED_FOR');
        }elseif (getenv('REMOTE_ADDR') and strcasecmp(getenv('REMOTE_ADDR'),'unknown')) {
            $onlineip=getenv('REMOTE_ADDR');
        }elseif (isset($_SERVER['REMOTE_ADDR']) and $_SERVER['REMOTE_ADDR'] and strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')) {
            $onlineip=$_SERVER['REMOTE_ADDR'];
        } else{
            $onlineip = 'unknown';
        }
        preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/",$onlineip,$match);
        return $onlineip = $match[0] ? $match[0] : 'unknown';
    }

    public static function getIpAddr() {
        if (isset($_SERVER)){
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")){
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }

    static function request_url()
    {
        if (isset($_SERVER['REQUEST_URI']))
        {
            $url = $_SERVER['REQUEST_URI'];
        }
        else
        {
            if (isset($_SERVER['argv']))
            {
                $url = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
            }
            else
            {
                $url = $_SERVER['PHP_SELF'] .'?'.$_SERVER['QUERY_STRING'];
            }
        }
        return urlencode($url);
    }

    //sql 过滤
    static function CheckSql($db_string,$querytype='select')
    {
        global $QS_pwdhash;
        $clean = '';
        $error='';
        $old_pos = 0;
        $pos = -1;
        $log_file = '/tmp/'.md5($QS_pwdhash).'_safe.txt';
        $userIP = self::getip();
        $getUrl = self::request_url();
        $time = date('Y-m-d H:i:s');
        if($querytype=='select')
        {
            $notallow1 = "[^0-9a-z@\._-]{1,}(sleep|benchmark|load_file|outfile)[^0-9a-z@\.-]{1,}";
            if(preg_match("/".$notallow1."/i", $db_string))
            {
                fputs(fopen($log_file,'a+'),"$userIP||$time\r\n$getUrl\r\n$db_string\r\nSelectBreak\r\n===========\r\n");
                exit("您输入的内容不符合要求请正确输入！");
            }
        }
        //完整的SQL检查
        while (TRUE)
        {
            $pos = strpos($db_string, '\'', $pos + 1);
            if ($pos === FALSE)
            {
                break;
            }
            $clean .= substr($db_string, $old_pos, $pos - $old_pos);
            while (TRUE)
            {
                $pos1 = strpos($db_string, '\'', $pos + 1);
                $pos2 = strpos($db_string, '\\', $pos + 1);
                if ($pos1 === FALSE)
                {
                    break;
                }
                elseif ($pos2 == FALSE || $pos2 > $pos1)
                {
                    $pos = $pos1;
                    break;
                }
                $pos = $pos2 + 1;
            }
            $clean .= '$s$';
            $old_pos = $pos + 1;
        }
        $clean .= substr($db_string, $old_pos);
        $clean = trim(strtolower(preg_replace(array('~\s+~s' ), array(' '), $clean)));
        if (strpos($clean, '@') !== FALSE  OR strpos($clean,'char(')!== FALSE OR strpos($clean,'"')!== FALSE 
        OR strpos($clean,'$s$$s$')!== FALSE)
        {
            $fail = TRUE;
            if(preg_match("#^create table#i",$clean)) $fail = FALSE;
            $error="unusual character";
        }
        elseif (strpos($clean, '/*') > 2 || strpos($clean, '--') !== FALSE || strpos($clean, '#') !== FALSE)
        {
            $fail = TRUE;
            $error="comment detect";
        }
        elseif (strpos($clean, 'sleep') !== FALSE && preg_match('~(^|[^a-z])sleep($|[^[a-z])~is', $clean) != 0)
        {
            $fail = TRUE;
            $error="slown down detect";
        }
        elseif (strpos($clean, 'benchmark') !== FALSE && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~is', $clean) != 0)
        {
            $fail = TRUE;
            $error="slown down detect";
        }
        elseif (strpos($clean, 'load_file') !== FALSE && preg_match('~(^|[^a-z])load_file($|[^[a-z])~is', $clean) != 0)
        {
            $fail = TRUE;
            $error="file fun detect";
        }
        elseif (strpos($clean, 'into outfile') !== FALSE && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~is', $clean) != 0)
        {
            $fail = TRUE;
            $error="file fun detect";
        }
        if (!empty($fail))
        {
            fputs(fopen($log_file,'a+'),"$userIP||$time\r\n$getUrl\r\n$db_string\r\n$error\r\n===========\r\n");
            exit("您输入的内容不符合要求请正确输入！");
        }
        else
        {
            return $db_string;
        }
    }

    public static function replaceWithStar($oldStr,$replaceStr,$start,$length) {
        if (empty($oldStr)) {
            return '';
        }
        return substr_replace($oldStr,$replaceStr,$start,$length);
    }

    public static function getOsInfo() {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $OS = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/win/i', $OS)) {
                $OS = 'Windows';
            } elseif (preg_match('/mac/i', $OS)) {
                $OS = 'MAC';
            } elseif (preg_match('/linux/i', $OS)) {
                $OS = 'Linux';
            } elseif (preg_match('/unix/i', $OS)) {
                $OS = 'Unix';
            } elseif (preg_match('/bsd/i', $OS)) {
                $OS = 'BSD';
            } else {
                $OS = 'Other';
            }
            return $OS;
        } else {
            return 'error';
        }
    }


    public static function getBrowserInfo() {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $br = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/MSIE/i', $br)) {
                $br = 'MSIE';
            } elseif (preg_match('/Firefox/i', $br)) {
                $br = 'Firefox';
            } elseif (preg_match('/Chrome/i', $br)) {
                $br = 'Chrome';
            } elseif (preg_match('/Safari/i', $br)) {
                $br = 'Safari';
            } elseif (preg_match('/Opera/i', $br)) {
                $br = 'Opera';
            } else {
                $br = 'Other';
            }
            return $br;
        } else {
            return "error";
        }
    }

    public static function getBrowserLang() {
        if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            $lang = substr($lang, 0, 5);
            if (preg_match("/zh-cn/i", $lang)) {
                $lang = "简体中文";
            } elseif (preg_match("/zh/i", $lang)) {
                $lang = "繁体中文";
            } else {
                $lang = "English";
            }
            return $lang;
        } else {
            return "error";
        }
    }

    public static function getLocation($ip = '') {
        empty($ip) && $ip = self::getIpAddr();
        if ($ip == "127.0.0.1") return "本机地址";
        $api = "http://apis.map.qq.com/ws/location/v1/ip?ip=$ip&key=3IPBZ-7BSCP-EFIDB-LWNOK-FINDQ-HRFCT";   //请求腾讯ip地址库
        $json = @file_get_contents($api);
        $arr = json_decode($json, true);
        if ($arr['status'] != 0) {
            return '';
        }
        $country = $arr['result']['ad_info']['nation'];
        $province = $arr['result']['ad_info']['province'];
        $city = $arr['result']['ad_info']['city'];
        $district = $arr['result']['ad_info']['district'];
        $adcode = $arr['result']['ad_info']['adcode'];
        if ((string)$country == '中国') {
            if ((string)($province) != (string)$city) {
                $_location = $province .'-'. $city .'-'. $district . ','. $adcode;
            } else {
                $_location = $country .'-'. $city .'-'. $district . ','. $adcode;
            }
        } else {
            $_location = $country;
        }
        return $_location;
    }


    /**
     * 获取用户设备信息
     */
    public static function getEqu() {
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if (stristr($agent, 'iPad')) {
            $fb_fs = "iPad";
        } else if (preg_match('/Android (([0-9_.]{1,3})+)/i', $agent, $version)) {
            $fb_fs = "手机(Android " . $version[1] . ")";
        } else if (stristr($agent, 'Linux')) {
            $fb_fs = "电脑(Linux)";
        } else if (preg_match('/iPhone OS (([0-9_.]{1,3})+)/i', $agent, $version)) {
            $fb_fs = "手机(iPhone " . $version[1] . ")";
        } else if (preg_match('/Mac OS X (([0-9_.]{1,5})+)/i', $agent, $version)) {
            $fb_fs = "电脑(OS X " . $version[1] . ")";
        } else if (preg_match('/unix/i', $agent)) {
            $fb_fs = "Unix";
        } else if (preg_match('/windows/i', $agent)) {
            $fb_fs = "电脑(Windows)";
        } else {
            $fb_fs = "Unknown";
        }
        return $fb_fs;
    }
}
?>
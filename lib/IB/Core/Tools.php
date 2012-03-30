<?php
Class IB_Core_Tools
{
    static function isUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    static function isEmail($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL) && $ext = strrchr($email, '.'))
        {
            if(isset($ext{1}) && !isset($ext{7}))
            {
                $ext2 = preg_replace("[^A-Za-z]",'',$ext);
                
                return isset($ext2{1}) && !isset($ext2{7}) && preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email);
            }
        }
        return false;
    }

    static function getAge($date)
    {
        $bd=strtotime($date);
        if($bd<strtotime('now'))
        {
            return date('Y')-date('Y',$bd)-(date('n')<(ltrim(date('m',$bd),'0')+(date('j')<ltrim(date('d',$bd),'0'))));
        }
        return 0;
    }

    static function getIp()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){$ip=$_SERVER['HTTP_CLIENT_IP'];}
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];}
        else{$ip=$_SERVER['REMOTE_ADDR'];}
        return $ip;
    }


    static function c2sdecrypt($s,$k)
    {
        $s = urldecode($s);
        $k = str_split(str_pad('', strlen($s), $k));
        $sa = str_split($s);
        foreach($sa as $i=>$v){
          $t = ord($v)-ord($k[$i]);
          $sa[$i] = chr( $t < 0 ?($t+256):$t);
        }
        return join('', $sa);
    }
/*

    static function _setcookie($name,$content,$time=false,$path='/')
    {
        if(!$time) $time=time()+1000000;
        setcookie($name,$content,$time,$path);
    }
    static function _getcookie($name)
    {
        if(!empty($_COOKIE[$name])) return $_COOKIE[$name];
        return false;
    }
    function fbLink($url)
    {
        return 'http://www.facebook.com/sharer.php?u='.URL.$url;
    }
*/
}
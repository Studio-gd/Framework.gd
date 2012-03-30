<?php
Class Clean
{
    static function email($email)
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL); 
    }
    static function url($url)
    {
        return filter_var($url, FILTER_SANITIZE_URL); 
    }
    static function string($str)
    {
        return filter_var($str, FILTER_SANITIZE_STRING);
    }
    static function username($username)
    {
        return IB_Core_TextFormat::getInstance($username)->replaceAccents()->alphanumeric()->getText(); 
    }
    static function convertLineBreak($str, $to = '<br/>')
    {
        return preg_replace("/\015\012|\015|\012/",$to,$str);
    }
    static function revertLineBreak($str)
    {
        return str_replace("<br/>","\n",$str);
    }
    static function strJs($txt)
    {
        return str_replace(array("\n","\t","'","(",")"),array('','','&#39;','&#40;','&#41;'),$txt);
    }
    static function xmlentities($str)
    {
       return strtr($str,array("&"=>"&amp;","'"=>"&apos;","<"=>"&lt;", ">"=>"&gt;", "\""=>"&quot;"));
    }
    static function htmlValue($s){return htmlentities($s,ENT_COMPAT,"UTF-8");}
}
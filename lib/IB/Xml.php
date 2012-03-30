<?php
class IB_Xml extends IB
{
    static function build(){return new IB_Xml();}
    function getRender()
    {
        $r = explode('/',self::$name);

        $cn = '';
        
        foreach($r as $v)
        {
           $cn.= ucfirst($v).'_';
        }
        
        $cn = trim($cn,'_');
        
        $xml = call_user_func(array('Xml_'.$cn,'getInstance'));
        
        return $xml->preRender();
    }
}
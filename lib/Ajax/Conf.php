<?php
class Ajax_Conf
{
    static function get()
    {
        echo IB_UserConfig::getInstance()->getValue(reader(),'playmode');
        echo '||';
        echo IB_UserConfig::getInstance()->getValue(reader(),'panel');
    }
}
<?php
class Ajax_Avatar
{
    static function delete()
    {
        IB_Avatar::getInstance()->remove(intval($_POST['id']), Clean::string($_POST['object']));
        
        echo __('Avatar is deleted');
    }
}
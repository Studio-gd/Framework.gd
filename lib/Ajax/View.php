<?php
class Ajax_View
{
    static function get()
    {
        if(isset($_POST['id']))
        {
            IB::getInstance()->set('id',intval($_POST['id']));
        }
        echo view(preg_replace("[^a-z_/]",'',$_POST['name']));
    }
}
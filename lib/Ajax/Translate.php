<?php class Ajax_Translate
{
    static function it()
    {
        echo __($_POST['s']);
    }
   static function js()
   {
       echo __('close').'||'.__('Loading');
   }
}
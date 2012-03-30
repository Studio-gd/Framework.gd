<?php Class Controller_Contact extends IB_Controller
{
    static function i(){return new Controller_Contact();}
    function index($arg = false)
    {
        $this->set('title',__('Contact'))->view('contact');
    }
}
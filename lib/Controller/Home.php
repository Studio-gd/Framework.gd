<?php Class Controller_Home extends IB_Controller
{
    static function i(){return new Controller_Home();}
    function index($arg = false)
    {
        $this->set('cached',3000)->set('title',__('Home'))->view('home');
    }
}
<?php Class IB_Controller extends IB
{
    static $controller = false;
    static function control($arg = false)
    {
        if(!self::$controller)
        {
            $names = array('Controller_'.ucfirst(self::get('p')), 'i');

            if(is_file(PATH.'lib/Controller/'.ucfirst(self::get('p')).'.php')) # is_callable($names, false, $callableName)
            {
                self::$controller = call_user_func($names);
            }
            else
            {
                #self::set('cancelJsCache',1); // to avoid a js cache of a redirection
                return self::homepage(true); # -> redirect to homepage
            }
        }
        
        if($arg) self::$controller->index($arg);
        return self::$controller;
    }
    static function homepage($returnObject = false)
    {
        $controller = call_user_func(array('Controller_'.ucfirst(HOMEPAGE), 'i'));
        
        if($returnObject) return self::$controller = $controller;
        
        $controller->index();
    }

    function subController($arg)
    {
        $m = empty($arg) ? false : $arg[0];

        $C = call_user_func(array('Controller_'.ucfirst($this->get('p')).'_'.ucfirst($this->get('sp')), 'i'));

        if($m && method_exists($C,$m))
        {
            $this->set('param',$m.'/');
            array_shift($arg);
            $C->{$m}($arg);
        }
        else
        {
            $C->index($arg);
        }
        
    }
    /*
    static function isHome()
    {
        return self::isPageController(HOMEPAGE);
    }
    static function isPageController($page) // test if the current controller is the controller of current page
    {
        #echo get_class(self::$controller);
        #echo '<br>';
        #echo 'Controller_'.ucfirst($page);
        return get_class(self::$controller) === 'Controller_'.ucfirst($page);
    }
    */
}
<?php Class Controller_Admin_Dev extends IB_Controller
{
    static function i(){return new Controller_Admin_Dev();}
    function index($arg)
    {
        return $this->panel();
    }

    function panel()
    {
        if(!isAdmin()) return $this->homepage();

        $this->set('title','Admin Panel')
             ->view('admin/menu')
             ->view('admin/dev/panel');
    }
    function cacheStats()
    {
        if(!isAdmin()) return $this->homepage();

        $this->set('title','<a href="/admin/dev/">Admin Dev</a>'.TITLE_SEPARATOR.' cache Stats')
             ->view('admin/menu')
             ->view('admin/dev/cacheStats');
    }
    function logs()
    {
        if(!isAdmin()) return $this->homepage();

        $this->set('title','<a href="/admin/dev/">Admin Dev</a>'.TITLE_SEPARATOR.'<a href="/admin/logs">Error Logs</a>')
             ->view('admin/menu')
             ->view('admin/dev/logs');
    }
    function scaffold()
    {
        if(!isAdmin()) return $this->homepage();

        $this->set('title','<a href="/admin/dev/">Admin Dev</a>'.TITLE_SEPARATOR.'Scaffold')
             ->view('admin/menu')
             ->view('admin/dev/scaffold');
    }
    function simplepage()
    {
        if(!isAdmin()) return $this->homepage();

        $this->set('title','<a href="/admin/dev/">Admin Dev</a>'.TITLE_SEPARATOR.'Simple page')
             ->view('admin/menu')
             ->view('admin/dev/simplepage');
    }
    function stats()
    {
        if(!isAdmin()) return $this->homepage();

        $this->set('title','<a href="/admin/dev/">Admin Dev</a>'.TITLE_SEPARATOR.__('Statistics'))
             ->view('admin/menu')
             ->view('admin/dev/stats');
    }

    function translate($arg)
    {
        $sp = empty($arg[0]) ? false : str($arg[0]);
        
        $lang_translate = '';
        
        if($sp==='help')
        {
            $this->set('title','<a href="/admin/dev/translate/">'.__('Translate').'</a></b><b>'.TITLE_SEPARATOR.'</b><b>'.__('Help').'</b>')
                 ->view('admin/dev/translate/menu');
            $this->view('admin/dev/translate/help');
        }
        elseif(isAdmin() || IB_Lang::getInstance()->isTranslator(reader(), $lang_translate))
        {
            $lang_translate = $sp;

            $nbPage = false;
            
            if($sp && !empty($arg[1]))
            {
                if($arg[1]==='search')
                {
                    $search = $arg[2];

                    if(!empty($arg[3]) && $arg[3] == 'p') $nbPage= (int) $arg[4];
                }
                elseif($arg[1]==='new' || $arg[1]==='all')
                {
                    $f = $arg[1];

                    if(!empty($arg[2]) && $arg[2] == 'p') $nbPage= (int) $arg[3];
                }
                elseif($arg[1]==='p')
                {
                    $nbPage= (int) $arg[2];
                }
                else
                {
                    $nbPage= (int) $arg[1];
                }
            }
            
            $this->set('nbPage',$nbPage);
            
            if(empty($f)) $f = 'all';
            
            $this->add('param',$lang_translate.'/'.$f.'/')
                 ->set('lang_translate',$lang_translate)
                 ->set('filter',$f)
                 ->set('title',__('Translate'))
                 ->view('admin/dev/translate/menu');

            if(!empty($search))
            {
                $this->set('search',$search)->add('param',$lang_translate.'/search/'.$search.'/');
            }
            $this->view('admin/dev/translate/list');
        }
        
    }
}
<?php
$IB = IB::getInstance()->set('lang',IB_Lang::getInstance()->setLang((empty($_GET['lang']) ? false : $_GET['lang'])));

$p = empty($_REQUEST['q']) ? HOMEPAGE : $_REQUEST['q'];

if($p === 'ajax')
{
    $a = explode('_',$_POST['a']);
    if(!empty($a[2]))
    {
        call_user_func(array('Ajax_'.$a[0].'_'.$a[1],$a[2]));exit;
    }
    call_user_func(array('Ajax_'.$a[0],$a[1]));exit;
}
$layout = 1;

$p = filter_var(trim($p,'/'), FILTER_SANITIZE_STRING);

if(strpos($p,'/'))
{
    $q = explode('/',$p);

    if($q[0] === 'x') // ajax
    {
        $layout = 0;
        array_shift($q); // content only (no layout)
    }
    elseif($q[0] === 'mobile') // mobile
    {
        $IB->set('outputFormat','Mobile');
    }
    elseif($q[0] === 'rss')
    {
        $IB->set('feedFormat',RSS2);
        $IB->set('outputFormat','Rss');
    }
    elseif($q[0] === 'atom')
    {
        $IB->set('feedFormat',ATOM);
        $IB->set('outputFormat','Rss');
    }
    elseif($q[0] === 'xml')
    {
        $IB->set('outputFormat','Xml');
    }
    elseif($q[0] === 'json')
    {
        $layout = 0;
        $IB->set('outputFormat','Json');
    }
    /*
    elseif($q[0] === 'fr') // hack pour rendre le site referenssable en FR
    {
        array_shift($q);
        #$IB->set('LangPrefixe','fr/'); i should change all url in this case with this prefixe but not in js...
        $IB->set('lang',IB_Lang::getInstance()->setLang('fr'));
    }
    */
    elseif($q[0] === 'html') array_shift($q); /*just in case it's specify*/
    
    if($IB->get('outputFormat') !== 'Html')
    {
        array_shift($q); // output format
        
        if($q[0] === 'limit' && intval($q[1]) == $q[1])
        {
            $IB->set('limit',intval($q[1]));
            array_shift($q); // limit
            array_shift($q); // limit value
        }
    }
    $p = $q[0];
}
else
{
    $q = array($p);
}

$IB->set('p',$p);

if(MOBILE && isMobile())
{
    $IB->set('outputFormat', 'Mobile');
}

if($layout) // not ajax
{
    setcookie('h', implode($q,'/'), time()+1000000, '/');

    if(!USE_JS_PACK) include PATH.'js/'.($IB->get('outputFormat') == 'Mobile' ? 'mobile/' : '').'include.php';
}

array_shift($q);

$C = IB_Controller::control(); // get the right controller or by default the homepage one

$method = empty($q[0]) ? false : $q[0];

if(!$method)
{
    $C->index(array());
}
elseif(method_exists($C, $method))
{
    array_shift($q);
    $IB->set('sp',$method);
    $C->{$method}($q);
}
elseif(is_file(PATH.'lib/Controller/'.ucfirst($IB->get('p')).'/'.ucfirst($method).'.php'))
{
    array_shift($q);
    $IB->set('sp',$method);
    $C->subController($q);
}
else
{
    IB_Controller::control($q);
}

if(DEBUG) view('admin/dev/debug',0);

$format = $IB->get('outputFormat');

if($format === 'Html' || $format === 'Mobile' || $format === 'Json')
{
    if($error = $IB->get('error'))
    {
        if($layout) header("Location: ".URL);
        
        echo view('layout/error');
        if(LOG_ERROR) IB_Error::log();
        exit;
    }
    if($layout) // fullpage
    {
        echo view('layout/top'), IB::$data, view('layout/bottom'); exit;
    }
    // else // just content
    echo IB::$data.'<b id=T>'.$IB->get('title').'</b>';
}
elseif($format === 'Rss')
{
    IB_Rss::build()->getRender();
    IB_Rss::$feed->generateFeed();
}
elseif($format === 'Xml')
{
    header("Content-type: text/xml; charset=utf-8");
    echo "<$p>
    ".IB_Xml::build()->getRender()."
</$p>";
}
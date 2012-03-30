<!doctype html><!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<?php
echo '<html lang="'.$P->get('lang').'"><head><meta charset=utf-8><title>'.TITLE.TITLE_SEPARATOR.strip_tags($P->get('title')).'</title>';

if(DESCRIPTION) echo '<meta name="description" content="'.DESCRIPTION.'"/>';
if(KEYWORDS)    echo '<meta name="keywords" content="'.KEYWORDS.','.$P->get('keywords').'"/>';

#'<link title="Search on " href="/opensearch.xml" type="application/opensearchdescription+xml" rel="search"/>'.
#'<link rel="shortcut icon" href="/favicon.png">'.
#echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';

echo '<script>REV="'.REVISION.'";TITLE="'.TITLE.TITLE_SEPARATOR.'";HOMEPAGE="'.HOMEPAGE.'";LOGGEDIN='.intval(reader()).';</script>';

if(DEV) include PATH.'/css/pack.php';
echo '<link rel="stylesheet" href="/css/.css?'.REVISION.'"/>';

if(USE_JS_PACK)
{
    echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>'.
         #'<script>!window.jQuery && document.write(unescape(\'%3Cscript src="js/lib/jquery.js"%3E%3C/script%3E\'))</script>'.
         '<script src="/js/'.(isAdmin()?'.admin':'').'.js?'.REVISION.'"></script>';
}
else
{
    $js = $P->js();
    
    foreach($js as $script)
    {
        $c = $P->get($script);
        if(!$c[0] || $c[0] && isAdmin()) echo '<script src="/js/'.$script.'.js?'.REVISION.'"></script>';
    }
}
echo '<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>';

?></head>
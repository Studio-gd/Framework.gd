<?php
$css = array
(
  'font',
  'lib/bootstrap',
  'lib/font-awesome',
  'functions',
  'main',
  'content',
  'header',
  'footer',
  'form',
  'icon',
  'pager',
  'error',
  'search',
  'page/home',
  'page/translate',
  'page/user',
  'lib/notice',
  'lib/tipsy',
  'lib/box',
  'lib/ui',
  'contact',
  'admin',
  'adminMenu',
#placeholder4scaffold (do not remove !)
);

if(plugins)
{
  $plugins = unserialize(plugins);

  foreach($plugins as $plugin)
  {
    $css[] = 'lib/'.$plugin;
  }
}


$css = array_unique($css);


$preloadImages = 0;
/*
array
(
  'loading.gif',
  'pager.png',
  'tipsy.gif',
);
*/
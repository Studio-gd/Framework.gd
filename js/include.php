<?php

if(!USE_JS_PACK) $IB->js('lib/jquery');

$IB->js('page/init')
->js('page/init.fluid')
->js('lib/ui')
->js('lib/form')
->js('functions')
->js('lib/bootstrap',false,false)
->js('lib/modernizr')
->js('lib/textchange')
->js('lib/tipsy')
->js('lib/notice')
->js('lib/autoresize')
->js('lib/box')
->js('lib/jquery.validate.min')
->js('lib/additional-methods.min')
->js('lib/chosen.jquery.min')
#->js('lib/swfObject')
->js('formElement')
->js('page/translate', true)
->js('page/admin', true)
->js('page/user')
#->js('page/contact')
#placeholder4scaffold (do not remove !)
->js('ajax')
->js('main')
->js('admin', true);

if(plugins)
{
  $plugins = unserialize(plugins);

  foreach($plugins as $plugin)
  {
    $IB->js('lib/'.$plugin, false, false);
  }
}
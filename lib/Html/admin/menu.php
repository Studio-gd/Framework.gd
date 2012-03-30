<?php

$cp= self::get('nbPage');
if($cp && $cp>1) $titlePage='<i>'.__('Page ').$cp.'</i>'; else $titlePage='';

echo div('adminMenu','<b>'.self::get('title').'</b>'.$titlePage);
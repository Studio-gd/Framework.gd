<?php

if(DEV)
{
    $IB->js('lib/jquery')
       ->js('mobile/jquery.mobile');
}

$IB->js('lib/form')
->js('mobile/main');
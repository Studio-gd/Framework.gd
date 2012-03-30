<?php

$data = '';

if(MULTI_LANGUE)
{
    $data.= view('layout/flags');
}

echo '<footer>'.$data.'</footer>';
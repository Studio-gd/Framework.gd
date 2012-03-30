<?php
$L = IB_Lang::getInstance();

$flags = '';

$userLang = $L->getLang();

$langs = $L->langs();

foreach($langs as $v)
{    
    $classTmp = $userLang === $v['id'] ? ' sel' : '';

    $img=$v['id'];
    if($img==='en') $img='gb';
    $flags.='<a title="'.$v['label'].'" class="tt lg'.$classTmp.'" href="'.URL.$v['id'].'/'.HOMEPAGE.'" id="l_'.$v['id'].'"><img src="/img/flag/'.$img.'.png" alt=""/></a>';
}

echo '<div id="flag_menu">'.$flags.'</div>';
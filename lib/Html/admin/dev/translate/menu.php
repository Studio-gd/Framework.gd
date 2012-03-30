<?php

$user = IB_User::getInstance();
        
$lang = IB_Lang::getInstance();

$lang_translate = self::get('lang_translate');

$menu_lang= '<b class="filter">';

if($lang_translate)
{            
    $menu_lang.= $lang->getLabel($lang_translate);
}
else
{
    $menu_lang.= __('Select a language');
}
$menu_lang.= '<span>';

if($user->isAdmin())
{
    $langs = $lang->allLangs();
}
else
{
    $langs = $lang->getUserLang($user->reader());
}    


foreach($langs as $v)
{        
    if($v['id']!=='en')
    {                
        if($v['id']!==$lang_translate) $menu_lang.= '<a href="/admin/dev/translate/'.$v['id'].'/all">'.$v['label'].'</a>';
    }
}
$menu_lang.= '</span></b>';

$titleSearch=$search=$filter='';

if($lang_translate)
{
    $searchValue = __('Search');

    $search='<form class="search srch_trslt">' .
    '<input type="text" class="q" onblur="$.resetTxt(this,\''.$searchValue.'\');" onfocus="$.clearTxt(this,\''.$searchValue.'\');" value="'.$searchValue.'" /><input type="hidden" class="l" value="'.$lang_translate.'"/>'.
    '<input type="submit" class="h"/><div class="sf" onclick="$(this).parent().submit()"></div></form>';
    
    if($s = self::get('search'))
    {
        $titleSearch = '<b>»</b><b>'.__('Search').'</b><b>»</b><b>'.$s.'</b>';
    }
    else
    {
        $f = self::get('filter');

        $texts[]= __('All');
        $values[]= 'all';

        $texts[]= __('New');
        $values[]= 'new';

        $filter = '<b>»</b><b class="filter"><span>';
        foreach($texts as $key => $text)
        {
            if($f!=$values[$key]) $filter.= '<a href="/admin/dev/translate/'.$lang_translate.'/'.$values[$key].'">'.$text.'</a>'; else $title=$text;
        }
        $filter.= '</span>'.__(ucfirst($f)).'</b>';
    }
}

$addKey = '<img src="/img/icon/help.png" class="add_key tt" title="'.__('Help').'" onclick="display(\'admin/dev/translate/help\')" alt="" />';

if($user->isAdmin())
{
    $addKey.= '<img src="/img/icon/add.png" class="add_lang tt" title="Add key" onclick="admin.translate.addKey()" alt="" />';
    $addKey.= '<img src="/img/icon/add.png" class="add_lang tt" title="Add lang" onclick="admin.translate.addLang()" alt="" />';
    $addKey.= '<img src="/img/icon/user.png" class="add_lang tt" title="Add Translator" onclick="admin.translate.addTranslator()" alt="" />';
}


$cp=self::get('nbPage');
if($cp && $cp>1) $titlePage='<i>'.__('Page ').$cp.'</i>'; else $titlePage='';

echo div('adminMenu','<b><a href="/admin/dev">Admin</a></b><b>»</b><b>'.self::get('title').'</b><b>»</b>'.$menu_lang.$filter .$titleSearch.$titlePage.$search.$addKey);
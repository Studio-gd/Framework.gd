<?php
$id = (int) $P->get('id');

$data = '<form action="a=Admin_addTranslator"><h2>Add Translator</h2>'.

IB_Form_Input::create('username')
          ->label(__('username'))
          ->get();

$langs = IB_Lang::getInstance()->allLangs();

$texts[]  = __('Select a language');
$values[] = '';

foreach($langs as $v)
{
    if($v['id']!=='en')
    {
        $texts[]  = $v['label'];
        $values[] = $v['id'];
    }
}
$data.= IB_Form_Select::create('lang', $texts, $values)->get();

$data.= button(__('Add')).

'</form>';

echo $data;
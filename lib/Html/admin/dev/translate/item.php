<?php

$v = self::get('value');
        
$data = '<div class="translate_item"><div class="to_translate">'.

'<b>#
'.$v['id'].'</b>'.htmlentities(preg_replace("/\015\012|\015|\012/","<br/>",$v['str']));

if(isAdmin())
{
    $data.='<a onclick="admin.translate[\'delete\']('.$v['id'].',\''.Clean::strJs(__('Are you sure ?')).'\')">delete</a>';
    $data.='<a class="edit" onclick="admin.translate.edit('.$v['id'].')"></a>';
}


$translated = __($v['str'],'',self::get('lang_translate'));

$translated===$v['str'] ? $class=' notranslated' : $class='';

$data.='<img src="/img/icon/arrow_right.png" alt="" /></div><div class="translation"><textarea name="str_'.$v['id'].'" class="markItUpEditor'.$class.'" onblur="$(\'#dont_forget\').fadeIn(450)">'.$translated.'</textarea>'.


'</div></div>';

echo $data;
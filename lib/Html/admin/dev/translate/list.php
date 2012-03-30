<?php
if(!$lang_translate = $P->get('lang_translate'))
{
    return '';
}

$lang = IB_Lang::getInstance();

$user = IB_User::getInstance();

if(!isAdmin() && !$lang->isTranslator($user->reader(),$lang_translate)) return '';

$data = '';


$search = $P->get('search');

if($search)
{
    $total = $lang->countSearchKey($lang_translate, $search);
    
    if(!$total) return __('No result');
}
elseif($P->get('filter')==='new')
{
    $total = $lang->countNew($lang_translate);
}
else
{
    $total = $lang->getTotal();
}




$pager = new IB_Pager(9, $total);

$nbr    = $pager->number;
$offset = $pager->offset;


if($search)
{
    $langData = $lang->searchKey($pager->number, $pager->offset, $lang_translate, $search);
    
    if(!$total) return __('No result');
}
elseif($P->get('filter') === 'new')
{
    $langData = $lang->getNew($pager->number, $pager->offset, $lang_translate);
}
else
{
    $langData = $lang->get($pager->number, $pager->offset);
}

$labelDone = '';

if(!$search && $P->get('filter')!=='new')
{
    $totalDone = $lang->countDone($lang_translate);

    $percentDone = $totalDone / $total * 100;
    
    $labelDone = ' ('.round($percentDone,1).'%)';
}


$data.= '<div class="translate_title"><h3>'.__('To translate').'</h3><h4>'.__('Translation').'  "'.$lang->getLabel($lang_translate).'" '.$labelDone.'</h4></div><form class="rjs" action="/lib/a.php?p=ajax&a=Admin_saveTranslation&lang_translate='.$lang_translate.'">';

$ids = '';

foreach($langData as $v)
{
	$P->set('value',$v);
    $data.= view('admin/dev/translate/item');
    
    $ids.= $v['id'].',';
}

$data.= '<input type="hidden" name="ids" value="'.$ids.'"/>';

$data.= button(__('Save'));

$data.= '<div id="dont_forget">don\'t forget to save your changes</div>';

$data.= '</form>';

$data.= view('layout/pager');

echo div('admin_translate_list_widget',$data);
<?php
$data = '<form action="a=Admin_scaffold">'.

'<div class="fieldHalf">'.
IB_Form_Input::create('namespace')->label('Namespace')->get().
IB_Form_Input::create('name')->label('Name')->get().
'</div>';

$checkbox = new IB_Form_Checkbox();

$field = '<div class="fieldGroup">'.

'<div class="inputGroup">'.
IB_Form_Input::create('labels[]')->placeholder('Label')->get().
IB_Form_Input::create('fields[]')->placeholder('Field')->get().
IB_Form_Input::create('types[]')-> placeholder('Type')->get().
IB_Form_Input::create('selectRessources[]')-> placeholder('Select Ressource')->get().
'</div>'.

'<div class="checkboxGroup">'.
$checkbox->create('uploads[]',0)->label('Upload')->get().
$checkbox->create('calendars[]',0)->label('Calendar')->get().
$checkbox->create('requires[]',0)->label('Require')->get().
$checkbox->create('editors[]',0)->label('Editor')->get().
$checkbox->create('filters[]',0)->label('filter')->get().
$checkbox->create('textareas[]',0)->label('Textarea')->get().
$checkbox->create('checkboxes[]',0)->label('Checkbox')->get().
$checkbox->create('hidden[]',0)->label('Hidden')->get().
$checkbox->create('colorpickers[]',0)->label('Color picker')->get().
$checkbox->create('selects[]',0)->label('Select')->get().
'</div></div>';


$data.= $field.$field.$field;


/*$i=0;while($i<2)
{
    $data.= $field;
$i++;}*/



$data.= '<a class="btn btn-success"><i class="icon-plus"></i> add field</a>';


$data.= 
$checkbox->create('dates',1)->label('created/updated dates')->get().
$checkbox->create('css',1)->label('Create css')->get().
$checkbox->create('js',1)->label('Create js')->get().
$checkbox->create('fluid',0)->label('Create js fluid')->get().
$checkbox->create('mobile',1)->label('Mobile Views')->get().
$checkbox->create('search',0)->label('Search')->get().
$checkbox->create('sortable',0)->label('Sortable')->get().
$checkbox->create('adminOnly',0)->label('Admin only ?')->get().
$checkbox->create('rss',0)->label('RSS')->get().
$checkbox->create('xml',0)->label('XML')->get().
$checkbox->create('json',0)->label('JSON')->get().

button('Scaffold').'</form>';

echo div('adminScaffold',$data);


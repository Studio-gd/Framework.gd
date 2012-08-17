<?php
$data = '';

$skin = IB_Skin::getInstance();

$object = $P->get('object');

$object_id = $P->get('object_id');

$v = $skin->get($object_id, $object);

if(!$v) // default data
{
    $bg_color = '#FFFFFF';
}
else
{
    $v = $v[0];
    
    $bg_color = $v['bg_color'];
}


if(!$skin->canEdit($object_id, $object))
{
    $P->set('error',sprintf(__(IB_Error::NO_RIGHT),'Edit: '.$object.$object_id));
    return '';
}

$data.= '<form action="a=Skin_set&id='.$object_id.'&object='.$object.'">';

$data.=

IB_Form_Input::create('bg_color')
          ->label(__('Background color'))
          ->value($bg_color)
          ->colorpicker()
          ->maxlength(10)
          ->get().

button(__('Save')).cancel().#resetButton().
'</form>';
    
echo div('customize', $data);
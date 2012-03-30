<?php
$data = '<form action="a=Admin_simplepage">'.

'<div class="fieldHalf">'.
IB_Form_Input::create('namespace')->label('Namespace')->get().
IB_Form_Input::create('name')->label('Name')->get().
IB_Form_Input::create('ressource')->label('Ressource','Facultatif')->get().
'</div>';

#IB_Form_Input::create('name')->label('Name')->get().

$checkbox = new IB_Form_Checkbox();

$data.= 
$checkbox->create('css',1)->label('Create css')->get().
$checkbox->create('js',1)->label('Create js')->get().
$checkbox->create('mobile',1)->label('Mobile Views')->get().

button('Create simple page').'</form>';

echo div('adminScaffold',$data);

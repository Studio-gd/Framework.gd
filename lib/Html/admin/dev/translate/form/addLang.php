<?php
$id = (int) $P->get('id');

echo '<form action="a=Admin_addLang"><h2>Add a Language</h2>'.

IB_Form_Input::create('id')->label('id')->get().
IB_Form_Input::create('code')->label('code')->get().
IB_Form_Input::create('label')->label('label')->get().

button(__('Save')).

'</form>';
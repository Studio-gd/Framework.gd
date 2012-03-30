<?php
$id = (int) $P->get('id');

echo '<form action="a=Admin_editKey&id='.$id.'"><h2>Edit key</h2>'.

'<textarea class="markItUpEditor" name="str">'.IB_Lang::getInstance()->getKey($id).'</textarea>'.

button(__('Save')).

'</form>';
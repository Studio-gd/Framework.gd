<?php

$error = self::get('error');

echo view('admin/menu').

'<div class="error">'.$error.'</div>';
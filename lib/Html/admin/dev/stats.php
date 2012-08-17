<?php
$user = IB_User::getInstance();

$totalUsers = $user->getTotal();

$data = '<div id="stats">'.

'<div><b>'.__('Users').'</b> : '.$totalUsers.'</div>'.

'</div>';


echo $data;
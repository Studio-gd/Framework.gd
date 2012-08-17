<?php

$v = $P->get('value');

$data = '<div class="userItem"><a class="username" href="/user/'.$v['id'].'">'

.$v['email'].'</a>';

if(isAdmin())
{
    $data.= '<a title="'.__('Delete').'" class="tt delete"></a>';
}

$avatar = IB_Avatar::getInstance()->get($v['id'],'user','',$v['email']);

$data.= '<img class="avatar" src="'.$avatar.'" alt="'.$v['email'].'" />';

$data.= '</div>';

echo $data;
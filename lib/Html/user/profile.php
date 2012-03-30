<?php

$user = IB_User::getInstance();
$username = $P->get('username');

$data = '';

$v = $user->get(array('username'=>$username),1,0,'id,name,description');

if(!$v)
{
    $P->set('error',sprintf(__(IB_Error::USER_NOT_EXIST),"'$username'"));
    return '';
}

$data.=$username;

$avatar = IB_Avatar::getInstance()->get($v['id']);

$data.= '<img class="avatar" src="'.$avatar.'" alt="'.$username.'" />';

if(isReader($v['id']))
{
    $data.= '<br/><br/><a class="btn btn-primary" href="/user/edit/'.$username.'">'.__('Edit').'</a>';
}

echo div('userProfile', $data);
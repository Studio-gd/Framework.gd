<?php

$user = IB_User::getInstance();
$id = $P->get('id');

$data = '';

$v = $user->get(array('id'=>$id),1,0,'id,name,description');

if(!$v)
{
    $P->set('error',sprintf(__(IB_Error::USER_NOT_EXIST),$id));
    return '';
}

$data.= $id;

$avatar = IB_Avatar::getInstance()->get($v['id']);

$data.= '<img class="avatar" src="'.$avatar.'" />';

if(isReader($id))
{
    $data.= '<br/><br/><a class="btn btn-primary" href="/user/edit/'.$id.'">'.__('Edit').'</a>';
}

echo div('userProfile', $data);
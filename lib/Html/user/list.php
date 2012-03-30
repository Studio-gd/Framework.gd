<?php

$data = '<div class="userList">';

$pager = new IB_Pager(USER_PER_PAGE);

$nbr    = $pager->number;
$offset = $pager->offset;

$user = IB_User::getInstance();

$search = $P->get('search');

$dataUser = $user->get(array('search'=>$search),$nbr,$offset,'*');
$total = $user->getTotal(array('search'=>$search));

if($dataUser)
{
    foreach($dataUser as $v)
    {
        $P->set('value',$v);
        $data.= view('user/item');
    }

    $P->set('total',$total);

    $data.= view('layout/pager');
}
elseif($search)
{
    $data.= '<div class="noResult">'.sprintf(__('No result for "%s"'),$s).'</div>';
}
echo $data.'</div>';
<?php
$dataUser = IB_User::getInstance()->get(array(),10);

if($dataUser)
{
    echo json_encode($dataUser);
}
<?php

if(!SIGNUP_OPEN) exit;


$onclick = 'onclick="IB.box.show(\'View_get&name=user/form/login&width=350\');"';

$content = '<form id="register" action="a=User_Register">'.

'<h2>'.__('Sign up').' <span>/ <a '.$onclick.'>'.__('Sign in').'</a></span></h2>'.

IB_Form_Input::create('email')->label(__('Email'))->get().

IB_Form_Input::create('password','password')->label(__('Password'))->get().


div('groupBtn', button(__('Sign up')).cancel()).

'</form>';

echo $content;
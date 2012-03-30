<?php
$register = '';

if(SIGNUP_OPEN)
{
    $register = ' <span>/ <a onclick="IB.box.show(\'View_get&name=user/form/register&width=350\');">'.__('Sign up').'</a></span>';
}

$content = '<form action="a=User_login" id="login">'.

'<h2>'.__('Sign in').$register.'</h2>'.

IB_Form_Input::create('username')
          ->label(__('Login').' '.__('or').' '.strtolower(__('Email')))
          ->validate('usernameOrEmail')
          ->get().

IB_Form_Input::create('password','password')
          ->label(__('Password'))
          ->required()
          ->get().

div
(
    'groupBtn',
    button(__('Sign in')).cancel()
    #.'<a class="recover" href="#" onclick="IB.box.show(\'View_get&name=user/form/recover&width=340\');return false;">'.__('Recover password').'</a>'
).

'</form>';

echo $content;
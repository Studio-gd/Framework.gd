<?php

$data = '<form action="a=User_recover">';

if($key = $P->get('key'))
{
    $username = $P->get('username');
    
    if(!IB_DB::Connect()->selectOne('recover','id',"id = '$key' && username = '$username'"))
    {
        $P->set('error',sprintf(__(IB_Error::NO_RIGHT),'Recover password: '.$username));
        return '';
    }
    
    $data.= 

    IB_Form_Input::create('new_password','password')
          ->label(__('New password'))
          ->get().
    IB_Form_Input::create('new_password2','password')
          ->label(__('New password (again)'))
          ->get().
    
    '<input type="hidden" name="key" value="'.$key.'" />'.
    '<input type="hidden" name="username" value="'.$username.'" />'.
    
    button(__('Save'));
}
else
{
    $data.= '<h2>'.__('Recover password').'</h2>'.

    IB_Form_Input::create('email','email')
              ->label(__('Email'))
              ->validate('email')
              ->get().
    
    div('groupBtn', button(__('Recover password')).cancel());
}

echo $data.'</form>';
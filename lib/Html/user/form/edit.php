<?php

$id = $P->get('id');

$data = '';

$v = IB_User::getInstance()->get(array('id'=>$id),1,0,'*');

if(!$v)
{
    $P->set('error',sprintf(__(IB_Error::USER_NOT_EXIST),$id));
    return '';
}
if(!isReader($v['id']))
{
    $P->set('error',sprintf(__(IB_Error::NO_RIGHT),'Edit: '.$id));
    return '';
}

$birthdate    = $v['birthdate'];
$firstname    = $v['firstname'];
$lastname     = $v['lastname'];
$email        = $v['email'];
$sex          = $v['sexe'];
$desc         = $v['description'];
$homepage     = $v['homepage'];
$name_profile = $v['name'];
$address      = $v['address'];
$postcode     = $v['postcode'];
$country      = $v['country'];
$city         = $v['city'];

$data.= '<form action="a=User_update">';

if($avatar = IB_Avatar::getInstance()->get($v['id'],'user','',$v['email']))
{
    $data.= '<img class="avatar" src="'.$avatar.'" />';
    
    if(preg_match('/gravatar.com/', $avatar))
    {
        // TODO: checkbox to not using gravatar
    }
    else
    {
        // remove avatar link
        $data.='<a class="deleteAvatar icon delete" id="userId'.$v['id'].'">'.__('Delete my avatar').'</a>';
    }
}
elseif($avatar = IB_Avatar::getInstance()->gravatar($v['email']))
{
    // this means user has a gravatar and has set to not using it
    // TODO: checkbox to using gravatar
}


$data.= 

IB_Form_Input::create('avatar','file')
          ->label('Avatar')
          ->get().

IB_Form_Input::create('email')
          ->label(__('Email'))
          ->value($email)
          ->validate('email')
          ->get().

IB_Form_Input::create('firstname')
          ->label(__('First name'))
          ->value($firstname)
          ->required()
          ->get().

IB_Form_Input::create('lastname')
          ->label(__('Last name'))
          ->value($lastname)
          ->get().

'<a class="editPwd icon lockEdit">'.__('Change password').'</a>'.
'<div class="editPwd">'.

IB_Form_Input::create('new_password','password')
      ->label(__('New password'))
      ->get().
IB_Form_Input::create('new_password2','password')
      ->label(__('New password (again)'))
      ->get().

'</div><br /><br />';


$texts[]  = '';
$values[] = '';

$texts[]  = __('Male');
$values[] = 'male';

$texts[]  = __('Female');
$values[] = 'female';

$data.= IB_Form_Select::create('gender', $texts, $values)
        ->label(__('Gender'))
        ->value($sex)
        ->get().

IB_Form_Input::create('birthdate')
          ->label(__('Birthdate'))
          ->value(_d($birthdate))
          ->calendar()
          ->maxlength(10)
          ->get().

IB_Form_Textarea::create('description')
          ->label(__('Description'))
          ->value($desc)
          ->css('editor')
          ->get().

IB_Form_Input::create('homepage')
          ->label(__('Website'))
          ->value($homepage)
          ->validate('url')
          ->get().

IB_Form_Input::create('address')
          ->label(__('Address'))
          ->value($address)
          ->get().

IB_Form_Input::create('postcode')
          ->label(__('Postcode'))
          ->value($postcode)
          ->validate('digits')
          ->get().

IB_Form_Input::create('country')
          ->label(__('Country'))
          ->value($country)
          ->get().

IB_Form_Input::create('city')
          ->label(__('City'))
          ->value($city)
          ->get().

div('groupBtn', button(__('Save')).cancel()).#resetButton().
'</form>';
    
echo div('userEdit', $data);

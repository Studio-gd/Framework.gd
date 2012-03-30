<?php
class Ajax_User
{
    static function login()
    {
        $salt = str_rot13(base64_encode($_POST['username'].'backdraft82'));
        
        $md5 = str_replace($salt,'',IB_Core_Tools::c2sdecrypt(substr($_POST['md5'],2),'backdraft82'));
        
        $result = substr(utf8_decode(str_rot13(base64_decode(str_rot13(substr(substr($md5,0,-2),2))))),0,-2);
        
        $password = str_replace($salt,'',$result);
        
        #echo "$.displayMessage('".$_POST['md5']."');$.btnLoaded();";
        #echo "$.displayMessage('".$salt."');$.btnLoaded();";
        #echo "$.displayMessage('".$password."');$.btnLoaded();";
        
        
        if(trim($_POST['username'])==='' || trim($password)==='')
        {
            echo "$.displayMessage('".Clean::strJs(__('You must enter a username and a password'))."');$.btnLoaded();";
        }
        else
        {
            if(IB_UserConnect::getInstance()->login($_POST['username'],$password))
            {
                echo "refresh()";
                return;
            }
            else
            {
                echo '$.displayMessage("'.Clean::strJs(__('Your username or your password is incorrect')).'");$.btnLoaded();';
            }
        }
        echo "$('a.recover').show()";
    }
    static function register()
    {
        $new_username = trim($_POST['new_username']);
        
        if($new_username==='')
        {
            echo "$.displayMessage('".Clean::strJs(__("A username is required"))."');";
        }
        elseif(isset($new_username{16}))
        {
            echo "$.displayMessage('".Clean::strJs(__('Your username is too long. Maximum 16 characters'))."');";
        }
        elseif(!isset($new_username{2}))
        {
            echo "$.displayMessage('".Clean::strJs(__('Your username is too short. At least 3 characters'))."');";
        }
        elseif(IB_UserConnect::getInstance()->isEmailExist($_POST['new_email']))
        {
            echo "$.displayMessage('".Clean::strJs(__('This email already exist. Please choose another'))."');";
        }
        elseif(!isset($_POST['new_password']{3}))
        {
            echo "$.displayMessage('".strJs(__('Your password is too short. At least 4 characters'))."');";
        }
        elseif(!IB_Core_Tools::isEmail($_POST['new_email']))
        {
            echo "$.displayMessage('".Clean::strJs(__('You must enter a valid email'))."');";
        }
        else
        {
            IB_UserConnect::getInstance()->add();
            echo "refresh();";
            return;
        }
        echo "$.btnLoaded();";
    }
    static function update()
    {
        if($_POST['new_password'] !== $_POST['new_password2'])
        {
            $error = __('Your password does not matched');
        }
        if(isset($error))
        {
            $error = Clean::strJs($error);
            echo "$.displayMessage('$error');";
        }
        else
        {
            IB_User::getInstance()->updateUser();

            echo "$.displayMessage('".Clean::strJs(__('Your profile has been updated'))."');".

            "display('user/".IB_User::getInstance()->readerName()."');";
        }
    }
    static function delete()
    {
        $username = Clean::username($_POST['username']);
        
        if($user_id = IB_User::getInstance()->getUserIdFromName($username))
        {
            IB_User::getInstance()->deleteUser($user_id);
            echo __('This user has been deleted.');
        }
        
    }
    static function recover()
    {
        if(!empty($_POST['email']))
        {
            if(IB_UserConnect::getInstance()->recover(Clean::email($_POST['email'])))
            {
                $m = Clean::strJs(__("You'll receive an email with instruction."));

                echo "IB.box.rm();";
            }
            else
            {
                $m = Clean::strJs(__("This email is not valid."));
            }

            echo "$.displayMessage('$m');";
        }
        elseif(!empty($_POST['new_password']))
        {
            if($_POST['new_password'] !== $_POST['new_password2'])
            {
                $error = Clean::strJs(__('Your password does not matched'));
                
                echo "$.displayMessage('$error');";
                return;
            }

            if(IB_UserConnect::getInstance()->changePassword())
            {
                echo "refresh();";
            }
        }
    }
    
    static function logout()
    {
        $db = IB_DB::Connect();
        $db->query("DELETE FROM connected WHERE user_id = ".reader());
    }
}
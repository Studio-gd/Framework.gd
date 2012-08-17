<?php
class Ajax_User
{
    static function login()
    {
        $salt = str_rot13(base64_encode($_POST['email'].'studio.gd'));
        
        $md5 = str_replace($salt,'',IB_Core_Tools::c2sdecrypt(substr($_POST['md5'],2),'studio.gd'));
        
        $result = substr(utf8_decode(str_rot13(base64_decode(str_rot13(substr(substr($md5,0,-2),2))))),0,-2);
        
        $password = str_replace($salt,'',$result);
        
        #echo "$.displayMessage('".$_POST['md5']."');$.btnLoaded();";
        #echo "$.displayMessage('".$salt."');$.btnLoaded();";
        #echo "$.displayMessage('".$password."');$.btnLoaded();";
        
        
        if(trim($_POST['email'])==='' || trim($password)==='')
        {
            echo "$.displayMessage('".Clean::strJs(__('You must enter an email and a password'))."');$.btnLoaded();";
        }
        else
        {
            if(IB_UserConnect::getInstance()->login($_POST['email'],$password))
            {
                echo "refresh()";
                return;
            }
            else
            {
                echo '$.displayMessage("'.Clean::strJs(__('Your email or your password is incorrect')).'");$.btnLoaded();';
            }
        }
        echo "$('a.recover').show()";
    }
    static function register()
    {
        if(IB_UserConnect::getInstance()->isEmailExist($_POST['email']))
        {
            echo "$.displayMessage('".Clean::strJs(__('This email already exist. Please choose another'))."');";
        }
        elseif(!isset($_POST['password']{3}))
        {
            echo "$.displayMessage('".strJs(__('Your password is too short. At least 4 characters'))."');";
        }
        elseif(!IB_Core_Tools::isEmail($_POST['email']))
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

            "display('user/".reader()."');";
        }
    }
    /*
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
    */
    static function logout()
    {
        $db = IB_DB::Connect();
        $db->query("DELETE FROM connected WHERE user_id = ".reader());
    }
}
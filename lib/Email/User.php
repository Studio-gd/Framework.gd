<?php
class Email_User
{
    static function register($username, $password, $email)
    {
        $m = sprintf(__('Dear %s,

Thank you for registering.

Your homepage is located at %s

Your login information are:

Login: %s
Password: %s

Best regards'),
        
        $username,
        URL.'user/'.$username,
        $username,
        $password);

        IB_Core_Email::create()
            ->recipient($email)
            ->subject(TITLE.' '.__('Welcome to '.TITLE))
            ->message($m)
            ->send();
    }
    
    
    static function recover($username, $key)
    {
        $m = sprintf(__('Hello %s,

You submitted a password recovery request for your "%s" account. Please click the following link to reset your password:

%s#retrieve/%s/%s

Best regards'),
        
        $username,
        $username,
        URL.'user/recover/'.$username.'/'.$key);

        IB_Core_Email::create()
            ->recipient($email)
            ->subject(TITLE.' Recover password')
            ->message($m)
            ->send();
    }
}
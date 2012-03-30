<?php class Ajax_Contact
{
    static function send()
    {
        $msg = '';
        
        if(empty($_POST['first_name']) || empty($_POST['last_name']))
        {
            $msg.= 'Vous devez saisir un nom et un prénom<br/>';
        }
        if(!IB_Core_Tools::isEmail($_POST['email']))
        {
            $msg.= 'Vous devez saisir un email valide<br/>';
        }
        
        if(!empty($msg)) // display error message
        {
            echo "$.displayMessage('".Clean::strJs($msg)."');";
        }
        else // no error -> send email
        {
            $firstname = $_POST['first_name'];
            $lastname  = $_POST['last_name'];
            $email     = $_POST['email'];
            
            $message =
            'Nom : '.$firstname.' '.$lastname."\n".
            'Email : '.$email."\n".
            'Message : '."\n\n".

            $_POST['message'];

            $subject = 'Contact  ['.$firstname.' '.$lastname.']';
            
            IB_Core_Email::create()
            ->recipient('contact@dev.dev')
            ->subject($subject)
            ->message($message)
            ->from('"'.$firstname.' '.$lastname.'" <'.$email.'>')
            ->send();
            
            echo "$.displayMessage('".Clean::strJs('Votre message a bien été envoyé.')."');display('contact')";
        }
    }

}

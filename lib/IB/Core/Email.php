<?php
Class IB_Core_Email
{
    protected $recipient = false;
    protected $from      = false;
    protected $subject   = false;
    protected $message   = false;

    static function create()
    {
        return new IB_Form_Input();
    }

    function recipient($recipient)
    {
        $this->recipient = $recipient; return $recipient;
    }
    function from($from)
    {
        $this->from = $from; return $this;
    }
    function subject($subject)
    {
        $this->subject = $subject; return $this;
    }
    function message($message)
    {
        $this->message = $message; return $this;
    }

    static function send()
    {
        if(!$this->from) $this->from = EMAIL_FROM;

        if(!DEV)
        {
            mail
            (
                $this->recipient,
                stripslashes($this->subject),
                stripslashes($this->message),
                "Content-Type: text/plain; charset=UTF-8\nFrom: ".$this->from
            );
        } 
    }



}
<div class=contact>
<h1>Contact</h1>

<form id="contact" action="a=Contact_send">
<?php

echo

IB_Form_Input::create('first_name')
          ->required()
          ->label(__('First name'))
          ->get().

IB_Form_Input::create('last_name')
          ->required()
          ->label(__('Last name'))
          ->get().

IB_Form_Input::create('email')
          ->required()
          ->label(__('Email'))
          ->validate('email')
          ->get().

IB_Form_Textarea::create('message')
             ->required()
             ->counter(900)
             ->label(__('Message'))
             ->get().

button(__('Send')).cancel();

?>
</form>
</div>
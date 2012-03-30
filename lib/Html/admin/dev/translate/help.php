<?php
$data= '<div class="help_question">things to know...</div>'
     . '<div class="help_text">'
     . '%s will be automatically replaced by a word or a number (username, song name, etc...). It must be add in the translation where the word must appear in the sentence.<br />'
     . '%d is the same but it\'s replaced only by a number.<br />'
     . 'Example: "Hi %s" => "Salut %s"<br />'
     . 'Example: "%d groups" => "%d groupes"<br />'
     . '</div>';

 $data.= '<div class="help_text">'
      . htmlentities('<!--s-->').' means it\'s the plural version of the word (this code is necessary when english word is the same singular and plural). it\'s not necessary to add this code in the translation <br />'
      . htmlentities('<!--f-->'). ' is the same but indicate it\'s the feminine version of the word<br />'
      . 'Example: "%s play count'.htmlentities('<!--s-->').'" => "%s lectures"<br />'
      . 'Example: "All'.htmlentities('<!--f-->').'" => "Toutes"<br /><br />'
      
      . 'A new translation will be activate when the translation is done at least at 70% and after a check.<br /><br />'
      
      . 'Try to keep the same text length if possible.<br /><br />'
      
      . 'If you think english text is wrong, please tell me and I\'ll change it (if I agree ;)).<br /><br />'
      
      . 'if you\'re not sure of the exact sense of a translation (can depend of the context) or if you have any question, please ask me directly to s.baronnet@gmail.com<br /><br />'
      
      . 'And many thanks for your contribution !<br />'
      
      . '</div>';

echo $data;
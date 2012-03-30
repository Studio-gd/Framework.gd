<?php
class IB_Core_TextFormat
{
    private static $text;
    
    function __construct($text='')
    {
        self::setText($text);
    }
    static function getInstance($text='')
    {
        return new IB_Core_TextFormat($text);
    }
    function setText($text)
    {
        self::$text = $text;
        return $this;
    }
    function getText()
    {
        return self::$text;
    }
    function alphanumeric()
    {
        self::setText(preg_replace("[^A-Za-z0-9_]",'',self::getText()));
        return $this;
    }
    function stripHtml()
    {
        self::setText(strip_tags(self::getText()));
        return $this;
    }
    function stripUnsafeHtml()
    {
        self::setText(strip_tags(self::getText(),'<br><b><pre><p><i><u><strong><img><font><object><embed><param><span><a><iframe>'));
        return $this;
    }
    function keepSimpleHtml()
    {
        self::setText(strip_tags(self::getText(),'<b><pre><i><u><strong><font><center><span>'));
        return $this;
    }
    function replaceAccents()
    {
$pairs=
array(
'À'=>'A',
'Â'=>'A',
'Ã'=>'A',
'Ä'=>'A',
'Â'=>'A',
'Æ'=>'A',
'Ç'=>'C',
'È'=>'E',
'É'=>'E',
'Ê'=>'E',
'Ë'=>'E',
'Ì'=>'I',
'Í'=>'I',
'Î'=>'I',
'Ï'=>'I',
'Ð'=>'D',
'Ñ'=>'N',
'Ò'=>'O',
'Ó'=>'O',
'Ô'=>'O',
'Õ'=>'O',
'Ö'=>'O',
'Ø'=>'O',
'Ù'=>'U',
'Ú'=>'U',
'Û'=>'U',
'Ü'=>'U',
'Ý'=>'y',
'à'=>'a',
'á'=>'a',
'â'=>'a',
'ã'=>'a',
'ä'=>'a',
'å'=>'a',
'æ'=>'o',
'ç'=>'c',
'è'=>'e',
'é'=>'e',
'ê'=>'e',
'ë'=>'e',
'ì'=>'i',
'í'=>'i',
'î'=>'i',
'ï'=>'i',
'ð'=>'o',
'ñ'=>'n',
'ò'=>'o',
'ó'=>'o',
'ô'=>'o',
'õ'=>'o',
'ö'=>'o',
'ø'=>'o',
'ù'=>'u',
'ú'=>'u',
'û'=>'u',
'ý'=>'y',
'ý'=>'y',
'þ'=>'b',
'ÿ'=>'y'
);
        self::setText(strtr(self::getText(),$pairs));
        return $this;
    }
    function word_limiter($str, $limit = 100, $end_char = '&#8230;')
    {
        if(trim($str) == '')
        {
            return $str;
        }
    
        preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);
            
        if(strlen($str) == strlen($matches[0]))
        {
            $end_char = '';
        }
        
        return rtrim($matches[0]).$end_char;
    }
    function character_limiter($str, $n = 500, $end_char = '&#8230;')
    {
        if (strlen($str) < $n)
        {
            return $str;
        }
        
        $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

        if (strlen($str) <= $n)
        {
            return $str;
        }

        $out = "";
        foreach (explode(' ', trim($str)) as $val)
        {
            $out .= $val.' ';
            
            if (strlen($out) >= $n)
            {
                $out = trim($out);
                return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
            }        
        }
    }
    function highlight_phrase($str, $phrase, $tag_open = '<strong>', $tag_close = '</strong>')
    {
        if ($str == '')
        {
            return '';
        }
    
        if ($phrase != '')
        {
            return preg_replace('/('.preg_quote($phrase, '/').')/i', $tag_open."\\1".$tag_close, $str);
        }

        return $str;
    }
    function auto_link($str, $type = 'both', $popup = FALSE)
    {
        if ($type != 'email')
        {
            if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches))
            {
                $pop = ($popup == TRUE) ? " target=\"_blank\" " : "";
    
                for ($i = 0; $i < count($matches['0']); $i++)
                {
                    $period = '';
                    if (preg_match("|\.$|", $matches['6'][$i]))
                    {
                        $period = '.';
                        $matches['6'][$i] = substr($matches['6'][$i], 0, -1);
                    }
        
                    $str = str_replace($matches['0'][$i],
                                        $matches['1'][$i].'<a href="http'.
                                        $matches['4'][$i].'://'.
                                        $matches['5'][$i].
                                        $matches['6'][$i].'"'.$pop.'>http'.
                                        $matches['4'][$i].'://'.
                                        $matches['5'][$i].
                                        $matches['6'][$i].'</a>'.
                                        $period, $str);
                }
            }
        }

        if ($type != 'url')
        {
            if (preg_match_all("/([a-zA-Z0-9_\.\-\+]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches))
            {
                for ($i = 0; $i < count($matches['0']); $i++)
                {
                    $period = '';
                    if (preg_match("|\.$|", $matches['3'][$i]))
                    {
                        $period = '.';
                        $matches['3'][$i] = substr($matches['3'][$i], 0, -1);
                    }
        
                    $str = str_replace($matches['0'][$i], safe_mailto($matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i]).$period, $str);
                }
            }
        }

        return $str;
    }
    function smiley()
    {
        $search = array
        (
        '/:-D/',
        '/:\)/',
        '/:-\)/',
        '/;\)/',
        '/;-\)/',
        '/:P/',
        '/:p/',
        '/:\(/',
        '/:D/',
        '/:O/',
        '/:o/',
        '/:\|/'
        );

        $replace = array
        (
        '<img class="smiley" src="'.URL.'img/emoticon/grin.png" alt=":-D" />',
        '<img class="smiley" src="'.URL.'img/emoticon/smile.png" alt=":)" />',
        '<img class="smiley" src="'.URL.'img/emoticon/smile.png" alt=":-)" />',
        '<img class="smiley" src="'.URL.'img/emoticon/wink.png" alt=";)" />',
        '<img class="smiley" src="'.URL.'img/emoticon/wink.png" alt=";-)" />',
        '<img class="smiley" src="'.URL.'img/emoticon/tongue.png" alt=":P" />',
        '<img class="smiley" src="'.URL.'img/emoticon/tongue.png" alt=":p" />',
        '<img class="smiley" src="'.URL.'img/emoticon/unhappy.png" alt=":(" />',
        '<img class="smiley" src="'.URL.'img/emoticon/grin.png" alt=":D" />',
        '<img class="smiley" src="'.URL.'img/emoticon/surprised.png" alt=":O" />',
        '<img class="smiley" src="'.URL.'img/emoticon/surprised.png" alt=":o" />',
        '<img class="smiley" src="'.URL.'img/emoticon/indifferent.png" alt=":|" />'
        );

        $limit=3;

        self::setText(preg_replace($search, $replace, self::getText(), $limit));
        return $this;
    }
    function wikiRenderer()
    {
        require_once(PATH.'/lib/stringparser.class.php');
        require_once(PATH.'/lib/stringparser_bbcode.class.php');
        
        $bbcode = new StringParser_BBCode();
        $bbcode->addFilter (STRINGPARSER_FILTER_PRE, 'convertlinebreaks');

        //$bbcode->addParser (array ('block', 'inline', 'link', 'listitem'), 'htmlspecialchars');
        $bbcode->addParser (array ('block', 'inline', 'link', 'listitem'), 'nl2br');
        $bbcode->addParser ('list', 'bbcode_stripcontents');

        $bbcode->addCode ('b', 'simple_replace', null, array ('start_tag' => '<b>', 'end_tag' => '</b>'),
                          'inline', array ('listitem', 'block', 'inline', 'link'), array ());
       $bbcode->addCode ('u', 'simple_replace', null, array ('start_tag' => '<u>', 'end_tag' => '</u>'),
                         'inline', array ('listitem', 'block', 'inline', 'link'), array ());
        $bbcode->addCode ('i', 'simple_replace', null, array ('start_tag' => '<i>', 'end_tag' => '</i>'),
                          'inline', array ('listitem', 'block', 'inline', 'link'), array ());
                          
                          
        $bbcode->addCode ('url', 'usecontent?', 'do_bbcode_url', array ('usecontent_param' => 'default'),
                          'link', array ('listitem', 'block', 'inline'), array ('link'));
                          
      $bbcode->addCode ('color', 'callback_replace', 'do_bbcode_color', array ('usecontent_param' => 'default'),
                        'inline', array ('listitem', 'block', 'inline', 'link'), array ());       
                          
        $bbcode->addCode ('link', 'callback_replace_single', 'do_bbcode_url', array (),
                          'link', array ('listitem', 'block', 'inline'), array ('link'));
        $bbcode->addCode ('img', 'usecontent', 'do_bbcode_img', array (),
                          'image', array ('listitem', 'block', 'inline', 'link'), array ());
        $bbcode->addCode ('bild', 'usecontent', 'do_bbcode_img', array (),
                          'image', array ('listitem', 'block', 'inline', 'link'), array ());
        $bbcode->setOccurrenceType ('img', 'image');
        $bbcode->setOccurrenceType ('bild', 'image');
        $bbcode->setMaxOccurrences ('image', 10);
        $bbcode->addCode ('list', 'simple_replace', null, array ('start_tag' => '<ul>', 'end_tag' => '</ul>'),
                          'list', array ('block', 'listitem'), array ());
        $bbcode->addCode ('*', 'simple_replace', null, array ('start_tag' => '<li>', 'end_tag' => '</li>'),
                          'listitem', array ('list'), array ());
        $bbcode->setCodeFlag ('*', 'closetag', BBCODE_CLOSETAG_OPTIONAL);
        $bbcode->setCodeFlag ('*', 'paragraphs', true);
        $bbcode->setCodeFlag ('list', 'paragraph_type', BBCODE_PARAGRAPH_BLOCK_ELEMENT);
        $bbcode->setCodeFlag ('list', 'opentag.before.newline', BBCODE_NEWLINE_DROP);
        $bbcode->setCodeFlag ('list', 'closetag.before.newline', BBCODE_NEWLINE_DROP);
        $bbcode->setRootParagraphHandling (true);

        self::stripUnsafeHtml();

        self::setText($bbcode->parse(self::getText()));
        return $this;
    }
    
    function removeBBcode()
    {
        //dirt way to remove bb code
        
        self::wikiRenderer();
        self::stripHtml();
        
        return $this;
    }
    function removeLineBreak()
    {
        self::setText(preg_replace("/\015\012|\015|\012/",' ',self::getText()));
        return $this;
    }
}
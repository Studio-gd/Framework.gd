<?php 

if(isset($mobile))
{
    include PATH.'css/mobile/include.php';
}
else
{
    include PATH.'css/include.php';
}

$less = new IB_Core_Lessc();

$d='';
foreach($css as $file)
{
    $fd = file_get_contents(PATH.'css/'.$file.'.css');
    
    $d.= trim($fd);
    
}
$d = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", '', $d);


$d = $less->parse($d);

// remove ws around { } and last semicolon in declaration block
$d = preg_replace('/\\s*{\\s*/', '{', $d);
$d = preg_replace('/;?\\s*}\\s*/', '}', $d);

// remove ws surrounding semicolons
$d = preg_replace('/\\s*;\\s*/', ';', $d);

// minimize hex colors
$d = preg_replace('/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i','$1#$2$3$4$5', $d);

$replace = array
(
  '  '  => '',
  "\n"  => '',
  "\t"  => '',
  "\r"  => '',
  ", "  => ',',
  " {"  => '{',
  "{ "  => '{',
  "; "  => ';',
  ": "  => ':',
  "} "  => '}',
  " }"  => '}',
  ";}"  => '}',
  ";}"  => '}',
  ":0px"=> ':0'
);

$d = strtr($d,$replace);
$d = strtr($d,$replace);


if(!empty($preloadImages)) #image preloader
{
    $d.= 'body:after{content:';    
    foreach($preloadImages as $img)
    {
        $d.= " url(../img/$img)";
    }
    $d.= ';display:none}';
}


if(isset($mobile))
{
    $f=fopen(PATH."css/mobile.css","w+");
    fwrite($f,$d);
    fclose($f);
}
else
{
    $f=fopen(PATH."css/.css","w+");
    fwrite($f,$d);
    fclose($f);
}


<?php 
class IB_Rss extends IB
{
 static $feed = null;
 static $s=false;
 static function build()
 {
     if(!self::$s)
     {
         header("Content-type: text/xml; charset=utf-8");
         
         self::$s=new IB_Rss();
         self::$feed=new IB_Core_FeedWriter(self::$s->get('feedFormat'));
     }
     return self::$s;
 }
 function getRender()
 {
     $title = TITLE.TITLE_SEPARATOR.utf8_decode(strip_tags($this->get('title')));
     
     self::$feed->setTitle($title);
     self::$feed->setLink(URL.$this->query());
     self::$feed->setDescription($title);
     self::$feed->setImage($title,URL.$this->query(),URL.'logo.png');


     $r = explode('/',self::$name);

     $cn = '';

     foreach($r as $v)
     {
        $cn.= ucfirst($v).'_';
     }

     $cn = trim($cn,'_');

     $rss = call_user_func(array('Rss_'.$cn,'getInstance'));

     $rss->preRender();
 }
}
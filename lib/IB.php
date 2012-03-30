<?php class IB
{
  static $name = false;
  static $data='';
  static $vars=array('nbPage'=>1,'outputFormat'=>'Html','sp'=>'','title'=>'');
  static $js=array();

  static function getInstance(){return new IB();}
  
  function get($key)
  {
      return empty(self::$vars[$key]) ? false : self::$vars[$key];
  }
  function set($key, $value)
  {
      self::$vars[$key] = $value; return $this;
  }
  function add($key, $value)
  {
      if(!isset(self::$vars[$key])) self::set($key,'');
      
      return self::set($key, self::$vars[$key].$value);
  }
  function _unset($key)
  {
      self::set($key, false);
  }
  function js($js='', $adminOnly = false, $compress = true)
  {
      if($js == '') return self::$js;
      self::$js[] = $js;
      
      return $this->set($js,array($adminOnly, $compress));
  }
  function query()
  {
      $query=$this->get('p').'/';
      
      if($this->get('sp')) $query.=$this->get('sp').'/';
      if($this->get('param')) $query.='/'.$this->get('param');
      
      return str_replace('//','/',$query);
  }
  function initCache($lifetime=300, $tag='')
  {
      $c['cache'] = new IB_Core_Cache();
      
      $n = $this->get('outputFormat').'-'.self::$name.'_';

      if(isLoggedIn()) $n.= isAdmin() ? '_adm' : '_log';

      $c['key'] = $n.IB_Lang::getInstance()->getLang().$tag;
      
      return $c;
  }

  static function view($name, $render = false)
  {
      self::$name = $name;  

      $P = IB::getInstance();

      if(FILE_CACHE && $lifetime = $P->get('cached'))
      {
          $c = $P->initCache($lifetime,$P->get('cache_tags'));
          
          if(!$data = $c['cache']->get($c['key']))
          {
              $data = $P->getView($name);
              
              $c['cache']->set($c['key'],$data);
          }
          $P->_unset('cached');
          $P->_unset('cache_tags');
      }

      if(!isset($data))
      {
          $data = self::getView($name);
      }
      
      if($render) return $data;

      self::$data.= $data;

      return new IB();
  }
  static function getView($name)
  {
      if(self::get('outputFormat') === 'Rss' || self::get('outputFormat') === 'Xml') return;

      $P = IB::getInstance();

      ob_start();
      include(PATH.'lib/'.$P->get('outputFormat')."/$name.php");

      $c = ob_get_contents();
      ob_end_clean();

      return $c;
  }

  function getValue($name, $default = '')
  {
      $v = $this->get('value');
      
      return isset($v[$name]) ? $v[$name] : $default;
  }
  function getUrlValues($values)
  {
      if(empty($values[0])) return false;

      if(is_int($values[0]))
      {
          $this->set('nbPage',$values[0]);
          array_shift($values);
      }

      $isValue = false;

      foreach($values as $i => $v)
      {
          if($isValue)
          {
              $isValue = false; continue;
          }

          if(!empty($values[$i+1]) && $value = $values[$i+1])
          {
              if($v === 'p') $v = 'nbPage';

              $this->set($v,$value)->add('param',$v.'/'.$value.'/');
          }

          $isValue = true;
      }
      return false;
  }
}
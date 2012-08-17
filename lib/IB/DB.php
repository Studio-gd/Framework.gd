<?php class IB_DB
{
  static $queries = array();
  static $mc = false;
  static $connection = false;
  static function connect()
  {
      if(self::$connection) return self::$connection;
      
      if(MEMCACHE) self::$mc = IB::getInstance()->get('mc');
      
      mysql_select_db(DBNAME,mysql_connect(DBHOST,DBLOGIN,DBPASS));
      return self::$connection = new IB_DB();
  }
  function query($q)
  {
      if(DEBUG) $this->queries('('.IB::name().':query) '.$q);

      if($r = mysql_query($q))
      {
          return $r;
      }
      echo $q.' : '.mysql_error();
      error_log($q.' : '.mysql_error());
      
      return false;
  }
  function fetchOne($q)
  {
      if(DEBUG) $this->queries('('.IB::$name.':fetchOne) '.$q);
      
      if($r=mysql_query($q.' LIMIT 1'))
      {
          $d=mysql_fetch_array($r);
          return $d[0];
      }
      return false;
  }
  function count($q, $l = false, $cacheTime = false, $reloadCache = false)
  {
      if(DEBUG) $this->queries('('.IB::$name.':count) '.$q);
      
      if($l) $q.=' LIMIT '.$l;
      
      if(MEMCACHE && $cacheTime)
      {
          $mc = self::$mc;

          $cacheName = md5($q);

          if($reloadCache || !$data = $mc->get($cacheName))
          {
              if($r=mysql_query($q))
              {
                  $data = mysql_num_rows($r);
                  
                  if(!$mc->replace($cacheName, $data, false, $cacheTime))
                  {
                      $mc->set($cacheName, $data, false, $cacheTime);
                  }
              }
              else
              {
                  echo $q.' : '.mysql_error();
                  error_log($q.' : '.mysql_error());
                  return false;
              }
          }
          return $data;
      }
      
      if($r=mysql_query($q)) return mysql_num_rows($r);

      echo $q.' : '.mysql_error();
      error_log($q.' : '.mysql_error());
      return false;
  }
  function has($q){return $this->count($q,1);}
  function lastInsertId(){return mysql_insert_id();}
  function fetch($q,$n=0,$o=0,$r=array())
  {
      if(DEBUG) $this->queries('('.IB::$name.':fetch) '.$q);
      
      if($rq=mysql_query($q.$this->limit($n,$o)))
      {
          while($d=mysql_fetch_assoc($rq)) $r[]=$d;
          return $r;
      }
      echo $q.' : '.mysql_error();
      error_log($q.' : '.mysql_error());
      return false;
  }
  
  
  function select($table, $properties = '*', $conditions = false, $number = 0, $offset = 0, $cacheTime = false)
  {
      if(!empty($conditions))
      {
          $conditions = trim($conditions);
          
          if(substr($conditions,0,5) !== 'ORDER') $conditions = "WHERE $conditions";
      }
      else
      {
          $conditions = '';
      }
      
      $query = "SELECT $properties FROM $table $conditions";
      
      if(MEMCACHE && $cacheTime)
      {
          $mc = self::$mc;
          
          $cacheName = $table.md5($properties.$conditions).$number.$offset;
          
          if(!$data = $mc->get($cacheName))
          {
              $data = $this->fetch($query, $number, $offset);

              if(!$mc->replace($cacheName, $data, false, $cacheTime))
              {
                  $mc->set($cacheName, $data, false, $cacheTime);
              }
          }
          return $data;
      }
      return $this->fetch($query, $number, $offset);
  }
  
  function selectOne($table, $property, $conditions = false, $cacheTime = false, $reloadCache = false)
  {
      if(substr($conditions,0,5) !== 'ORDER') $conditions = "WHERE $conditions";
      
      $query = "SELECT $property FROM $table".($conditions ? " $conditions" : '');
  
      if(MEMCACHE && $cacheTime)
      {
          $mc = self::$mc;
  
          $cacheName = $table.$property.md5($conditions);
  
          if($reloadCache || !$data = $mc->get($cacheName))
          {
              $data = $this->fetchOne($query);
              
              if(!$mc->replace($cacheName, $data, false, $cacheTime))
              {
                  $mc->set($cacheName, $data, false, $cacheTime);
              }
          }
          return $data;
      }
      return $this->fetchOne($query);
  }
  
  function delete($table, $conditions = false)
  {
      $this->query("DELETE FROM $table".($conditions ? " WHERE $conditions" : ''));
  }
  
  function update($table, $changes, $conditions = false)
  {  
      $query = "UPDATE $table SET ";
      
      foreach($changes as $field => $v)
      {
          #$query.= "$field = ".(is_numeric($v) && intval($v) == $v ? $v : "'$v'").',';
          $query.= "$field = '$v'".',';
      }
      
      $query = substr($query, 0, -1);
      
      $query.= $conditions ? " WHERE ".$conditions : '';
      
      if(MEMCACHE)
      {
          $mc = self::$mc;
          $mc->delete($table.md5('*'.$conditions).'00');
          $mc->delete($table.md5('*'.$conditions).'10');
      }
      
      return $this->query($query);
  }
  
  function insert($table, $data)
  {
      $fields = $values = "";
  
      foreach($data as $f => $v)
      {
          $fields .= "$f,";
          #$values .= (is_numeric($v) && intval($v) == $v ? $v : "'$v'").',';
          $values .= "'$v',";
      }
      
      $fields = trim($fields, ',');
      $values = trim($values, ',');

      return $this->query("INSERT INTO $table ($fields) VALUES($values)");
  }
  
  function limit($n=0,$o=0){return $n?" LIMIT $o,$n":'';}
  
  function queries($q = false)
  {
      if(!$q) return self::$queries;

      self::$queries[] = $q;
  }
}


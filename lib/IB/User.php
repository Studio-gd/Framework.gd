<?php
class IB_User extends IB_DB
{
    static $s          = false;
    static $admin      = false;
    static $user_id    = false;
    static $username   = false;
    static $isLoggedIn = false;

    static function getInstance(){if(!self::$s){self::$s=new IB_User();}return self::$s;}

    function isLoggedIn()
    {
        if(self::$isLoggedIn) return self::$isLoggedIn;
        
        if($ib = empty($_COOKIE['i']) ? false : $_COOKIE['i'])
        {
            $r=explode('%',base64_decode($ib));
            
            $scope = relativeDate('-10 days');
            $hash = $this->selectOne('connected','hash',"user_id=".intval($r[2])." && inserted > '$scope'",360);

            if(crypt($r[0],SALT) === $r[1] && $hash === $r[3])
            {
                return self::$isLoggedIn = true;
            }
            setcookie('i','',-1,'/'); // connection expired -> we clean cookie
        }
        return self::$isLoggedIn = false;
    }
    function isAdmin()
    {
        if(self::$admin) return self::$admin;
        
        if(!$this->isLoggedIn() || !$admin = $this->selectOne('user','admin',"id=".$this->reader(), 4000))
        {
            return false;
        }
        if($admin==1) return self::$admin=true;

        return self::$admin=false;
    }

    function reader()
    {
        if(self::$user_id) return self::$user_id;
        
        if($ib = empty($_COOKIE['i']) ? false : $_COOKIE['i'])
        {
            $r = $ar=explode('%', base64_decode($ib));
          
            $user_id = (int) $r[2];
                      
            if($email = $this->selectOne('user','email',"id = $user_id", 18000))
            {
                if($email === $r[0]) return self::$user_id = $user_id;
            }
        }
        return self::$user_id = false;
    }
    /*
    static function readerName()
    {
        if(self::$username) return self::$username;
        
        if($ib = empty($_COOKIE['i']) ? false : $_COOKIE['i'])
        {
            $r=explode('%', base64_decode($ib));
            return self::$username = preg_replace("[^A-Za-z0-9@_]",'',$r[0]);
        }
        return self::$username=false;
    }
    */
    function getEmail($user_id=false)
    {
        if(!$user_id) $user_id = $this->reader();
        
        return $this->selectOne('user','email',"id=".intval($user_id),18000);
    }
    
    function online()
    {
        if($user_id = $this->reader())
        {
            $this->delete('user_online',"user_id=".$user_id);
            $this->query("INSERT INTO user_online VALUES($user_id,'".now()."')");
        }
    }

    function updateUser()
    {
        $id = $this->reader();
        
        IB_Avatar::getInstance()->add($id);
        
        $email = Clean::email($_POST['email']);

        $changes = array
        (
            'email'       => $email,
            'firstname'   => Clean::string($_POST['firstname']),
            'lastname'    => Clean::string($_POST['lastname']),
            'description' => $_POST['description'],
            'sexe'        => Clean::string($_POST['gender']),
            'birthdate'   => Clean::string($_POST['birthdate']),
            'homepage'    => Clean::url($_POST['homepage']),
            'address'     => Clean::string($_POST['address']),
            'postcode'    => Clean::string($_POST['postcode']),
            'country'     => Clean::string($_POST['country']),
            'city'        => Clean::string($_POST['city']),
        );
        
        if(!empty($_POST['new_password']))
        {
            $newPassword = IB_UserConnect::getInstance()->processPassword($_POST['new_password'], $email);
            
            $changes = array_merge($changes,array('sha_pwd' => $newPassword['sha1'], 'salt' => $newPassword['salt']));
        }
        
        $this->update('user', $changes, "id = $id");
    }
    
    function getName($user_id)
    {
        return $this->selectOne('user','username',"id=".intval($user_id),40000);
    }
    function getFullName($user_id)
    {
        return $this->selectOne('user','firstname',"id=".intval($user_id),40000);
    }
    function isOnline($user_id)
    {
        $date = relativeDate('-4 minutes');
        $user_id = (int) $user_id;
        return $this->selectOne('user_online','user_id',"user_id = $user_id && inserted > '$date'",360);
    }
    function getLastConnection($user_id)
    {
        return $this->selectOne('user_online','user_id',"user_id = $user_id ORDER BY `inserted` DESC",300);
    }
    /*
    function getUserIdFromName($name)
    {
        return $this->selectOne('user','id',"username = '$name'",40000);
    }
    */
    function buildQuery($options)
    {
        $default = array
        (
            'id'       => false,
            'email'    => false,
            'search'   => false,
            'order'    => " ORDER BY join_date DESC",
        );
        $opt = array_merge($default,$options);
    
        $w = "disabled = 0 ";
    
        if($opt['id'])
        {
            $w.=' && id = '.$opt['id'];
        }
        elseif($opt['email'])
        {
            $w.=" && email = '".Clean::email($opt['email'])."'";
        }
        elseif($s = $opt['search'])
        {                
            $keywords = array();
    
            $sa = explode(' ',trim($s));
    
            $sa = array_unique($sa);
            $sa = array_filter($sa);
    
            $i=0;
            foreach($sa as $k => $v)
            {
                $sa[$k] = trim($v);
    
                if(!isset($sa[$k]{2}))
                {
                    unset($sa[$k]);
                }
                else
                {
                    $keywords[] = $sa[$k];
    
                    if($i>3) break;
                    $i++;
                }
            }
    
            if($count = count($sa) > 1) // more than one string
            {
                foreach($sa as $v)
                {
                    $v2 = IB_Core_TextFormat::getInstance($v)->replaceAccents()->getText();
    
                    $w?$w.=' && ': $w='';
    
                    $w.= " (firstname LIKE '%%$v%%' || lastname LIKE '%%$v%%' || description LIKE '%%$v%%' || email LIKE '%%$v%%' ||".
                         "  firstname LIKE '%%$v2%%' || lastname LIKE '%%$v%%' || description LIKE '%%$v2%%' || email LIKE '%%$v2%%')";
                }
            }
            else // one string search
            {
                $v2 = IB_Core_TextFormat::getInstance($s)->replaceAccents()->getText();
    
                $w?$w.=' && ':$w='';
    
                $w.= " (firstname LIKE '%%$s%%' || lastname LIKE '%%$v%%' || description LIKE '%%$s%%' || email LIKE '%%$s%%' ||".
                     "  firstname LIKE '%%$v2%%' || lastname LIKE '%%$v%%' || description LIKE '%%$v2%%' || email LIKE '%%$v2%%')";
            }
        }
        return $w.$opt['order'];
    }
    
    function get($options,$number=0,$offset=0,$select='*')
    {
        $d = $this->select('user',$select,$this->buildQuery($options),$number,$offset);
        
        if($d && $number == 1) return $d[0];
        
        return $d;
    }
    function getTotal($options = array())
    {
        return $this->count("SELECT id FROM user WHERE ".$this->buildQuery($options));
    }
    
    function deleteUser($id)
    {
        $id = (int) $id;
        
        if(!$this->isAdmin() || empty($id)) return;
        
        $this->delete('user', "id = $id");
        $this->delete('user_online', "user_id = $id");
    }
}
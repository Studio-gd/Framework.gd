<?php
Class IB_UserConnect extends IB_DB
{
    static $s = false;
    static function getInstance(){if(!self::$s){self::$s = new IB_UserConnect();}return self::$s;}
    
    /*
    function recover($email)
    {
        if($username = $this->selectOne('user','username',"email = '$email'"))
        {
            $key = md5(rand(0,99).$username.now().SALT.$email).date("si");
            
            $this->insert('recover',array
            (
                'id'         => $key,
                'created_at' => now(),
                'email'      => $email,
                'username'   => $username
            ));
            
            Email_User::recover($username, $key);
            
            return true;
        }
        return false;
    }
    function changePassword()
    {
        $username = Clean::username($_POST['username']);
        $key      = Clean::string($_POST['key']);

        if($this->selectOne('recover','id',"id = '$key' && username = '$username'"))
        {
            $newPassword = $this->processPassword($_POST['new_password'],$username);

            $changes = array('sha_pwd' => $newPassword['sha1'], 'salt' => $newPassword['salt']);

            $this->update('user',$changes,"username = '$username'");
            
            $this->delete('recover',"username = '$username' AND id = '$key'");

            return $this->login($username, $_POST['new_password']);
        }
        return false;
    }
    */
    
    function login($email, $password)
    {
        $email = Clean::email($email);
        
        if(empty($email)) return false;
        
        if($r = $this->select('user',"id,salt,sha_pwd,disabled","email='$email'",1))
        {
            $user_info = $r[0];
        }
        if(!isset($user_info) || $user_info['disabled']=='1') return false;
        
        $user_id = (int) $user_info['id'];
        
        if(sha1($user_info['salt'].$email.trim($password)) === $user_info['sha_pwd'])
        {
            $ip = IB_Core_Tools::getIp();
            
            // if last hash is older than 10 days -> we remove it !
            $scope = relativeDate('-10 days');
            if($this->selectOne('connected','hash',"user_id = $user_id && inserted < '$scope'")) 
            {
                $this->delete('connected',"user_id = $user_id");
            }
            
            $lastIp = $this->selectOne('connected','ip',"user_id = $user_id");
            
            
            if($lastIp && $lastIp === $ip) // allow multiple loggedIn for same account for the same IP (with different browser for ex.)
            {
                // if the user try to log in with the same ip we don't recreate a hash, just return last one
                // that allow a user to login on different computer/browser that use the same provider
                $hash = $this->selectOne('connected','hash',"user_id = $user_id");
            }
            else
            {
                $hash = rand(0,999).rand(0,999).date('ds').rand(0,999).substr($email,rand(0,1),rand(1,2));
                $this->delete('connected',"user_id = $user_id");
                $this->query("INSERT INTO connected VALUES($user_id, '".now()."', '$hash', '$ip')");
            }

            setcookie('i', base64_encode($email.'%'.crypt($email, SALT).'%'.$user_id.'%'.$hash), time()+1000000, '/');

            return true;
        }
        
        $this->logAttempt($email);
        
        return false;
    }
    /*
    function isUsernameExist($username)
    {
        $username = Clean::username($username);

        return $this->selectOne('user','username',"username='$username'");
    }
    */
    function isEmailExist($email)
    {
        return $this->selectOne('user','email',"email = '$email'");
    }

    function processPassword($password, $email)
    {
        $data['salt'] = md5(rand(100000,999999).SALT);
        $data['sha1'] = sha1($data['salt'].$email.trim($password));
        
        return $data;
    }
    
    function add()
    {
        if(!SIGNUP_OPEN)
        {
            return false;
        }
        
        $email = Clean::email($_POST['email']);
        
        $dataPass = $this->processPassword($_POST['password'], $email);
        
        $this->insert('user', array
        (
            'sha_pwd'   => $dataPass['sha1'],
            'salt'      => $dataPass['salt'],
            'email'     => $email,
            'join_date' => now(),
            'ip'        => IB_Core_Tools::getIp(),
            'active'    => 1,
            'admin'     => 1,
            'disabled'  => 0,
        ));

        $this->login($email, $_POST['password']);

        Email_User::register($email, $_POST['password']);
    }
    
    
    function logAttempt($email)
    {
        $ip = IB_Core_Tools::getIp();
        
        $this->query("INSERT INTO connect_log
        (
            `username`,
            `logged`,
            `ip`
        )
        VALUES
        (
            '$email',
            '".now()."',
            '$ip'
        )");
        
        if($nb = $this->countAttempt($email, $ip))
        {
                if($nb <  4) $nb =  0;
            elseif($nb <  7) $nb =  1;
            elseif($nb < 13) $nb =  2;
            elseif($nb < 19) $nb =  3;
            elseif($nb < 25) $nb =  4;
            elseif($nb < 30) $nb =  6;
            elseif($nb < 40) $nb =  8;
            elseif($nb < 60) $nb =  9;
            elseif($nb < 80) $nb = 12;
            elseif($nb < 95) $nb = 14;
            elseif($nb <100) $nb = 16;
            elseif($nb <120) $nb = 18;
            elseif($nb <130) $nb = 19;
            elseif($nb <140) $nb = 21;
            elseif($nb <160) $nb = 26;
            else $nb = 30;

            sleep($nb*2);
        }
    }
    
    function countAttempt($email, $ip)
    {
        $this->cleanAttempt();
        
        $date = relativeDate('-8 minutes');
        
        if($r = $this->select('connect_log','logged',"email = '$email' && ip = '$ip' && logged > '$date'"))
        {
            return count($r);
        }
        return 0;
    }
    function cleanAttempt()
    {
        $date = relativeDate('-2 days');
        
        $this->delete('connect_log',"logged < '$date'");
    }
    /*
    function countAccountCreatedFromIp()
    {
        $date = dateShift(date("Y-m-d"),'-1 day');
        
        $r = $this->count("SELECT id FROM user WHERE ip = '".IB_Core_Tools::getIp()."' && join_date > '$date'",1);
        
        if($r > 7) // more than 7 accounts created today
        {
            return true;
        }
        return false;
    }
    */
}
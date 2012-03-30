<?php
Class IB_Lang extends IB_DB
{
    static $s = false;
    static $l = false;    
    static function getInstance(){if(!self::$s){self::$s = new IB_Lang();}return self::$s;}
        
    function langs()
    {
        return $this->select('lang','id,label','activate = 1',0,0,10000);
    }
    function allLangs()
    {
        return $this->select('lang','id,label',0,0,400);
    }
    function getLabel($id)
    {
        return $this->selectOne('lang','label',"id = '$id'",10000);
    }
    function isActivate($id)
    {
        return $this->selectOne('lang','activate',"id = '$id'",4000);
    }
    function addLang()
    {
        $id = str($_POST['id']);
        $code = str($_POST['code']);
        $label = $_POST['label'];

        $this->query("INSERT INTO lang (id,code,label) VALUES('$id','$code','$label')");
        
        $this->query("create table `lang_$id` (
        `ref_id` int(9) unsigned not null,
        `str` text character set utf8 collate utf8_general_ci not null,
        unique (`ref_id`)
        ) engine=innodb default character set utf8;");
        
        $d = $this->fetch("SELECT id FROM lang_ref");
        
        foreach($d as $v)
        {
            $this->query("INSERT INTO lang_$id (ref_id) VALUES(".$v['id'].")");
        }
    }
    
    function __($str,$user_id=false,$lang=false,$reloadCache=false)
    {
        if(!$lang) $lang=$this->getLang($user_id);

        if($lang==='en' || $lang==='gb') return $str;
                
        $strTmp = mysql_real_escape_string($str);
                
        $t = $this->selectOne("lang_$lang lt, lang_ref lr",'lt.str', "lr.str = '$strTmp' && lr.id = lt.ref_id",10000,$reloadCache);
        
        if(!$t) $t = $str;
        return $t;
    }
    
    function setLang($lang)
    {
        if(empty($lang)) return $this->getLang();

        $lang = str($lang);
        
        if($lang==='us' || $lang==='gb') $lang='en';

        setcookie('lang', $lang, time()+1000000, '/');
        
        if($user_id = reader())
        {
            $this->query("UPDATE user SET language = '$lang' WHERE id = ".$user_id);
            return $this->getLang($user_id, true);
        }
        
        return self::$l = $lang;
    }

    function getLang($user_id=false, $reloadCache = false)
    {
        if(!MULTI_LANGUE && DEFAULT_LANG)
        {
            return DEFAULT_LANG;
        }

        if(!$user_id) //return current user language
        {
            // get object var if exist
            if(self::$l) return self::$l;
        
            // get cookie var if exist
            if(!empty($_COOKIE['lang'])) return self::$l = $_COOKIE['lang'];

            // if user is logged get in base if exist
            if(isLoggedIn())
            {
                if($lang=$this->selectOne('user','language',"id = ".reader(),10000,$reloadCache))
                {
                    setcookie('lang', $lang, time()+1000000, '/');

                    return self::$l=$lang;
                }
            }
            
            // user nav lang
            if(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
            {
                $navLang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
                if($this->isActivate($navLang))
                {
                    setcookie('lang', $navLang, time()+1000000, '/');
                    
                    return self::$l=$navLang;
                }
            }
        }
        elseif($lang=$this->selectOne('user','language',"id = ".intval($user_id),10000,$reloadCache))
        {
            return $lang;
        }
        return DEFAULT_LANG;
    }
    
    function get($number=0,$offset=0)
    {
        return $this->select('lang_ref','*',false,$number,$offset);
    }
    function getTotal()
    {        
        return $this->count("SELECT id FROM lang_ref");
    }
    
    function getNew($number=0,$offset=0,$lang)
    {
        return $this->fetch("SELECT lr.* FROM lang_ref lr, lang_$lang ll WHERE (ll.str LIKE '' OR ll.str = lr.str) AND lr.id = ll.ref_id GROUP BY lr.id ORDER BY lr.id DESC",$number,$offset);
    }
    function countNew($lang)
    {
        return $this->count("SELECT lr.id FROM lang_ref lr, lang_$lang ll WHERE (ll.str LIKE '' OR ll.str = lr.str) AND lr.id = ll.ref_id GROUP BY lr.id");
    }
    
    function countDone($lang)
    {
        return $this->count("SELECT ref_id FROM lang_$lang WHERE str != ''");
    }
    
    function getEmpty($number=0,$offset=0,$lang='ref')
    {
        return $this->fetch("SELECT * FROM lang_$lang WHERE str = ''",$number,$offset);
    }
    function countEmpty($lang='ref',$s='id')
    {
        if($lang!=='ref') $s = 'ref_id';
        
        return $this->count("SELECT $s FROM lang_$lang WHERE str = ''");
    }
    
    function saveTranslation()
    {
        $lang = str($_POST['lang_translate']);
        
        if(!isAdmin() && !$this->isTranslator(reader(),$lang)) return false;
        
        if(empty($lang)) return false;

        $ids = explode(',',$_POST['ids']);
        
        foreach($ids as $id)
        {
            $id = (int) $id;
            
            if(!empty($id) && isset($_POST['str_'.$id]))
            {
                $str = $_POST['str_'.$id];
                
                if(!$this->isRefKey($id,$str))
                {
                    if($this->keyExist($id,$lang))
                    {
                        $this->query("UPDATE lang_$lang SET str = '$str' WHERE ref_id = $id");
                        
                        $this->__($str,false,$lang,true); // force reload cache
                    }
                    else
                    {
                        $this->query("INSERT INTO lang_$lang VALUES($id,'$str')");
                    }
                }
            }
        }
    }
    function keyExist($id,$lang)
    {
        $id = (int) $id;
        return $this->fetchOne("SELECT ref_id FROM lang_$lang WHERE ref_id = $id");
    }
    function isRefKey($id,$str)
    {
        $id = (int) $id;
        return $this->fetchOne("SELECT id FROM lang_ref WHERE id = $id && str = '$str'");
    }
    function getKey($id)
    {
        $id = (int) $id;
        return $this->fetchOne("SELECT str FROM lang_ref WHERE id = $id");
    }
    function getLangKey($id,$lang)
    {
        $id = (int) $id;
        return $this->fetchOne("SELECT str FROM lang_$lang WHERE ref_id = $id");
    }
    function editKey($id)
    {
        $id = (int) $id;
        if($str = $_POST['str'])
        {
            $this->query("UPDATE lang_ref SET str = '$str' WHERE id = $id");
        }
    }
    function addKey()
    {
        if(!empty($_POST['str']) && IB_User::getInstance()->isAdmin())
        {
            $str = $_POST['str'];
            
            if($this->fetchOne("SELECT id FROM lang_ref WHERE str = '$str'"))
            {
                // already exist
                return false;
            }
            
            $this->query("INSERT INTO lang_ref (str) VALUES('$str')");
            
            $id = $this->lastInsertId();
            
            $langs = $this->allLangs();

            foreach($langs as $v)
            {
                if($v['id']!=='en') $this->query("INSERT INTO lang_".$v['id']." (ref_id) VALUES($id)");
            }
        }
    }
    
    function searchKey($number=0,$offset=0,$lang,$search)
    {
        return $this->fetch("SELECT lr.* FROM lang_ref lr, lang_$lang ll WHERE lr.str LIKE '%$search%' OR (ll.str LIKE '%$search%' AND lr.id = ll.ref_id) GROUP BY lr.id",$number,$offset);
    }
    function countSearchKey($lang,$search)
    {
        return $this->count("SELECT lr.id FROM lang_ref lr, lang_$lang ll WHERE lr.str LIKE '%$search%' OR (ll.str LIKE '%$search%' AND lr.id = ll.ref_id) GROUP BY lr.id",500);
    }
    
    function deleteKey($id)
    {
        $id = (int) $id;
        
        if(empty($id) || !isAdmin()) return;
        
        $this->query("DELETE FROM lang_ref WHERE id = $id");
        
        $langs = $this->allLangs();
        
        foreach($langs as $v)
        {
            if($v['id']!=='en') $this->query("DELETE FROM lang_".$v['id']." WHERE ref_id = $id");
        }
    }
    
    function addTranslator()
    {
        if(!IB_User::getInstance()->isAdmin()) return;
        
        $username = $_POST['username'];
        $lang = str($_POST['lang']);
        
        if($user_id = IB_Users::getInstance()->getUserIdFromName($username))
        {
            $this->query("INSERT INTO translator VALUES($user_id,'$lang')");
        }
    }
    function isTranslator($user_id, $lang_id)
    {
        $user_id = (int) $user_id;
        $lang_id = str($lang_id);
        
        if($user_id === 0 || empty($lang_id)) return false;
        
        return $this->fetchOne("SELECT user_id FROM translator WHERE user_id = $user_id && lang_id = '$lang_id'");
    }
    function getUserLang($user_id)
    {
        return $this->fetch("SELECT l.id,l.label FROM lang l, translator t WHERE t.user_id = $user_id && l.id = t.lang_id");
    }
}
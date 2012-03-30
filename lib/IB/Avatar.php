<?php
class IB_Avatar extends IB_DB
{
    static $s = false;
    static function getInstance(){if(!self::$s){self::$s=new IB_Avatar();}return self::$s;}
    
    function add($id, $object = 'user')
    {
        if(!isLoggedIn()) return;
        
        $id = (int) $id;
        
        $object = Clean::string($object);
        
        if(empty($id) || empty($object) || empty($_FILES['avatar'])) return false;
        
        
        $path = PATH.AVATAR_FOLDER.$object.'/';
        
        if(!file_exists($path)) // create object folder if necessary
        {
            mkdir($path, 0755, true);
        }
        
        $ext = strtolower(getExtension($_FILES['avatar']['name']));
        
        $filename = "$id.$ext";
        
        if(IB_File::getInstance()->upload($_FILES['avatar'], $filename, $path))
        {
            $Image = IB_Image::getInstance();
            
            
            if($ext !== 'png') //convert to png
            {
                $Image->convert($path.$filename, $path.$id.'.png');
                
                $filename = $id.'.png';
            }
            
            $sizes = $this->getSize();
            
            foreach($sizes as $size)
            {
                $Image->niceCrop("$path$filename", "$path$id-$size", $size, $size);
            }
            
            
            if($version = $this->selectOne('avatar','version',"object='$object' && object_id=$id"))
            {
                $version++;
                $this->update('avatar',array('version'=>$version),"object='$object' && object_id=$id");
            }
            else
            {
                $this->insert('avatar',array
                (
                    'object'    => $object,
                    'object_id' => $id,
                    'user_id'   => reader(),
                    'version'   => 1
                ));
            }
            return $path.$filename;
        }
        
        
        return false;
    }
    
    function get($id, $object = 'user', $size = '', $email = false, $fullpath = false)
    {
        $url = $fullpath ? URL : '/';
        
        if($version = $this->selectOne('avatar','version',"object='$object' && object_id=$id"))
        {
            $size = $size==='' ? '' : '-'.$size;
            
            return $url.AVATAR_FOLDER.$object.'/'.$id.$size.'.png?'.$version;
        }
        else if($object === 'user')
        {
            $email = $email ? $email : IB_User::getInstance()->getEmail($id);
            
            if($gravatar = $this->gravatar($email,$size))
            {
                return $gravatar;
            }
        }
        return $url.'img/avatar.png';
    }
    
    function gravatar($email, $size='20')
    {
        $gravatar = "http://www.gravatar.com/avatar/".md5(strtolower($email)).'?s='.$size.'&d='.urlencode(URL.'img/avatar.png');
        /*
        $headers = get_headers($gravatar,1);
        
        if(is_array($headers['Content-Type']))
        {
            return false;
        }
        */
        return $gravatar;
    }
    

    function remove($id,$object)
    {
        if(!isLoggedIn()) return;
        
        $this->delete('avatar',"object='$object' && object_id=$id");
        
        $path = PATH.AVATAR_FOLDER.$object.'/';
        
        $sizes = $this->getSize();
        
        foreach($sizes as $size)
        {
            $fullpath = $path.$id.'-'.$size.'.png';
            
            if(file_exists($fullpath)) unlink($fullpath);
        }
        
        unlink($path.$id.'.png');
    }
    
    function getSize()
    {
        return explode(',',AVATAR_SIZE);
    }
    
}
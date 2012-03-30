<?php
class IB_Core_Cache
{
    function set($key, $data, $timeout = 3000)
    {
        $dirname = $this->getFolder($key);
        
        $dir = PATH.FILE_CACHE_FOLDER.$dirname.'/';
        
        $f = $dir.str_replace($dirname.'_','',$key);
        
        if(!file_exists($dir)) mkdir($dir, 0777, true);
        
        $expire = time()+$timeout;
        
        return file_put_contents($f, $expire."\n".serialize($data), LOCK_EX);
    }

    function get($key)
    {
        if(!FILE_CACHE) return false;
        
        $dir = $this->getFolder($key);
        
        $f = PATH.FILE_CACHE_FOLDER.$dir.'/'.str_replace($dir.'_','',$key);
        
        if(!file_exists($f)) return false;
        
        list($timeout, $data) = explode("\n", file_get_contents($f), 2);
        
        if($timeout < time())
        {
            @unlink($f);
            return false;
        }
        return unserialize($data);
    }

    function getFolder($key)
    {
        $folders = explode('_', $key, 2);
        
        return $folders[0];
    }
    
    function remove($key) // remove a file
    {
        $dir = $this->getFolder($key);
        
        return @unlink(PATH.FILE_CACHE_FOLDER.$dir.'/'.str_replace($dir.'_','',$key));
    }
    
    function clean($oldOnly = false)
    {
        if(!$oldOnly)
        {
            IB_File::getInstance()->deleteFiles(PATH.FILE_CACHE_FOLDER);
        }
        else
        {
            //TODO
        }
        
    }
}

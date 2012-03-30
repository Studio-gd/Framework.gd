<?php
Class IB_UserConfig extends IB_DB
{
    static function getInstance(){return new IB_UserConfig();}
    
    function add($user_id,$label,$value)
    {
        $user_id = (int) $user_id;
        
        if(empty($value) || empty($user_id)) return false;
        
        if($this->getValue($user_id,$label))
        {
            $this->del($user_id,$label);
        }
        $this->query("INSERT INTO user_config VALUES($user_id,'$label','$value')");
    }
    
    function get($user_id)
    {
        $user_id = (int) $user_id;
        return $this->select('user_config','label,value',"user_id = $user_id");
    }
    
    function getValue($user_id,$label)
    {
        $user_id = (int) $user_id;
        return $this->selectOne('user_config','value',"user_id = $user_id && label = '$label'");
    }
    
    function del($user_id,$label)
    {
        $user_id = (int) $user_id;
        $this->delete('user_config',"user_id = $user_id && label = '$label'");
    }
}
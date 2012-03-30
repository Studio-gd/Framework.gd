<?php Class IB_Error
{
    const NO_USER_SELECTED = "No user selected";
    const USER_NOT_EXIST   = "User %s doesn't exist";
    const NO_RIGHT         = "You don't have the right (%s)";
    const DEFAULT_ERROR    = "Sorry, something wrong happened.";
    
    static function log()
    {
        $IB = IB::getInstance();
        
        $error = strip_tags($IB->get('error'));
        
        IB_File::getInstance()->appendToFile(LOG_ERROR_PATH, now().
        
        "\t".$IB->query().
        "\t".'userId:'.reader().'('.IB_Core_Tools::getIp().') '.
        "\t".$error."\n"
        );
    }
    
    static function cleanLog()
    {
        IB_File::getInstance()->writeFile(LOG_ERROR_PATH, '');
    }
}
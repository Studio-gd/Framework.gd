<?php Class Controller_User extends IB_Controller
{
    static function i(){return new Controller_User();}
    function index($arg)
    {
        if(!isAdmin()) return;

        if(!empty($arg[0]) && $arg[0] !== 'search' && $username = $this->checkUsername($arg[0]))
        {
            $this->set('username',$username)
                 ->set('title',__('Profile').TITLE_SEPARATOR.$username)
                 ->view('admin/menu')
                 ->view('user/profile');
        }
        else
        {
            if(!empty($arg[0]))
            {
                if(!empty($arg[1]) && $arg[0] === 'search')
                {
                    $this->set('search',urlDecode(Clean::string($arg[1])));
                }
                elseif(!empty($arg[1]) && $arg[0] === 'p')
                {
                    $this->set('nbPage',intval($arg[1]));
                }
                else
                {
                    $this->set('nbPage',intval($arg[0]));
                }
            }
            
            $this->set('title',__('Users').TITLE_SEPARATOR.__('List'))
                 ->view('admin/menu')
                 ->view('user/list');
        }
    }
    function edit($arg)
    {
        if($username = $this->checkUsername($arg[0]))
        {
            $this->set('username',$username)
                 ->set('title',__('Edit').TITLE_SEPARATOR.$username)
                 ->view('admin/menu')
                 ->view('user/form/edit');
        }
    }
    function recover($arg)
    {
        if($username = $this->checkUsername($arg[0]))
        {
            $this->set('username',$username)
                 ->set('title',__('Recover password').TITLE_SEPARATOR.$username)
                 ->set('key',$arg[1])
                 ->view('admin/menu')
                 ->view('user/form/recover');
        }
    }
    
    function checkUsername($username)
    {
        if(!empty($username))
        {
            return Clean::username($username);
        }
        $this->set('error', __(IB_ERROR::NO_USER_SELECTED));
        return false;
    }

}
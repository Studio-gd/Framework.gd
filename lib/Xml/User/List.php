<?php
class Xml_User_List extends IB_Xml
{
    static function getInstance(){ return new Xml_User_List();}
    function preRender()
    {
        $data = '';
        
        $pager = new IB_Pager(USER_PER_PAGE);
        
        $nbr    = $pager->number;
        $offset = $pager->offset;

        $user = IB_User::getInstance();
        
        $search = $this->get('search');
        
        $dataUser = $user->get(array('search'=>$search),$nbr,$offset);

        if($dataUser)
        {
            foreach($dataUser as $v)
            {
                $data.= '
  <user
';

        foreach($v as $key => $value)
        {
            $data.=  '    '.$key . '="' . Clean::xmlentities($value) . '" 
';
        }
        
        $data.= '  />';
            }
        }
        
        return $data;
    }
}
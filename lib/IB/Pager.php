<?php
Class IB_Pager extends IB
{
    static $number = 0;
    static $offset = 0;
    static $total  = 0;
    static $pages  = 0;
    static $next   = 0;
    static $prev   = 0;
    
    function __construct($number, $total = 0)
    {
        $this->number = $number;
        $this->total  = $total;
    
        $page = $this->get('nbPage');
        if($page == 0)
        {
            $page = 1;
            $this->set('nbPage', 1);
        }
        $this->offset = ($page * $this->number) - $this->number;
        
        $this->pages = ceil($this->total / $this->number);

        if($this->total > $this->number)
        {
            if($page > 1)
            {
                $this->prev = $page - 1;

                $this->set('prev', $this->prev);
            }
            if($page < $this->pages)
            {
                $this->next = $page + 1;

                $this->set('next', $this->next);
            }
        }

        $this->set('number', $this->number);
        $this->set('pages', $this->pages);

        if($this->total != 0)
        {
            $this->set('total', $this->total);
        }

        return $this;
    }
}
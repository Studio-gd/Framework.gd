<?php

$total = $P->get('total');
$data  = $prev = $prevOne = '';
$nbr   = $P->get('number');
$page  = $P->get('nbPage');
$query = '/'.$P->query().'p/';
$pages = $P->get('pages');
$nextPage = $P->get('next');
$prevPage = $P->get('prev');

if($total > $nbr)
{
    if($prevPage)
    {
        $prevOne = '<li><a href="'.$query.$prevPage.'" title="'.__('Previous').'">«</a></li>';

        $i = 1;
        while($i < 9)
        {
            if($page > $i)
            {
                $prev = '<li><a href="'.$query.$prevPage.'">'.$prevPage.'</a></li>'.$prev;
                --$prevPage;
            }
            $i++;
        }
    }

    $data.= '<div class="pagination"><ul>'.$prevOne.$prev.'<li class="active"><a>'.$page.'</a></li>';
    
    if($nextPage)
    {
        $i = 0;
        while($i < 9)
        {
            if($page < ceil(($total-$nbr*$i)/$nbr))
            {
                $data.= '<li><a href="'.$query.$nextPage.'">'.$nextPage.'</a></li>';
                ++$nextPage;
            }
            $i++;
        }
        
        $data.= '<li><a href="'.$query.$P->get('next').'" title="'.__('Next').'">»</a></li>';
    }
    
    $data.='</ul></div>';
}

echo $data;
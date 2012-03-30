<?php
Class IB_Core_Date
{
    static function getRelativeDate($date)
    {
        $date_time=strtotime($date);
        $in_seconds=$date_time;
        $diff=time()-$in_seconds;
        $months=floor($diff/2419200);
        $diff-=$months*2419200;
        $weeks=floor($diff/604800);
        $diff-=$weeks*604800;
        $days=floor($diff/86400);
        $diff-=$days*86400;
        $hours=floor($diff/3600);
        $diff-=$hours*3600;
        $minutes=floor($diff/60);
        $diff-=$minutes*60;
        $seconds=$diff;
        $today=date("Y").str_pad(date("z"),3,"0",STR_PAD_LEFT);
        $date2=strtotime('-1 day',$date_time);
        $yesterday=strftime("%Y",$date_time).str_pad(strftime("%j",$date_time),3,"0",STR_PAD_LEFT);
        if(($today==$yesterday+1)&&($hours>12)){$days=1;}
        elseif(($today==$yesterday+1)&&($hours>12)){$days=2;}
        if($months>0){$relative_date = sprintf(_s("%d month ago","%d months ago",$months),$months);}
        elseif($weeks>0){$relative_date = sprintf(_s("%d week ago","%d weeks ago",$weeks),$weeks);}
        elseif($days==1){$relative_date=__('yesterday');}
        elseif($days==2){$relative_date=__("2 days ago");}
        elseif($days>2){$relative_date=sprintf(_s("%d day ago","%d days ago",$days),$days);}
        elseif($hours>0){$relative_date=sprintf(_s("%d hour ago","%d hours ago",$hours),$hours);}
        elseif($minutes>0){$relative_date=sprintf(_s("%d minute ago","%d minutes ago",$minutes),$minutes);}
        else{$relative_date=sprintf(_s("%d second ago","%d seconds ago", $seconds),$seconds);}
        return $relative_date;
    }



    static function addArrayTime($array)
    {
        $total=0;
        
        foreach($array as $v)
        {
            $vTmp = explode(':',$v);
            
            if(!isset($v{1})) continue;
            
            if(!isset($v{7}))
            {
                $total+=$vTmp[0]*60;
                $total+=$vTmp[1];
            }
            else
            {
                $total+=$vTmp[0]*3600;
                $total+=$vTmp[1]*60;
                $total+=$vTmp[2];
            }
        }
        return self::int2array($total);
    }

    static function int2array($seconds)
    {
        $periods = array(
         //'years' => 31556926, //'months' => 2629743, //'weeks' => 604800, //'days' => 86400,
         'hours'   => 3600,
         'minutes' => 60,
         'seconds' => 1
        );
        $values = '';

        foreach($periods as $period => $value)
        {
            $count = floor($seconds / $value);

            if($count == 0 && $period!=='seconds') continue;

            if($count != 0 || $period==='seconds')
            {
                if($count<10 && ($period==='minutes' || $period==='seconds')) $count = "0$count";
                
                $values.= $count; if($period!=='seconds') $values.= ':';
            }
            $seconds = $seconds % $value;
        }
        return $values;
    }
}
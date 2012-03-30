<?php
$logs = IB_File::getInstance()->readFile(LOG_ERROR_PATH);

if(empty($logs)) $logs = 'No log';

$url = URL.UPLOAD_FOLDER.LOG_ERROR;

$data ='<a onclick="admin.cleanLog()">clean Log</a><br/><br/>';

$data.= '<div id="logs"><a href="'.$url.'">'.$url.' :</a><br /><br /><pre>'.$logs.'</pre></div>';

echo $data;
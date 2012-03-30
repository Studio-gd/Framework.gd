<?php

$data = '<pre style="float: left;"><a class="remove" onclick="$(this).parent().remove()">remove</a><br/>';
$data.= 'Peak mem: '.(int)(memory_get_peak_usage()/1024).'kB'."\n";
$data.= 'Exec time: '.sprintf('%.5f', (microtime(true) - TIME)).'s'."\n";
$included_files = get_included_files();
sort($included_files);
$data.= 'Included files : '.count($included_files)." :\n\n";

foreach ($included_files as $filename) {
    $data.= htmlspecialchars($filename)."\n";
}

$db = IB_DB::Connect();

$queries = $db->queries();

$data.= "\n DB query #".count($queries)." :\n";

foreach ($queries as $q)
{
    $data.= htmlspecialchars($q)."\n";
}

$data.= '</pre>';

echo div('admin_Debug',$data);
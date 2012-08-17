<?php
$mc = mc();

$stats = $mc->getStats();

$data = 'stats : <br /><pre>'.print_r($stats,true).'<pre>';

$data.= "<table border='1'>";

$data.= "<tr><td>Memcache Server version:</td><td> ".$stats["version"]."</td></tr>";
$data.= "<tr><td>Process id of this server process </td><td>".$stats["pid"]."</td></tr>";
$data.= "<tr><td>Number of seconds this server has been running </td><td>".$stats["uptime"]."</td></tr>";
$data.= "<tr><td>Accumulated user time for this process </td><td>".$stats["rusage_user"]." seconds</td></tr>";
$data.= "<tr><td>Accumulated system time for this process </td><td>".$stats["rusage_system"]." seconds</td></tr>";
$data.= "<tr><td>Total number of items stored by this server ever since it started </td><td>".$stats["total_items"]."</td></tr>";
$data.= "<tr><td>Number of open connections </td><td>".$stats["curr_connections"]."</td></tr>";
$data.= "<tr><td>Total number of connections opened since the server started running </td><td>".$stats["total_connections"]."</td></tr>";
$data.= "<tr><td>Number of connection structures allocated by the server </td><td>".$stats["connection_structures"]."</td></tr>";
$data.= "<tr><td>Cumulative number of retrieval requests </td><td>".$stats["cmd_get"]."</td></tr>";
$data.= "<tr><td>Cumulative number of storage requests </td><td>".$stats["cmd_set"]."</td></tr>";

$percCacheHit=((real)$stats["get_hits"]/ (real)$stats["cmd_get"] *100);
$percCacheHit=round($percCacheHit,3);
$percCacheMiss=100-$percCacheHit;

$data.= "<tr><td>Number of keys that have been requested and found present </td><td>".$stats["get_hits"]." ($percCacheHit%)</td></tr>";
$data.= "<tr><td>Number of items that have been requested and not found </td><td>".$stats["get_misses"]."($percCacheMiss%)</td></tr>";

$MBRead= (real)$stats["bytes_read"]/(1024*1024);

$data.= "<tr><td>Total number of bytes read by this server from network </td><td>".$MBRead." Mega Bytes</td></tr>";
$MBWrite=(real) $stats["bytes_written"]/(1024*1024) ;
$data.= "<tr><td>Total number of bytes sent by this server to network </td><td>".$MBWrite." Mega Bytes</td></tr>";
$MBSize=(real) $stats["limit_maxbytes"]/(1024*1024) ;
$data.= "<tr><td>Number of bytes this server is allowed to use for storage.</td><td>".$MBSize." Mega Bytes</td></tr>";
$data.= "<tr><td>Number of valid items removed from cache to free memory for new items.</td><td>".$stats["evictions"]."</td></tr>";

$data.= "</table>";

echo $data;
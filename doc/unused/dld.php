<?php 

exit; ## force download file ##

header('Content-Type: text/html; charset=utf-8');
require dirname(__FILE__).'.php';
require 'functions.php';
require 'conf.php';
require 'dispatcher.php';

$file_id = (int) $_GET['id'];

$v = $file->getFileById($file_id); $v=$v[0];

$name = str_replace('@','a',$file->getDownloadFilename($file_id).'.mp3');

$src_path = PATH.UPLOAD_FOLDER.$v['filename'].'.mp3';

$post_id = $file->getPostIdByFileId($file_id);

if(!$user->isAdmin())
{
    if(!$user->isLoggedIn() || !is_file($src_path) || $file->isDownloadable($post_id)!==1) exit;
}

if($file->isDownloadable($post_id)) // avoid to notify and count dld when admin dld it...
{
    $file->downloadCount($file_id);

    IB_Event::add($user->reader(),$file_id,'download');
}

$filesize = @filesize($src_path);

header("Pragma: public");
header("Expires: Thu, 19 Nov 1982 08:52:00 GMT");
header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
header("Cache-Control: no-store,no-cache,must-revalidate");
header("Cache-Control: private");
header("Content-Transfer-Encoding: binary");
header("Content-Type: audio/x-mpeg,audio/x-mpeg-3,audio/mpeg3");
// line causes the browser's "save as" dialog
header( 'Content-Disposition: attachment; filename="'.$name.'"');
// Length required for Internet Explorer
header("Content-Length: ".urldecode($filesize));

echo readfile($src_path);
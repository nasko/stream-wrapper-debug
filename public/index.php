<?php
require_once 'Bootstrap.php';

stream_wrapper_register('dbfile', '\StreamWrapper\Image');
/*
$fp = fopen('dbfile://do.jpg', 'wb');
fwrite($fp, file_get_contents('./do.jpg'));
fclose($fp);
*/ 
$fp = fopen('dbfile://do.jpg', 'rb');
 
header('Content-type: image/jpeg');
while(!feof($fp)){
    echo fread($fp, 8192);
}
 
fclose($fp);

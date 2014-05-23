<?php
require_once 'Bootstrap.php';

stream_wrapper_register('mydebug', '\StreamWrapper\Debug');

$fp = fopen('mydebug://do.jpg', 'r');

while(!feof($fp)){
    echo fread($fp, 8192) . "\n";
}
 
fclose($fp);

<?php
require_once 'Bootstrap.php';

stream_wrapper_register('my', '\StreamWrapper\Decorator');

$fp = fopen('my://do.jpg', 'r');

while(!feof($fp)){
    echo fread($fp, 8192) . "\n";
}
 
fclose($fp);

<?php
defined('DS') OR
    define('DS', DIRECTORY_SEPARATOR);

require_once __DIR__ . DS . '..' . DS . 'etc'       . DS . 'config.php';
require_once __DIR__ . DS . '..' . DS . 'vendor'    . DS . 'SplClassLoader.php';

$classLoader = new SplClassLoader('StreamWrapper', realpath(__DIR__ . DS . '..' . DS . 'StreamWrapper' . DS . 'src'));
$classLoader->register();
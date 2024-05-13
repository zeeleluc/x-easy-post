<?php
require_once "vendor/autoload.php";

define('LIBRARY_PATH', realpath(dirname(__FILE__)) . DS . 'src' . DS);

set_include_path(
    get_include_path() . PATH_SEPARATOR . LIBRARY_PATH
);

spl_autoload_register(function ($className) {
    if (file_exists(LIBRARY_PATH . str_replace('\\', '/', $className) . '.php')) {
        include LIBRARY_PATH . str_replace('\\', '/', $className) . '.php';
    }
});

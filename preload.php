<?php
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

session_start();

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit;
}

if (isset($_SERVER['REQUEST_URI'])) {
    $isJsonApiCall = str_starts_with($_SERVER['REQUEST_URI'], '/json');
    $isHtmlApiCall = str_starts_with($_SERVER['REQUEST_URI'], '/html');
    if ($isJsonApiCall || $isHtmlApiCall) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET");
    }
}

const DS = DIRECTORY_SEPARATOR;
const ROOT = __DIR__;

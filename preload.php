<?php
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

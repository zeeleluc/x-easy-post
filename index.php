<?php
include_once 'preload.php';
include_once 'autoloader.php';
include_once 'utilities.php';

if (env('ENV') === 'local') {
    error_reporting(E_ALL ^ E_DEPRECATED ^ E_WARNING);
    ini_set('display_errors', 'On');
} else {
    error_reporting(0);
    ini_set('display_errors', 'Off');
}

try {
    // Initialize the application.
    $initialize = new \App\Initialize();

    // Run the action and show the output.
    $initialize->action()->show();

    // Clear any alerts or form errors, so we show them only once.
    $session = new \App\Session();
    $session->destroySession('alert');
    $session->destroySession('formValidatedValues');
    $session->destroySession('formErrors');
} catch (Exception $e) {
    ob_start();
    require(ROOT . DS . 'templates' . DS . 'layouts' . DS . 'error.phtml');
    $errorPage = ob_get_contents();
    ob_end_clean();

    $slack = new \App\Slack();
    $slack->sendErrorMessage($e->getMessage());

    echo $errorPage;
}

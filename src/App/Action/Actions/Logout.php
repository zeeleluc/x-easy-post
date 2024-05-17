<?php
namespace App\Action\Actions;

use App\Action\BaseAction;

class Logout extends BaseAction
{
    public function __construct()
    {
        $this->getSession()->destroySession('loggedIn');
        session_unset();
        session_destroy();

        session_start();
        success('login', 'See you next time.');
    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

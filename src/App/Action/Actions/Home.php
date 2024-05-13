<?php
namespace App\Action\Actions;

use App\Action\BaseAction;

class Home extends BaseAction
{
    public function __construct()
    {
        parent::__construct();

        $this->setLayout('default');
        $this->setView('website/home');
    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

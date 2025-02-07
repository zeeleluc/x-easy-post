<?php
namespace App\Action\Actions;

use App\Action\BaseAction;

class Account extends BaseAction
{
    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        $account = $this->getRequest()->getParam('account');

        $this->getSession()->setSessionAccount($account);

        success('', 'Changed account to ' . $account);

    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

<?php
namespace App\Action\Actions;

use App\Action\BaseAction;

class CheckLogin extends BaseAction
{
    public function __construct()
    {
        if ($this->getSession()->getItem('loggedIn')) {
            echo json_encode([
                'success' => true,
            ]);
            exit;
        }

        echo json_encode([
            'success' => false,
        ]);
        exit;
    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

<?php
namespace App\Action\Actions\Cli;

use App\Action\BaseAction;
use App\Query\AuthIdentifierQuery;

class ClearOldMagicTokens extends BaseAction
{

    private AuthIdentifierQuery $authIdentifierQuery;

    public function __construct()
    {
        $this->authIdentifierQuery = new AuthIdentifierQuery();
    }

    public function run()
    {
        $this->authIdentifierQuery->deleteOldTokens();
    }
}

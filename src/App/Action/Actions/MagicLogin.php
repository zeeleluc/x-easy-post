<?php
namespace App\Action\Actions;

use App\Action\BaseAction;
use App\Models\AuthIdentifier;
use App\Query\AuthIdentifierQuery;

class MagicLogin extends BaseAction
{
    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        $givenServerKey = $this->getRequest()->getParam('server_key');
        $givenAuthIdentifier = $this->getRequest()->getParam('auth_identifier');

        if ($givenServerKey !== env('MAGIC_LOGIN_SERVER_KEY')) {
            abort();
        }

        $authIdentifier = new AuthIdentifier();
        $authIdentifier = $authIdentifier->fromArray([
            'auth_identifier' => $givenAuthIdentifier
        ]);
        $isValid = $authIdentifier->isValid();

        if (!$isValid) {
            abort();
        }

//        $authIdentifier->delete();
//        (new AuthIdentifierQuery())->deleteAllAuthIdentifiers();

        session_regenerate_id(true);
        $this->getSession()->setSession('loggedIn', true);

        success('', 'Welcome magic stranger');

    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

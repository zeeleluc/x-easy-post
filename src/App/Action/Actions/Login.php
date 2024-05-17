<?php
namespace App\Action\Actions;

use App\Action\BaseFormAction;
use App\FormFieldValidator\RegularString;

class Login extends BaseFormAction
{
    public function __construct()
    {
        if ($this->getSession()->getItem('loggedIn')) {
            success('', 'Already logged in');
        }

        parent::__construct('login');

        $this->setLayout('default');
        $this->setView('website/login');

        if ($this->getRequest()->isGet()) {
            $this->performGet();
        } elseif ($this->getRequest()->isPost()) {
            $this->performPost();
        }
    }

    /**
     * @throws \Exception
     */
    protected function performGet(): void
    {
        parent::performGet();
    }

    /**
     * @throws \Exception
     */
    protected function performPost(): void
    {
        $this->validateFormValues([
            new RegularString('username', $this->getRequest()->getPostParam('username')),
            new RegularString('password', $this->getRequest()->getPostParam('password')),
        ]);
    }

    protected function handleForm(): void
    {
        $username = $this->validatedFormValues['username'];
        $password = $this->validatedFormValues['password'];

        if ($username !== env('LOGIN_USERNAME')) {
            abort('login', 'Login failed');
        }

        if (!password_verify($password, env('LOGIN_PASSWORD'))) {
            abort('login', 'Login failed');
        }

        session_regenerate_id(true);
        $this->getSession()->setSession('loggedIn', true);

        success('', 'Welcome ' . env('LOGIN_USERNAME'));
    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

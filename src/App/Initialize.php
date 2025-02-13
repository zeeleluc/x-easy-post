<?php
namespace App;

use App\Action;
use App\Action\Action as AbstractAction;
use App\Action\BaseAction;
use App\Object\BaseObject;
use App\Object\ObjectManager;
use App\Query\AuthIdentifierQuery;
use App\Query\ImageQuery;
use App\Query\PostQuery;
use App\Service\Projects\Projects;

class Initialize extends BaseObject
{
    public function __construct()
    {
        ObjectManager::set(new Request());
        ObjectManager::set(new Session());
        ObjectManager::set(new AbstractAction());
        ObjectManager::set(new PostQuery());
        ObjectManager::set(new ImageQuery());
        ObjectManager::set(new AuthIdentifierQuery());
    }

    public function action(): Initialize
    {
        $this->getAbstractAction()->setAction($this->resolveAction());
        $this->getAbstractAction()->getAction()->run();

        return $this;
    }

    public function show(): void
    {
        $variables = $this->getAbstractAction()->getAction()->getVariables();

        extract($variables);

        ob_start();
        if (false === $this->getAbstractAction()->getAction()->getTemplate()->isTerminal()) {
            require_once ROOT . DS . 'templates' . DS . 'views' . DS . $this->getAbstractAction()->getAction()->getTemplate()->getView()->getViewName() . '.phtml';
        }
        $content = ob_get_contents();
        ob_end_clean();

        if (false === $this->getAbstractAction()->getAction()->getTemplate()->isTerminal()) {
            ob_start();
            require_once ROOT . DS . 'templates' . DS . 'layouts' . DS . $this->getAbstractAction()->getAction()->getTemplate()->getLayout()->getLayoutName() . '.phtml';
            $html = ob_get_contents();
            ob_end_clean();
        } else {
            $html = $content;
        }

        echo $html;
    }

    /**
     * @return BaseAction
     * @throws \Exception
     */
    private function resolveAction(): BaseAction
    {
        $get = $this->getRequest()->get();

        if (is_cli()) {
            return new Action\Actions\Cli();
        }

        if (is_hypeomatic_website()) {
            if (false === isset($get['action']) || (true === isset($get['action']) && '' === $get['action'])) {
                return new Action\Actions\Hypeomatic\Home();
            }

            foreach (Projects::getAllPublic() as $projectSlug => $project) {
                if ($get['action'] === str_replace('_', '', $projectSlug)) {
                    return new Action\Actions\Hypeomatic\Home();
                }
            }

            if ($get['action'] === 'load-dynamic-form-elements') {
                return new Action\Actions\Hypeomatic\LoadDynamicFormElements();
            }

            if ($get['action'] === 'image') {
                return new Action\Actions\Hypeomatic\ShowImage();
            }

            if ($get['action'] === 'redo') {
                return new Action\Actions\Hypeomatic\Redo();
            }
        }

        if (false === isset($get['action']) || (true === isset($get['action']) && '' === $get['action'])) {
            return new Action\Actions\Home();
        }

        if ($get['action'] === 'delete-scheduled-post') {
            return new Action\Actions\DeleteScheduledPost();
        }

        if ($get['action'] === 'account') {
            return new Action\Actions\Account();
        }

        if ($get['action'] === 'retry-post') {
            return new Action\Actions\RetryPost();
        }

        if ($get['action'] === 'redo-image') {
            return new Action\Actions\RedoImage();
        }

        if ($get['action'] === 'copy-post') {
            return new Action\Actions\CopyPost();
        }

        if ($get['action'] === 'magic-login') {
            return new Action\Actions\MagicLogin();
        }

        if ($get['action'] === 'login') {
            return new Action\Actions\Login();
        }

        if ($get['action'] === 'logout') {
            return new Action\Actions\Logout();
        }

        if ($get['action'] === 'check-login') {
            return new Action\Actions\CheckLogin();
        }

        if ($get['action'] === 'load-dynamic-form-elements') {
            return new Action\Actions\LoadDynamicFormElements();
        }

        throw new \Exception('Page not found.');
    }

}

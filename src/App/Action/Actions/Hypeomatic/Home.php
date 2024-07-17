<?php
namespace App\Action\Actions\Hypeomatic;

use App\Action\BaseFormAction;

class Home extends BaseFormAction
{
    public function __construct()
    {
        parent::__construct('');

        $this->setLayout('hypeomatic');
        $this->setView('hypeomatic/home');


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

    }

    protected function handleForm(): void
    {


    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

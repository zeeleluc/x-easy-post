<?php
namespace App\Action\Actions;

use App\Action\BaseFormAction;
use App\Service\XPost;

class Home extends BaseFormAction
{
    public function __construct()
    {
        parent::__construct('');

        $this->setLayout('default');
        $this->setView('website/home');
    }

    public function run()
    {
        parent::run();

        return $this;
    }

    protected function performPost()
    {
        $xPost = new XPost();
        $xPost->setText('Have you seen this collection already?');
        $xPost->setImageLooneyLuca();
        $xPost->reply('');
    }

    protected function handleForm()
    {
        // TODO: Implement handleForm() method.
    }
}

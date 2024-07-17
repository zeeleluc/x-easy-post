<?php
namespace App\Action\Actions\Hypeomatic;

use App\Action\BaseAction;
use App\Service\Form\FormHelper;
use App\Variable;

class LoadDynamicFormElements extends BaseAction
{
    public function __construct()
    {
        parent::__construct('');

        $this->setLayout('async');
        $this->setView('clean/dynamic-form-elements');

        $this->setVariable(new Variable(
            'formHelper',
            (new FormHelper())->parse($this->getRequest()->postJson())
        ));
    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

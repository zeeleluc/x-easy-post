<?php
namespace App\Action\Actions\Hypeomatic;

use App\Action\BaseAction;
use App\Variable;

class ShowImage extends BaseAction
{
    public function __construct()
    {
        parent::__construct('');

        $this->setLayout('hypeomatic');
        $this->setView('hypeomatic/show-image');

        $uuid = $this->getRequest()->getParam('uuid');
        if (!$uuid) {
            abort();
        }

        $image = $this->getImageQuery()->getImageByUuid($uuid);
        if (!$image) {
            abort();
        }

        $this->setVariable(new Variable('image', $image));
    }
}

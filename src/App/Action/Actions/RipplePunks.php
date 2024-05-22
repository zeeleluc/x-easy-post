<?php
namespace App\Action\Actions;

use App\Action\BaseAction;
use App\Service\PropertyHighlightRipplePunksWithoutTextAndMany;
use App\Variable;

class RipplePunks extends BaseAction
{
    public function __construct()
    {
        parent::__construct('');

        $this->setLayout('default');
        $this->setView('website/ripplepunks');

        $id = (int) $this->getRequest()->getParam('id');
        if ($id < 0 || $id > 9999) {
            abort();
        }

        $image = new PropertyHighlightRipplePunksWithoutTextAndMany();
        $image->setId($id);
        $images = $image->render();

        $this->setVariable(new Variable('url', $images['urlCDN']));
    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

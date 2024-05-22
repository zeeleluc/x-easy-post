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

        $image = new PropertyHighlightRipplePunksWithoutTextAndMany();
        $image->setId(15);
        $images = $image->render();

        $this->setVariable(new Variable('url', $images['urlCDN']));
    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

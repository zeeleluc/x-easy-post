<?php
namespace App\Service\Images\LooneyLuca;

use App\Service\Images\BaseImage;
use App\Service\Projects\Projects;

class Regular extends BaseImage
{
    protected string $project = Projects::LOONEY_LUCA;

    protected string $name = 'Regular NFT';

    public function __construct()
    {
        $this->idRange = range(1, 10000);
    }
}

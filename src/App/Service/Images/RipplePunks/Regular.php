<?php
namespace App\Service\Images\RipplePunks;

use App\Service\Images\BaseImage;
use App\Service\Projects\Projects;

class Regular extends BaseImage
{
    protected string $project = Projects::RIPPLE_PUNKS;

    protected string $name = 'Regular NFT';

    public function __construct()
    {
        $this->idRange = range(0, 9999);
    }
}

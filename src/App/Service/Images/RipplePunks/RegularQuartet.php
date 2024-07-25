<?php
namespace App\Service\Images\RipplePunks;

use App\Service\Images\BaseImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;

class RegularQuartet extends BaseImage
{
    use HasIdRange;

    protected string $project = Projects::RIPPLE_PUNKS;

    protected string $name = 'Regular Quartet NFT';

    protected string $description = 'This image type gives a RipplePunk Quartet card by #ID (or random by property)';

    public function __construct()
    {
        $this->idRange = range(1, 512);
    }
}

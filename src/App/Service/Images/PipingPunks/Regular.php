<?php
namespace App\Service\Images\PipingPunks;

use App\Models\DataSeeder;
use App\Service\Images\BaseImage;
use App\Service\Projects\Projects;

class Regular extends BaseImage
{
    protected string $project = Projects::PIPING_PUNKS;

    protected string $name = 'Regular NFT';

    public function __construct()
    {
        $this->idRange = range(0, 9999);
        $this->optionsPerId = DataSeeder::get(DataSeeder::CRYPTO_PUNKS_PROPERTIES_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }
}

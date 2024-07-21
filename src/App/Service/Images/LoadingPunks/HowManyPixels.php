<?php
namespace App\Service\Images\LoadingPunks;

use App\Models\DataSeeder;
use App\Service\Images\BaseImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;
use App\Service\Traits\HasOptions;
use App\Service\Traits\HasOptionsPerId;

class HowManyPixels extends BaseImage
{
    use HasOptions;
    use HasIdRange;
    use HasOptionsPerId;

    protected string $project = Projects::LOADING_PUNKS;

    protected string $name = 'How many pixels?';

    protected string $description = 'Get a breakdown of all pixels per attribute, incl. a QR code that leads to the NFT on the LoadingPunks website';

    public function __construct()
    {
        $this->idRange = range(0, 9999);
        $this->optionsPerId = DataSeeder::get(DataSeeder::CRYPTO_PUNKS_PROPERTIES_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }
}

<?php
namespace App\Service\Images\LoadingPunksOnBase;

use App\Models\DataSeeder;
use App\Service\Images\BaseImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;
use App\Service\Traits\HasOptions;
use App\Service\Traits\HasOptionsPerId;

class Regular extends BaseImage
{
    use HasOptions;
    use HasIdRange;
    use HasOptionsPerId;

    protected string $project = Projects::LOADING_PUNKS_BASE;

    protected string $name = 'Regular NFT';

    protected string $description = 'This image type gives a regular LoadingPunk on Base by #ID (or random by property)';

    protected string $imageExtension = 'gif';

    public function __construct()
    {
        $this->idRange = range(0, 9999);
        $this->optionsPerId = DataSeeder::get(DataSeeder::CRYPTO_PUNKS_PROPERTIES_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }
}

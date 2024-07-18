<?php
namespace App\Service\Images\PipingPunks;

use App\Models\DataSeeder;
use App\Service\Images\BaseImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;
use App\Service\Traits\HasOptions;
use App\Service\Traits\HasOptionsPerId;

class MovingPipingPunk extends BaseImage
{
    use HasOptions;
    use HasIdRange;
    use HasOptionsPerId;

    protected string $project = Projects::PIPING_PUNKS;

    protected string $name = 'NFT as GIF';

    public function __construct()
    {
        $this->idRange = range(0, 9999);
        $this->optionsPerId = DataSeeder::get(DataSeeder::CRYPTO_PUNKS_PROPERTIES_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }
}

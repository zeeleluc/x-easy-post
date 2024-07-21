<?php
namespace App\Service\Images\LooneyLuca;

use App\Models\DataSeeder;
use App\Service\Images\BaseImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;
use App\Service\Traits\HasOptions;
use App\Service\Traits\HasOptionsPerId;

class Regular extends BaseImage
{
    use HasIdRange;
    use HasOptions;
    use HasOptionsPerId;

    protected string $project = Projects::LOONEY_LUCA;

    protected string $name = 'Regular NFT';

    protected string $description = 'This image type gives a regular Looney Luca by #ID';

    public function __construct()
    {
        $this->idRange = range(1, 10000);
        $this->optionsPerId = DataSeeder::get(DataSeeder::LOONEY_LUCA_PROPERTIES_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }
}

<?php
namespace App\Service\Images\BaseAliens;

use App\Models\DataSeeder;
use App\Service\Images\BaseImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;
use App\Service\Traits\HasOptions;
use App\Service\Traits\HasOptionsPerId;

class Loading extends BaseImage
{
    use HasIdRange;
    use HasOptions;
    use HasOptionsPerId;

    protected string $project = Projects::BASE_ALIENS;

    protected string $name = 'Loading BaseAlien';

    public function __construct()
    {
        $this->idRange = range(1, 4444);
        $this->optionsPerId = DataSeeder::get(DataSeeder::BASE_ALIENS_PROPERTIES_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }
}

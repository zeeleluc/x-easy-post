<?php
namespace App\Service\Images\MoneyMindedApes;

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

    protected string $project = Projects::MONEY_MINDED_APES;

    protected string $name = 'Regular NFT';

    protected string $description = 'This image type gives a regular MoneyMindedApe by #ID (or random by property)';

    public function __construct()
    {
        $this->idRange = range(1, 1730);
        $this->optionsPerId = DataSeeder::get(DataSeeder::MONEY_MINDED_APES_PROPERTIES_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }
}

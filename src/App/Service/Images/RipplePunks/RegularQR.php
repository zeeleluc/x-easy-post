<?php
namespace App\Service\Images\RipplePunks;

use App\Models\DataSeeder;
use App\Service\Images\BaseImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;
use App\Service\Traits\HasOptions;
use App\Service\Traits\HasOptionsPerId;

class RegularQR extends BaseImage
{
    use HasIdRange;
    use HasOptions;
    use HasOptionsPerId;

    protected string $project = Projects::RIPPLE_PUNKS;

    protected string $name = 'Regular NFT With QR Code';

    protected string $description = 'This image type gives a regular RipplePunk by ID, including a QR code that leads to the RipplePunk on the RipplePunks website';

    public function __construct()
    {
        $this->idRange = range(0, 9999);
        $this->optionsPerId = DataSeeder::get(DataSeeder::CRYPTO_PUNKS_PROPERTIES_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }
}

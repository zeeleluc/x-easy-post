<?php
namespace App\Service\Images\BaseAliens;

use App\Models\DataSeeder;
use App\Service\Bucket;
use App\Service\Images\BaseTextImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;
use App\Service\Traits\HasOptions;
use App\Service\Traits\HasOptionsPerId;

class PropertyHighlightBaseAliensWithoutTextAndMany extends BaseTextImage
{
    use HasIdRange;
    use HasOptions;
    use HasOptionsPerId;

    protected string $project = Projects::BASE_ALIENS;

    protected string $name = 'Neat Properties Highlight With Mint Info';

    private ?int $id = null;

    private string $type = '';

    public function __construct()
    {
        $this->idRange = range(1, 4444);
        $this->optionsPerId = DataSeeder::get(DataSeeder::BASE_ALIENS_PROPERTIES_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public static function make(): PropertyHighlightBaseAliensWithoutTextAndMany
    {
        return new self();
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @throws \ImagickDrawException
     * @throws \ImagickException
     */
    public function render(): array
    {
        $filename = uniqid() . '.png';
        $urlTMP = ROOT . '/tmp/' . $filename;

        $image = new \Imagick();
        $image->newImage(1000, 1000, '#CAD9FC');
        $image->setImageFormat("png");

        $y = 100;
        for ($row = 0; $row < 16; ++$row) {
            $x = 100;
            for ($column = 0; $column < 16; ++$column) {
                if (!$this->isSmallAlienOutOfRange($row, $column)) {
                    $id = $this->getRandomIdForOption($this->type);
                    $baseAlienSmall = $this->getBasePunkTransparent($id, 91.4);
                    $image->compositeImage($baseAlienSmall, \Imagick::COMPOSITE_ATOP, $x, $y);
                }
                $x += 50;
            }
            $y += 50;
        }

        $id = $this->getRandomIdForOption($this->type);
        $baseAlienLarge = $this->getBasePunkTransparent($id, 39.2);
        $image->compositeImage($baseAlienLarge, \Imagick::COMPOSITE_ATOP, 325, 325);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/space_age-webfont.ttf");
        $draw->setFontSize(60);
        $draw->setFillColor(new \ImagickPixel('#17287F'));
        $draw->annotation(500, 70, '4,444 BASEALIENS');
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/space_age-webfont.ttf");
        $draw->setFontSize(60);
        $draw->setFillColor(new \ImagickPixel('#17287F'));
        $draw->annotation(500, 965, '.002 VIA NIFTYKIT');
        $image->drawImage($draw);

        $image->writeImage($urlTMP);

        return [
            'urlCDN' => $this->upload($urlTMP, $filename),
            'urlTMP' => $urlTMP,
        ];
    }

    private function isSmallAlienOutOfRange(int $row, int $column): bool
    {
        $outOfRangeCoordinates = [];
        $outOfRangeCoordinates[4] = range(4, 11);
        $outOfRangeCoordinates[5] = range(4, 11);
        $outOfRangeCoordinates[6] = range(4, 11);
        $outOfRangeCoordinates[7] = range(4, 11);
        $outOfRangeCoordinates[8] = range(4, 11);
        $outOfRangeCoordinates[9] = range(4, 11);
        $outOfRangeCoordinates[10] = range(4, 11);
        $outOfRangeCoordinates[11] = range(4, 11);

        if (array_key_exists($row, $outOfRangeCoordinates) && in_array($column, $outOfRangeCoordinates[$row])) {
            return true;
        }

        return false;
    }

    private function upload(string $urlTMP, string $filename): string
    {
        return (new Bucket())->uploadFile($urlTMP, $filename);
    }
}

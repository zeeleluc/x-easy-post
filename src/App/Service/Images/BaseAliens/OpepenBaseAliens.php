<?php
namespace App\Service\Images\BaseAliens;

use App\Models\DataSeeder;
use App\Service\Bucket;
use App\Service\Images\BaseTextImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;
use App\Service\Traits\HasOptions;
use App\Service\Traits\HasOptionsPerId;

class OpepenBaseAliens extends BaseTextImage
{
    use HasIdRange;
    use HasOptions;
    use HasOptionsPerId;

    protected string $project = Projects::BASE_ALIENS;

    protected string $name = 'Opepen Style';

    private string $text = '';

    private string $type = '';

    private ?int $id = null;

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->idRange = range(1, 4444);
        $this->optionsPerId = DataSeeder::get(DataSeeder::BASE_ALIENS_PROPERTIES_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }

    public static function make(): OpepenBaseAliens
    {
        return new self();
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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
        $image->newImage(800, 800, '#3250FC');
        $image->setImageFormat("png");

        $color = $this->getColor();
        $colors = [
            adjust_brightness($color, 0.1),
            adjust_brightness($color, 0.13),
            adjust_brightness($color, 0.16),
            adjust_brightness($color, 0.19),
        ];

        $rowsY = [200, 300, 400, 500, 700];
        foreach ($rowsY as $rowY) {
            shuffle($colors);
            $this->pasteRipplePunk($image, $colors[0], 2 * 100, $rowY);
            $this->pasteRipplePunk($image, $colors[1], 3 * 100, $rowY);
            $this->pasteRipplePunk($image, $colors[2], 4 * 100, $rowY);
            $this->pasteRipplePunk($image, $colors[3], 5 * 100, $rowY);
        }

        if ($this->text) {
            $image->drawImage($this->createText($this->text, 120));
        }

        $image->writeImage($urlTMP);

        return [
            'urlCDN' => $this->upload($urlTMP, $filename),
            'urlTMP' => $urlTMP,
        ];
    }

    private function pasteRipplePunk(\Imagick &$image, string $color, int $x, int $y)
    {
        $resize = 82.7; // will result in 100px width

        if ($this->id) {
            $ripplePunk = $this->getBasePunkTransparent($this->id, $resize);
        } elseif ($this->type) {
            $id = $this->getRandomIdForOption($this->type);
            $ripplePunk = $this->getBasePunkTransparent($id, $resize);
        } else {
            $ripplePunk = $this->getBasePunkTransparent(null, $resize);
        }

        // background square
        $draw = new \ImagickDraw();
        $color = new \ImagickPixel($color);
        $draw->setFillColor($color);
        $draw->rectangle($x, $y + 100, $x + 100, $y);
        $image->drawImage($draw);

        $image->compositeImage($ripplePunk, \Imagick::COMPOSITE_ATOP, $x, $y);
    }

    private function getColor(): string
    {
        $colors = [
            '#3250FC',
        ];
        shuffle($colors);

        return $colors[0];
    }

    private function upload(string $urlTMP, string $filename): string
    {
        return (new Bucket())->uploadFile($urlTMP, $filename);
    }

    /**
     * @throws \ImagickException
     * @throws \ImagickDrawException
     */
    private function createText(string $text): \ImagickDraw
    {
        $fontSize = $this->calculateFontSize();

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/space_age-webfont.ttf");
        $draw->setFontSize($fontSize);
        $draw->setFillColor(new \ImagickPixel('#efefef'));

        $y = 100;
        if ($fontSize >= 60) {
            $y = 110;
        }
        $draw->annotation(400, $y, $text);

        return $draw;
    }

    private function calculateFontSize(): int
    {
        $baseFontSize = 120;
        $minFontSize = 50;
        $maxLength = 30;
        $rate = 0.4;

        $length = strlen($this->text);

        if ($length <= 1) {
            $fontSize = $baseFontSize;
        } else {
            $fontSize = $baseFontSize - pow($length, $rate) * (($baseFontSize - $minFontSize) / pow($maxLength, $rate));
        }

        return max($fontSize, $minFontSize);
    }

}

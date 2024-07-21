<?php
namespace App\Service\Images\BaseAliens;

use App\Models\DataSeeder;
use App\Service\Bucket;
use App\Service\Images\BaseTextImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;
use App\Service\Traits\HasOptions;
use App\Service\Traits\HasOptionsPerId;

class TextImageCenteredWithMintInfoBaseAliens extends BaseTextImage
{
    use HasIdRange;
    use HasOptions;
    use HasOptionsPerId;

    protected string $project = Projects::BASE_ALIENS;

    protected string $name = 'Centered Text With Mint Info';

    private ?string $text = '';

    private ?int $id = null;

    private string $type = '';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->idRange = range(1, 4444);
        $this->optionsPerId = DataSeeder::get(DataSeeder::BASE_ALIENS_PROPERTIES_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }

    public static function make(): TextImageCenteredWithMintInfoBaseAliens
    {
        return new self();
    }

    public function setText(string $text = null): static
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
        $image->newImage(800, 800, '#CAD9FC');
        $image->setImageFormat("png");

        if ($this->id) {
            $baseAlienFirst = $this->getBasePunkTransparent($this->id, 40);
            $baseAlienSecond = $this->getBasePunkTransparent($this->id, 40);
        } elseif ($this->type) {
            $baseAlienFirst = $this->getBasePunkTransparent($this->getRandomIdForOption($this->type), 40);
            $baseAlienSecond = $this->getBasePunkTransparent($this->getRandomIdForOption($this->type), 40);
        } else {
            $baseAlienFirst = $this->getRandomImageBaseAlienFirst(40);
            $baseAlienSecond = $this->getRandomImageBaseAlienSecond(40);
        }

        // pick a variant
        $variants = [
            'positionImagesVariantThree',
            'positionImagesVariantFour',
        ];
        shuffle($variants);
        $this->{$variants[0]}($image, $baseAlienFirst, $baseAlienSecond);

        if ($this->text) {
            $image->drawImage($this->createText($this->text));
        }

        $image->writeImage($urlTMP);

        return [
            'urlCDN' => $this->upload($urlTMP, $filename),
            'urlTMP' => $urlTMP,
        ];
    }

    private function positionImagesVariantThree(\Imagick &$image, \Imagick $baseAlienFirst, \Imagick $baseAlienSecond): void
    {
        $baseAlienFirst->rotateImage('#CAD9FC', 90);
        $baseAlienSecond->rotateImage('#CAD9FC', 90 * 3);

        $image->compositeImage($baseAlienFirst, \Imagick::COMPOSITE_ATOP, 0, 0);
        $image->compositeImage($baseAlienSecond, \Imagick::COMPOSITE_ATOP, 455, 455);

        $this->setTopBottomRectanglesWithInformation($image);
    }

    private function positionImagesVariantFour(\Imagick &$image, \Imagick $baseAlienFirst, \Imagick $baseAlienSecond): void
    {
        $baseAlienFirst->rotateImage('#CAD9FC', 90 * 3);
        $baseAlienSecond->rotateImage('#CAD9FC', 90);

        $image->compositeImage($baseAlienFirst, \Imagick::COMPOSITE_ATOP, 455, 40);
        $image->compositeImage($baseAlienSecond, \Imagick::COMPOSITE_ATOP, 0, 415);

        $this->setTopBottomRectanglesWithInformation($image);
    }

    private function setTopBottomRectanglesWithInformation(\Imagick &$image): void
    {
        // top rectangle
        $draw = new \ImagickDraw();
        $color = new \ImagickPixel('#3250FC');
        $draw->setFillColor($color);
        $draw->rectangle(0, 40, 800, 0);
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/space_age-webfont.ttf");
        $draw->setFontSize(30);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(400, 30, '4,444 BASEALIENS');
        $image->drawImage($draw);

        // bottom rectangle
        $draw = new \ImagickDraw();
        $color = new \ImagickPixel('#3250FC');
        $draw->setFillColor($color);
        $draw->rectangle(0, 800, 800, 760);
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/space_age-webfont.ttf");
        $draw->setFontSize(30);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(400, 787, '.002 VIA NIFTYKIT');
        $image->drawImage($draw);
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
        $draw->setFillColor(new \ImagickPixel('#111111'));

        $y = 400;
        if ($fontSize >= 60) {
            $y = 440;
        }
        $draw->annotation(400, $y, $text);

        return $draw;
    }

    private function calculateFontSize(): int
    {
        $baseFontSize = 100;
        $minFontSize = 30;
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

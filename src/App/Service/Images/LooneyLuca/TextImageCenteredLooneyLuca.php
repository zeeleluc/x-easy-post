<?php
namespace App\Service\Images\LooneyLuca;

use App\Models\DataSeeder;
use App\Service\Bucket;
use App\Service\Images\BaseTextImage;
use App\Service\Projects\Projects;

class TextImageCenteredLooneyLuca extends BaseTextImage
{
    protected string $project = Projects::LOONEY_LUCA;

    protected string $name = 'Centered Text';

    private array $backgroundColorPerId;

    private string $text = '';

    protected string $description = 'Shout a few words with 2 random Looney Luca\'s';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColorPerId = DataSeeder::get(DataSeeder::LOONEY_LUCA_BACKGROUND_COLOR_PER_ID);
    }

    public static function make(): TextImageCenteredLooneyLuca
    {
        return new self();
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getRandomByHex(string $forHex): int
    {
        $idsForHex = [];
        foreach ($this->backgroundColorPerId as $id => $hex)
        {
            if ($hex === $forHex) {
                $idsForHex[$id] = $hex;
            }
        }
        $ids = array_keys($idsForHex);
        shuffle($ids);

        return $ids[0];
    }

    /**
     * @throws \ImagickDrawException
     * @throws \ImagickException
     */
    public function render(): array
    {
        $filename = uniqid() . '.png';
        $urlTMP = ROOT . '/tmp/' . $filename;

        $looneyLucaWithSolidBackground = array_keys($this->backgroundColorPerId);
        shuffle($looneyLucaWithSolidBackground);

        $looneyLucaId = $looneyLucaWithSolidBackground[0];
        $background = $this->backgroundColorPerId[$looneyLucaWithSolidBackground[0]];
        $looneyLucaSecondId = $this->getRandomByHex($background);

        $image = new \Imagick();
        $image->newImage(800, 800, $background);
        $image->setImageFormat("png");

        // orange top rectangle
        $draw = new \ImagickDraw();
        $color = new \ImagickPixel('#FB6C0F');
        $draw->setFillColor($color);
        $draw->rectangle(0, 100, 1000, 0);
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/kidslinedemo-webfont.ttf");
        $draw->setFontSize(60);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(400, 70, 'Mint Looney Luca for 0.001 on OpenSea');
        $image->drawImage($draw);

        # composite the Looney Luca images
        $looneyLuca = $this->getLooneyLuca($looneyLucaId, 85);
        $looneyLucaSecond = $this->getLooneyLuca($looneyLucaSecondId, 85);
        $looneyLucaSecond->flopImage();

        $image->compositeImage($looneyLuca, \Imagick::COMPOSITE_ATOP, 75, 493);
        $image->compositeImage($looneyLucaSecond, \Imagick::COMPOSITE_ATOP, 418, 493);

        # add Looney Luca #IDs in bottom corners
        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_LEFT);
        $draw->setFont(ROOT . "/assets/fonts/kidslinedemo-webfont.ttf");
        $draw->setFontSize(20);
        $draw->setFillColor(new \ImagickPixel(darken_color($background, 90)));
        $draw->annotation(10, 790, '#' . $looneyLucaId);
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_RIGHT);
        $draw->setFont(ROOT . "/assets/fonts/kidslinedemo-webfont.ttf");
        $draw->setFontSize(20);
        $draw->setFillColor(new \ImagickPixel(darken_color($background, 90)));
        $draw->annotation(790, 790, '#' . $looneyLucaSecondId);
        $image->drawImage($draw);

        # add centered large text
        if ($this->text) {
            $image->drawImage($this->createText($this->text, '#111111'));
        }

        $image->writeImage($urlTMP);

        return [
            'urlCDN' => $this->upload($urlTMP, $filename),
            'urlTMP' => $urlTMP,
        ];
    }

    private function upload(string $urlTMP, string $filename): string
    {
        return (new Bucket())->uploadFile($urlTMP, $filename);
    }

    /**
     * @throws \ImagickException
     * @throws \ImagickDrawException
     */
    private function createText(string $text, string $hexColor): \ImagickDraw
    {
        $fontSize = $this->calculateFontSize();

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/kidslinedemo-webfont.ttf");
        $draw->setFontSize($fontSize);
        $draw->setFillColor(new \ImagickPixel($hexColor));

        $y = 300;
        if ($fontSize >= 60) {
            $y = 340;
        }
        $draw->annotation(400, $y, $text);

        return $draw;
    }

    private function calculateFontSize(): int
    {
        $baseFontSize = 300;
        $minFontSize = 60;
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

<?php
namespace App\Service\Images\RipplePunks;

use App\Service\Bucket;
use App\Service\Images\BaseTextImage;
use App\Service\Projects\Projects;

class TextImageCenteredRipplePunks extends BaseTextImage
{
    protected string $project = Projects::RIPPLE_PUNKS;

    private string $text = '';

    protected string $name = 'Centered Text';

    public function __construct()
    {
        $this->canHaveImageText = true;
    }

    public static function make(): TextImageCenteredRipplePunks
    {
        return new self();
    }

    public function setText(string $text): static
    {
        $this->text = $text;

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
        $image->newImage(800, 800, '#C2E4F7');
        $image->setImageFormat("png");

        $color = $this->getColor();
        $colors = [
            adjust_brightness($color, 0.1),
            adjust_brightness($color, 0.2),
            adjust_brightness($color, 0.3),
            adjust_brightness($color, 0.4),
            adjust_brightness($color, 0.5),
            adjust_brightness($color, 0.6),
            adjust_brightness($color, 0.7),
            adjust_brightness($color, 0.8),
            adjust_brightness($color, 0.9),
            $color,
            darken_color($color, 10),
        ];

        $this->pasteRipplePunk($image, $colors[0], 0 * 160, 0);
        $this->pasteRipplePunk($image, $colors[1], 1 * 160, 0);
        $this->pasteRipplePunk($image, $colors[2], 2 * 160, 0);
        $this->pasteRipplePunk($image, $colors[3], 3 * 160, 0);
        $this->pasteRipplePunk($image, $colors[4], 4 * 160, 0);

        $this->pasteRipplePunk($image, $colors[5], 0 * 160, 800 - 160);
        $this->pasteRipplePunk($image, $colors[6], 1 * 160, 800 - 160);
        $this->pasteRipplePunk($image, $colors[7], 2 * 160, 800 - 160);
        $this->pasteRipplePunk($image, $colors[8], 3 * 160, 800 - 160);
        $this->pasteRipplePunk($image, $colors[9], 4 * 160, 800 - 160);

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
        // background square
        $draw = new \ImagickDraw();
        $color = new \ImagickPixel($color);
        $draw->setFillColor($color);
        $draw->rectangle($x, $y + 160, $x + 160, $y);
        $image->drawImage($draw);

        $resize = 72.2; // will result in 160px width
        $image->compositeImage($this->getRipplePunkFixedSize(null, $resize), \Imagick::COMPOSITE_ATOP, $x, $y);
    }

    private function getColor(): string
    {
        $colors = [
            '#1990E3',
            '#34A0E0',
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
        $draw->setFont(ROOT . "/assets/fonts/JoystixMonospace-Regular.ttf");
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

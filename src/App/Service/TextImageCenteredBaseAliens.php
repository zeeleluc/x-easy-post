<?php
namespace App\Service;

class TextImageCenteredBaseAliens extends BaseTextImage
{

    private string $text = '';

    public function __construct()
    {
    }

    public static function make(): TextImageCenteredBaseAliens
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
        $image->newImage(800, 800, '#CAD9FC');
        $image->setImageFormat("png");

        $baseAlienFirst = $this->getRandomImageBaseAlienFirst(40);
        $baseAlienFirst->flipImage();

        $baseAlienSecond = $this->getRandomImageBaseAlienSecond(40);
        $baseAlienSecond->flopImage();

        $image->compositeImage($baseAlienFirst, \Imagick::COMPOSITE_ATOP, 10, 0);
        $image->compositeImage($baseAlienSecond, \Imagick::COMPOSITE_ATOP, 470, 455);

        if ($this->text) {
            $image->drawImage($this->createText($this->text, 120));
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

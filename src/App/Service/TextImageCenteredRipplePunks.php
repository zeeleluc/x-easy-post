<?php
namespace App\Service;

class TextImageCenteredRipplePunks extends BaseTextImage
{

    private string $text = '';

    public function __construct()
    {
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

        $this->pasteRipplePunk($image, 0 * 160, 0);
        $this->pasteRipplePunk($image, 1 * 160, 0);
        $this->pasteRipplePunk($image, 2 * 160, 0);
        $this->pasteRipplePunk($image, 3 * 160, 0);
        $this->pasteRipplePunk($image, 4 * 160, 0);

        $this->pasteRipplePunk($image, 0 * 160, 800 - 160);
        $this->pasteRipplePunk($image, 1 * 160, 800 - 160);
        $this->pasteRipplePunk($image, 2 * 160, 800 - 160);
        $this->pasteRipplePunk($image, 3 * 160, 800 - 160);
        $this->pasteRipplePunk($image, 4 * 160, 800 - 160);

        if ($this->text) {
            $image->drawImage($this->createText($this->text, 120));
        }

        $image->writeImage($urlTMP);

        return [
            'urlCDN' => $this->upload($urlTMP, $filename),
            'urlTMP' => $urlTMP,
        ];
    }

    private function pasteRipplePunk(\Imagick &$image, int $x, int $y)
    {
        // background square
        $draw = new \ImagickDraw();
        $color = new \ImagickPixel($this->getColor());
        $draw->setFillColor($color);
        $draw->rectangle($x, $y + 160, $x + 160, $y);
        $image->drawImage($draw);

        $resize = 72.2; // will result in 160px width
        $image->compositeImage($this->getRipplePunkFixedSize($resize), \Imagick::COMPOSITE_ATOP, $x, $y);
    }

    private function getColor(): string
    {
        $colors = [
            '#FDF0C1',
            '#FFE0A9',
            '#FFCC9C',
            '#FFB69C',
            '#BFDBEB',
            '#91B3DA',
            '#D6A7CD',
            '#FDF0C1',
            '#C4B88B',
            '#FCF1CA',
            '#F2FEDC',
            '#4797C9',
            '#00B3D5',
            '#00CCCA',
            '#4CE1AD',
            '#A5F18A',
            '#F9F871',
            '#DB7065',
            '#639E4F',
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

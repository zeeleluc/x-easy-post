<?php
namespace App\Service;

class OpepenLooneyLuca extends BaseTextImage
{

    private string $text = '';

    private string $type = '';

    public function __construct()
    {
    }

    public static function make(): OpepenLooneyLuca
    {
        return new self();
    }

    public function setText(string $text): static
    {
        $this->text = $text;

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


        $looneyLucaWithSolidBackground = array_keys(id_background_hex_looney_luca());
        shuffle($looneyLucaWithSolidBackground);

        $background = id_background_hex_looney_luca()[$looneyLucaWithSolidBackground[0]];

        $image = new \Imagick();
        $image->newImage(1000, 1000, $background);
        $image->setImageFormat("png");

        $rowsY = [200, 300, 400, 500, 700];
        foreach ($rowsY as $rowY) {
            $this->pasteLooneyLuca($image, $background, 2 * 100, $rowY);
            $this->pasteLooneyLuca($image, $background, 3 * 100, $rowY);
            $this->pasteLooneyLuca($image, $background, 4 * 100, $rowY);
            $this->pasteLooneyLuca($image, $background, 5 * 100, $rowY);
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

    private function pasteLooneyLuca(\Imagick &$image, string $color, int $x, int $y)
    {
        $resize = 95.1; // will result in 100px width

        $id = get_random_by_hex($color);
        $ripplePunk = $this->getLooneyLuca($id, $resize);

        // background square
        $draw = new \ImagickDraw();
        $color = new \ImagickPixel($color);
        $draw->setFillColor($color);
        $draw->rectangle($x, $y + 100, $x + 100, $y);
        $image->drawImage($draw);

        $image->compositeImage($ripplePunk, \Imagick::COMPOSITE_ATOP, $x, $y);
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

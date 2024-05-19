<?php
namespace App\Service;

class TextAdRichLists extends BaseTextImage
{

    private string $text = '';

    public function __construct()
    {
    }

    public static function make(): TextAdRichLists
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
        $image->newImage(800, 800, '#000000');
        $image->setImageFormat("png");

        // large rectangle gray
        $draw = new \ImagickDraw();
        $color = new \ImagickPixel('#222222');
        $draw->setFillColor($color);
        $draw->rectangle(0, 400, 800, 0);
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $color = new \ImagickPixel('#000000');
        $draw->setFillColor($color);
        $draw->rectangle(0, 100, 800, 0);
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $color = new \ImagickPixel('#111111');
        $draw->setFillColor($color);
        $draw->rectangle(0, 800, 400, 400);
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $color = new \ImagickPixel('#FFFFBC');
        $draw->setFillColor($color);
        $draw->rectangle(0, 400, 800, 380);
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/kodemono-variablefont_wght-webfont.ttf");
        $draw->setFontSize(25);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(400, 65, 'Multi-Chain NFT RichLists');
        $image->drawImage($draw);

        $image->compositeImage($this->getMoneyFaceEmoji(85), \Imagick::COMPOSITE_ATOP, 680, 50);


        if ($this->text) {
            $image->drawImage($this->createText($this->text, 120));
        }

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_LEFT);
        $draw->setFont(ROOT . "/assets/fonts/kodemono-variablefont_wght-webfont.ttf");
        $draw->setFontSize(20);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(450, 500, 'Multi-Chain');
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_LEFT);
        $draw->setFont(ROOT . "/assets/fonts/kodemono-variablefont_wght-webfont.ttf");
        $draw->setFontSize(20);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(450, 550, 'Wrap collections per chain');
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_LEFT);
        $draw->setFont(ROOT . "/assets/fonts/kodemono-variablefont_wght-webfont.ttf");
        $draw->setFontSize(20);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(450, 600, 'Realtime data endpoints');
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_LEFT);
        $draw->setFont(ROOT . "/assets/fonts/kodemono-variablefont_wght-webfont.ttf");
        $draw->setFontSize(20);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(450, 650, 'JSON endpoint');
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_LEFT);
        $draw->setFont(ROOT . "/assets/fonts/kodemono-variablefont_wght-webfont.ttf");
        $draw->setFontSize(20);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(450, 700, 'HTML endpoint (inject CSS)');
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/kodemono-variablefont_wght-webfont.ttf");
        $draw->setFontSize(37);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(200, 530, 'Starting from');
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/kodemono-variablefont_wght-webfont.ttf");
        $draw->setFontSize(48);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(200, 600, '$10 p/m');
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/kodemono-variablefont_wght-webfont.ttf");
        $draw->setFontSize(18);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(200, 700, 'richlist.hasmints.com');
        $image->drawImage($draw);

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
        $draw->setFont(ROOT . "/assets/fonts/kodemono-variablefont_wght-webfont.ttf");
        $draw->setFontSize($fontSize);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));

        $y = 250;
        if ($fontSize >= 60) {
            $y = 270;
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

<?php
namespace App\Service\Images;

use App\Service\Bucket;

class BaseSimpleTextImageInjectMeme extends BaseImage
{

    protected string $backgroundColor;

    protected string $textColor;

    protected string $text = '';

    public static function make(): BaseSimpleTextImageInjectmeme
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
        $image->newImage(800, 800, $this->backgroundColor);
        $image->setImageFormat("png");

        if ($this->text) {
            $image->drawImage($this->createText($this->text));
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
        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/inject-meme/RedHatText-Regular.ttf");
        $draw->setFontSize(50);
        $draw->setFillColor(new \ImagickPixel($this->textColor));
        $draw->annotation(400, 400, $text);

        return $draw;
    }
}

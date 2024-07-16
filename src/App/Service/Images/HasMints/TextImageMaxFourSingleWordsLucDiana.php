<?php
namespace App\Service\Images\HasMints;

use App\Service\Bucket;
use App\Service\Images\BaseTextImage;
use App\Service\Projects\Projects;

class TextImageMaxFourSingleWordsLucDiana extends BaseTextImage
{
    protected string $project = Projects::HAS_MINTS;

    protected string $name = 'Four Words Luc & Diana';

    private string $textFirstRow = '';

    private string $textSecondRow = '';

    private string $textThirdRow = '';

    private string $textFourthRow = '';

    public function __construct()
    {
        $this->canHaveImageText = true;
    }

    public static function make(): TextImageMaxFourSingleWordsLucDiana
    {
        return new self();
    }

    public function textFirstRow(string $text): static
    {
        $this->textFirstRow = $text;

        return $this;
    }

    public function textSecondRow(string $text): static
    {
        $this->textSecondRow = $text;

        return $this;
    }

    public function textThirdRow(string $text): static
    {
        $this->textThirdRow = $text;

        return $this;
    }

    public function textFourthRow(string $text): static
    {
        $this->textFourthRow = $text;

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
        $image->newImage(800, 800, '#C0DBEC');
        $image->setImageFormat("png");

        $image->compositeImage($this->getImageLuc(50), \Imagick::COMPOSITE_ATOP, 35, 514);
        $image->compositeImage($this->getImageDianaFlip(50), \Imagick::COMPOSITE_ATOP, 500, 0);

        if ($this->textFirstRow) {
            $image->drawImage($this->createText($this->textFirstRow, 120));
        }
        if ($this->textSecondRow) {
            $image->drawImage($this->createText($this->textSecondRow, 220));
        }
        if ($this->textThirdRow) {
            $image->drawImage($this->createText($this->textThirdRow, 320));
        }
        if ($this->textFourthRow) {
            $image->drawImage($this->createText($this->textFourthRow, 420));
        }

        $footerText = 'hasmints.com';

        $image->drawImage($this->createFooterText($footerText));
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
    private function createFooterText(string $text): \ImagickDraw
    {
        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_RIGHT);
        $draw->setFont(ROOT . "/assets/fonts/bm-hanna-webfont.ttf");
        $draw->setFontSize(40);
        $draw->setFillColor(new \ImagickPixel('#7ba8c4'));
        $draw->annotation(770, 765, $text);

        return $draw;
    }

    /**
     * @throws \ImagickException
     * @throws \ImagickDrawException
     */
    private function createText(string $text, int $y): \ImagickDraw
    {
        $draw = new \ImagickDraw();
        $draw->setFont(ROOT . "/assets/fonts/bm-hanna-webfont.ttf");
        $draw->setFontSize(80);
        $draw->setFillColor(new \ImagickPixel('#111111'));
        $draw->annotation(50, $y, $text);

        return $draw;
    }
}

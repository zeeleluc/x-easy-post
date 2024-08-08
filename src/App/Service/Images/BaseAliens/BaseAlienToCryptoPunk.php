<?php
namespace App\Service\Images\BaseAliens;

use App\Models\DataSeeder;
use App\Service\Bucket;
use App\Service\Images\BaseTextImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;

class BaseAlienToCryptoPunk extends BaseTextImage
{
    use HasIdRange;

    protected string $project = Projects::BASE_ALIENS;

    protected string $name = 'Match with CryptoPunk Humanoid';

    private array $baseAliensIdToCryptoPunksId = [];

    private ?int $id = null;

    protected string $description = 'Find out from which CryptoPunk humanoid a BaseAlien was morphed.';

    public function __construct()
    {
        $this->idRange = range(1, 4444);
        $this->baseAliensIdToCryptoPunksId = DataSeeder::get(DataSeeder::BASE_ALIENS_ID_TO_CRYPTOPUNKS_ID);
    }

    public static function make(): BaseAlienToCryptoPunk
    {
        return new self();
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @throws \ImagickDrawException
     * @throws \ImagickException
     */
    public function render(): array
    {
        if (!$this->id) {
            $this->id = $this->getRandomId();
        }

        $filename = uniqid() . '.png';
        $urlTMP = ROOT . '/tmp/' . $filename;

        $image = new \Imagick();
        $image->newImage(800, 800, '#2f6ff9');
        $image->setImageFormat("png");

        $image->drawImage($this->createTextBaseAlien('BaseAlien'));
        $image->drawImage($this->createTextBaseAlienNr('#' . $this->id));
        $image->drawImage($this->createTextCryptoPunk('CryptoPunk #' . $this->baseAliensIdToCryptoPunksId[$this->id] . ' was once a humble humanoid on Ethereum'));

        $this->pasteBaseAlien($image,-20, 235);
        $this->pasteCryptoPunk($image,440, 450);

        $image->writeImage($urlTMP);

        return [
            'urlCDN' => $this->upload($urlTMP, $filename),
            'urlTMP' => $urlTMP,
        ];
    }

    private function pasteBaseAlien(\Imagick &$image, int $x, int $y)
    {
        $baseAlien = $this->getBasePunkTransparent($this->id, 2);

        // background square
        $image->compositeImage($baseAlien, \Imagick::COMPOSITE_ATOP, $x, $y);
    }

    private function pasteCryptoPunk(\Imagick &$image, int $x, int $y)
    {
        $width = 350;
        $height = 350;

        $cryptoPunk = $this->getCryptoPunkFixedSize(
            $this->baseAliensIdToCryptoPunksId[$this->id],
            $width,
            $height
        );

        // background square
        $image->compositeImage($cryptoPunk, \Imagick::COMPOSITE_ATOP, $x, $y);
    }

    /**
     * @throws \ImagickException
     * @throws \ImagickDrawException
     */
    private function createTextBaseAlien(string $text): \ImagickDraw
    {
        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/space_age-webfont.ttf");
        $draw->setFontSize(90);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));

        $draw->annotation(400, 100, $text);

        return $draw;
    }

    /**
     * @throws \ImagickException
     * @throws \ImagickDrawException
     */
    private function createTextBaseAlienNr(string $text): \ImagickDraw
    {
        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/space_age-webfont.ttf");
        $draw->setFontSize(110);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));

        $draw->annotation(400, 180, $text);

        return $draw;
    }

    /**
     * @throws \ImagickException
     * @throws \ImagickDrawException
     */
    private function createTextCryptoPunk(string $text): \ImagickDraw
    {
        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
//        $draw->setFont(ROOT . "/assets/fonts/space_age-webfont.ttf");
        $draw->setFontSize(25);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));

        $draw->annotation(400, 250, $text);

        return $draw;
    }

    private function upload(string $urlTMP, string $filename): string
    {
        return (new Bucket())->uploadFile($urlTMP, $filename);
    }
}

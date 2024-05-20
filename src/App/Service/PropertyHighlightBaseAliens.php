<?php
namespace App\Service;

class PropertyHighlightBaseAliens extends BaseTextImage
{

    private string $type = '';

    public function __construct()
    {
    }

    public static function make(): PropertyHighlightBaseAliens
    {
        return new self();
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

        $y = 0;
        for ($row = 0; $row < 8; ++$row) {
            $x = 0;
            for ($column = 0; $column < 8; ++$column) {
                $id = get_random_basealien_id_for_type($this->type);
                $baseAlienFirst = $this->getBasePunkTransparent($id, 82.7);
                $image->compositeImage($baseAlienFirst, \Imagick::COMPOSITE_ATOP, $x, $y);
                $x += 100;
            }
            $y += 100;
        }

        // top rectangle
        $draw = new \ImagickDraw();
        $color = new \ImagickPixel('#3250FC');
        $draw->setFillColor($color);
        $draw->rectangle(0, 350, 800, 450);
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/space_age-webfont.ttf");
        $draw->setFontSize(50);
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->annotation(400, 415, str_replace('Attribute:', '', $this->type));
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
}

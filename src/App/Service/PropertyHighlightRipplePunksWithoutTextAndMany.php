<?php
namespace App\Service;

class PropertyHighlightRipplePunksWithoutTextAndMany extends BaseTextImage
{

    private ?int $id = null;

    private string $type = '';

    public function __construct()
    {
    }

    public static function make(): PropertyHighlightRipplePunksWithoutTextAndMany
    {
        return new self();
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

        $image = new \Imagick();
        $image->newImage(1000, 1000, '#C3E5F7');
        $image->setImageFormat("png");

        $y = 100;
        for ($row = 0; $row < 16; ++$row) {
            $x = 100;
            for ($column = 0; $column < 16; ++$column) {
                if (!$this->isSmallPunkOutOfRange($row, $column)) {
                    if ($this->type) {
                        $id = get_random_cryptopunk_id_for_type($this->type);
                        $smallPunk = $this->getRipplePunkFixedSize($id, 91.4);
                    } else {
                        $smallPunk = $this->getRipplePunkFixedSize(null, 91.4);
                    }
                    $image->compositeImage($smallPunk, \Imagick::COMPOSITE_ATOP, $x, $y);
                }
                $x += 50;
            }
            $y += 50;
        }

        if (!$this->id) {
            $id = get_random_cryptopunk_id_for_type($this->type);
        } else {
            $id = $this->id;
        }
        $largePunk = $this->getRipplePunkFixedSize($id, 39.2);
        $image->compositeImage($largePunk, \Imagick::COMPOSITE_ATOP, 325, 325);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/JoystixMonospace-Regular.ttf");
        $draw->setFontSize(50);
        $draw->setFillColor(new \ImagickPixel('#090909'));
        $draw->annotation(500, 70, '10,000 RIPPLEPUNKS');
        $image->drawImage($draw);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/JoystixMonospace-Regular.ttf");
        $draw->setFontSize(50);
        $draw->setFillColor(new \ImagickPixel('#090909'));
        $draw->annotation(500, 965, 'PUNKS ON THE XRPL');
        $image->drawImage($draw);

        $image->writeImage($urlTMP);

        return [
            'urlCDN' => $this->upload($urlTMP, $filename),
            'urlTMP' => $urlTMP,
        ];
    }

    private function isSmallPunkOutOfRange(int $row, int $column): bool
    {
        $outOfRangeCoordinates = [];
        $outOfRangeCoordinates[4] = range(4, 11);
        $outOfRangeCoordinates[5] = range(4, 11);
        $outOfRangeCoordinates[6] = range(4, 11);
        $outOfRangeCoordinates[7] = range(4, 11);
        $outOfRangeCoordinates[8] = range(4, 11);
        $outOfRangeCoordinates[9] = range(4, 11);
        $outOfRangeCoordinates[10] = range(4, 11);
        $outOfRangeCoordinates[11] = range(4, 11);

        if (array_key_exists($row, $outOfRangeCoordinates) && in_array($column, $outOfRangeCoordinates[$row])) {
            return true;
        }

        return false;
    }

    private function upload(string $urlTMP, string $filename): string
    {
        return (new Bucket())->uploadFile($urlTMP, $filename);
    }
}

<?php
namespace App\Service\Images\WeepingPlebs;

use App\Models\DataSeeder;
use App\Service\Bucket;
use App\Service\Images\BaseTextImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;
use App\Service\Traits\HasOptions;
use App\Service\Traits\HasOptionsPerId;

class TextImageCenteredWeepingPlebs extends BaseTextImage
{
    use HasIdRange;
    use HasOptions;
    use HasOptionsPerId;

    protected string $project = Projects::WEEPING_PLEBS;

    private string $text = '';

    protected string $name = 'Centered Text';

    protected string $description = 'Shout a few words with WeepingPlebs on top, and below that you can optionally specify NFTs by ID or attribute';

    private ?int $id = null;

    private string $type = '';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->idRange = range(1, 8888);
        $this->optionsPerId = DataSeeder::get(DataSeeder::WEEPING_PLEBS_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }

    public static function make(): TextImageCenteredWeepingPlebs
    {
        return new self();
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
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
        $image->newImage(800, 800, '#ffffff');
        $image->setImageFormat("png");

        $this->pasteWeepingPleb($image, 0 * 160, 0);
        $this->pasteWeepingPleb($image, 1 * 160, 0);
        $this->pasteWeepingPleb($image, 2 * 160, 0);
        $this->pasteWeepingPleb($image, 3 * 160, 0);
        $this->pasteWeepingPleb($image, 4 * 160, 0);

        $this->pasteWeepingPleb($image, 0 * 160, 800 - 160);
        $this->pasteWeepingPleb($image, 1 * 160, 800 - 160);
        $this->pasteWeepingPleb($image, 2 * 160, 800 - 160);
        $this->pasteWeepingPleb($image, 3 * 160, 800 - 160);
        $this->pasteWeepingPleb($image, 4 * 160, 800 - 160);

        if ($this->text) {
            $image->drawImage($this->createText($this->text));
        }

        $image->writeImage($urlTMP);

        return [
            'urlCDN' => $this->upload($urlTMP, $filename),
            'urlTMP' => $urlTMP,
        ];
    }

    private function pasteWeepingPleb(\Imagick &$image, int $x, int $y)
    {
        $color = '#ffffff';

        // background square
        $draw = new \ImagickDraw();
        $color = new \ImagickPixel($color);
        $draw->setFillColor($color);
        $draw->rectangle($x, $y + 160, $x + 160, $y);
        $image->drawImage($draw);

        $resize = 84; // will result in 160px width

        if ($this->id) {
            $id = $this->id;
        } elseif ($this->type) {
            $id = $this->getRandomIdForOption($this->type);
        } else {
            $id = null;
        }
        $image->compositeImage($this->getWeepingPlebFixedSize($id, $resize), \Imagick::COMPOSITE_ATOP, $x, $y);
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
        $draw->setFont(ROOT . "/assets/fonts/downtown_street-webfont.ttf");
        $draw->setFontSize($fontSize);
        $draw->setFillColor(new \ImagickPixel('#0C6DFD'));

        $y = 380;
        if ($fontSize >= 60) {
            $y = 420;
        }
        $draw->annotation(400, $y, $text);

        return $draw;
    }

    private function calculateFontSize(): int
    {
        $baseFontSize = 120;
        $minFontSize = 40;
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

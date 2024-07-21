<?php
namespace App\Service\Images\BaseAliens;

use App\Models\DataSeeder;
use App\Service\Bucket;
use App\Service\Images\BaseTextImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasOptions;
use App\Service\Traits\HasOptionsPerId;

class PropertyHighlightBaseAliensWithoutText extends BaseTextImage
{
    use HasOptions;
    use HasOptionsPerId;

    protected string $project = Projects::BASE_ALIENS;

    protected string $name = 'Random NFTs Repeat For Property';

    private string $type = '';

    public function __construct()
    {
        $this->optionsPerId = DataSeeder::get(DataSeeder::BASE_ALIENS_PROPERTIES_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }

    public static function make(): PropertyHighlightBaseAliensWithoutText
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
                $id = $this->getRandomIdForOption($this->type);
                $baseAlienFirst = $this->getBasePunkTransparent($id, 82.7);
                $image->compositeImage($baseAlienFirst, \Imagick::COMPOSITE_ATOP, $x, $y);
                $x += 100;
            }
            $y += 100;
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
}

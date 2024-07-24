<?php
namespace App\Service\Images\RipplePunks;

use App\Service\Bucket;
use App\Service\Images\BaseTextImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;

class LaserEyes extends BaseTextImage
{
    use HasIdRange;

    protected string $project = Projects::RIPPLE_PUNKS;

    protected string $name = 'Laser Eyes';

    protected string $description = 'Put some laser eyes on your RipplePunks';

    private string $type = '';

    private ?int $id = null;

    public function __construct()
    {
        $this->idRange = range(0, 9999);
    }

    public static function make(): LaserEyes
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
        $filename = uniqid() . '.png';
        $urlTMP = ROOT . '/tmp/' . $filename;

        if (!is_numeric($this->id)) {
            $this->id = $this->getRandomId();
        }

        $image = new \Imagick();
        $image->newImage(576, 576, '#ffffff');
        $image->setImageFormat("png");

        $image->compositeImage($this->getRipplePunkFixedSize($this->id), \Imagick::COMPOSITE_ATOP, 0, 0);

        $this->addLaserEyes($image);

        $image->writeImage($urlTMP);

        return [
            'urlCDN' => $this->upload($urlTMP, $filename),
            'urlTMP' => $urlTMP,
        ];
    }

    private function addLaserEyes(&$image): void
    {
        $isMale = false;
        $metadata = $this->getRipplePunksMetadata($this->id);
        foreach ($metadata['attributes'] as $attribute) {
            if ($attribute['trait_type'] === 'Type') {
                $isMale = ($attribute['value'] === 'Male') ||
                    ($attribute['value'] === 'Zombie') ||
                    ($attribute['value'] === 'Alien') ||
                    ($attribute['value'] === 'Ape');
                break;
            }
        }

        if ($isMale) {

            $imagick = new \Imagick(realpath('assets/images/lasereye.png'));
            $imagick->flipImage();
            $imagick->setImageBackgroundColor("gray");
            $imagick->resizeimage(700, 700, \Imagick::FILTER_LANCZOS, 1.0, true);
            $image->compositeImage($imagick, \Imagick::COMPOSITE_ATOP, -225, 200);

            $imagick = new \Imagick(realpath('assets/images/lasereye.png'));
            $imagick->flipImage();
            $imagick->setImageBackgroundColor("gray");
            $imagick->resizeimage(700, 700, \Imagick::FILTER_LANCZOS, 1.0, true);
            $image->compositeImage($imagick, \Imagick::COMPOSITE_ATOP, -105, 200);
        } else {

            $imagick = new \Imagick(realpath('assets/images/lasereye.png'));
            $imagick->flipImage();
            $imagick->setImageBackgroundColor("gray");
            $imagick->resizeimage(700, 700, \Imagick::FILTER_LANCZOS, 1.0, true);
            $image->compositeImage($imagick, \Imagick::COMPOSITE_ATOP, -225, 225);

            $imagick = new \Imagick(realpath('assets/images/lasereye.png'));
            $imagick->flipImage();
            $imagick->setImageBackgroundColor("gray");
            $imagick->resizeimage(700, 700, \Imagick::FILTER_LANCZOS, 1.0, true);
            $image->compositeImage($imagick, \Imagick::COMPOSITE_ATOP, -105, 225);
        }

    }

    private function upload(string $urlTMP, string $filename): string
    {
        return (new Bucket())->uploadFile($urlTMP, $filename);
    }
}

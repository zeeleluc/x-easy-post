<?php
namespace App\Service;

class BaseTextImage
{

    public function getRandomImageBaseAlienFirst(int $resize = 100): \Imagick
    {
        $tmpPath = $this->getTempImageFromCDN(
            'base-punks-png',
            rand(1, 4444) . '.png',
            'random-image-base-alien-first.png'
        );

        return $this->getImage(realpath($tmpPath), $resize);
    }

    public function getRandomImageBaseAlienSecond(int $resize = 100): \Imagick
    {
        $tmpPath = $this->getTempImageFromCDN(
            'base-punks-png',
            rand(1, 4444) . '.png',
            'random-image-base-alien-second.png'
        );

        return $this->getImage(realpath($tmpPath), $resize);
    }

    /**
     * @throws \ImagickException
     */
    public function getImageLuc(int $resize = 100): \Imagick
    {
        return $this->getImage(realpath('assets/images/luc.png'), $resize);
    }

    /**
     * @throws \ImagickException
     */
    public function getImageDianaFlip(int $resize = 100): \Imagick
    {
        return $this->getImage(realpath('assets/images/diana-flip.png'), $resize);
    }

    /**
     * @throws \ImagickException
     */
    private function getImage(string $path, int $resize = 100): \Imagick
    {
        $sizes = getimagesize($path);
        $width = $sizes[0];
        $height = $sizes[1];

        if ($resize < 100) {
            $width = $width - ($width * ($resize / 100));
            $height = $height - ($height * ($resize / 100));
        }

        $imagick = new \Imagick(realpath($path));
        $imagick->setImageBackgroundColor("gray");
        $imagick->resizeimage($width, $height, \Imagick::FILTER_LANCZOS, 1.0, true);

        return $imagick;
    }

    private function getTempImageFromCDN(string $slug, string $filename, string $localFilename): string
    {
        $remoteUrl = get_url_digital_ocean($slug, $filename);

        $path = ROOT . '/tmp/' . $localFilename;
        $image = file_get_contents($remoteUrl);
        file_put_contents($path, $image);
        chmod($path, 0777);

        return $path;
    }
}

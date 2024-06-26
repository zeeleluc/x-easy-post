<?php
namespace App\Service;

class BaseTextImage
{
    public function getBasePunkTransparent(int $id = null, float $resize = 100): \Imagick
    {
        if (!$id) {
            $ids = range(1, 4444);
            shuffle($ids);
            $id = $ids[0];
        }

        $tmpPath = $this->getTempImageFromCDN(
            'base-punks-transparent',
            $id . '.png',
            'basealien-transparent.png'
        );

        return $this->getImage(realpath($tmpPath), $resize);
    }

    public function getRipplePunkFixedSize(int $id = null, float $resize = 100): \Imagick
    {
        if (!$id) {
            $ids = range(0, 9999);
            shuffle($ids);
            $id = $ids[0];
        }

        $tmpPath = $this->getTempImageFromCDN(
            'ripplepunks-transparent',
            $id . '.png',
            'ripplepunk.png'
        );

        return $this->getImage(realpath($tmpPath), $resize);
    }

    public function getLooneyLuca(int $id, float $resize = 100): \Imagick
    {
        $tmpPath = $this->getTempImageFromCDN(
            'looney-luca-ether',
            $id . '.png',
            'looney-luca.png'
        );

        return $this->getImage(realpath($tmpPath), $resize);
    }

    public function getRandomImageBaseAlienFirst(float $resize = 100): \Imagick
    {
        $tmpPath = $this->getTempImageFromCDN(
            'base-punks-png',
            rand(1, 4444) . '.png',
            'random-image-base-alien-first.png'
        );

        return $this->getImage(realpath($tmpPath), $resize);
    }

    public function getRandomImageBaseAlienSecond(float $resize = 100): \Imagick
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
    public function getMoneyFaceEmoji(int $resize = 100): \Imagick
    {
        return $this->getImage(realpath('assets/images/money-face-emoji.png'), $resize);
    }

    /**
     * @throws \ImagickException
     */
    private function getImage(string $path, float $resize = 100): \Imagick
    {
        $sizes = getimagesize($path);
        $width = $sizes[0];
        $height = $sizes[1];

        if ($resize < 100) {
            $width = round($width - ($width * ($resize / 100)));
            $height = round($height - ($height * ($resize / 100)));
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

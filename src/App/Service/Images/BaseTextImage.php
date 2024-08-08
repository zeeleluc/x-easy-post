<?php
namespace App\Service\Images;

use App\Models\DataSeeder;

class BaseTextImage extends BaseImage
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

    public function getCryptoPunkTransparent(int $id = null, float $resize = 100): \Imagick
    {
        if (!$id) {
            $ids = range(0, 9999);
            shuffle($ids);
            $id = $ids[0];
        }

        $tmpPath = $this->getTempImageFromCDN(
            'cryptopunks-transparent-background',
            $id . '.png',
            'cryptopunks-transparent-background.png'
        );

        return $this->getImage(realpath($tmpPath), $resize);
    }

    public function getCryptoPunkFixedSize(int $id = null, int $width = 100, int $height = 100): \Imagick
    {
        if (!$id) {
            $ids = range(0, 9999);
            shuffle($ids);
            $id = $ids[0];
        }

        $tmpPath = $this->getTempImageFromCDN(
            'cryptopunks-transparent-background',
            $id . '.png',
            'cryptopunks-transparent-background.png'
        );

        return $this->getImageFixedSize(realpath($tmpPath), $width, $height);
    }

    public function getRipplePunkFixedSize(int $id = null, float $resize = 100): \Imagick
    {
        if (!is_numeric($id)) {
            $ids = range(0, 9999);
            shuffle($ids);
            $id = $ids[0];
        }

        $tmpPath = $this->getTempImageFromCDN(
            'ripplepunks',
            $id . '.png',
            'ripplepunk.png'
        );

        return $this->getImage(realpath($tmpPath), $resize);
    }

    public function getRipplePunkFixedSizeTransparent(int $id = null, float $resize = 100): \Imagick
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


    public function getWeepingPlebFixedSize(int $id = null, float $resize = 100): \Imagick
    {
        if (!$id) {
            $ids = range(1, 8888);
            shuffle($ids);
            $id = $ids[0];
        }

        $tmpPath = $this->getTempImageFromCDN(
            'weeping-plebs-v4',
            $id . '.png',
            'weepingpleb.png'
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
    private function getImageFixedSize(string $path, int $width = 100, int $height = 100): \Imagick
    {
        $imagick = new \Imagick(realpath($path));
        $imagick->setImageBackgroundColor("gray");
        $imagick->resampleImage(1150, 1150, \Imagick::FILTER_BESSEL, 0.1);
        $imagick->resizeimage($width, $height, \Imagick::FILTER_LANCZOS, 1.0, true);

        return $imagick;
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

    protected function getRipplePunksMetadata(int $id): array
    {
        $metadata = DataSeeder::get(DataSeeder::RIPPLE_PUNKS_METADATA);

        return $metadata[$id];
    }
}

<?php
namespace App\Service;

use Couchbase\ValueRecorder;

class ResolveImage
{
    public string $urlTMP;

    public string $urlCDN;

    public function __construct(
        private readonly string $imageTypeSlug,
        private ?int $id = null
    ) {
    }

    public static function make(string $imageTypeSlug, int $id = null): ResolveImage
    {
        return new self($imageTypeSlug, $id);
    }

    public function do():? ResolveImage
    {
        $getterMethod = 'get' . convert_snakecase_to_camelcase($this->imageTypeSlug, true);
        if (method_exists($this, $getterMethod)) {
            $images = $this->{$getterMethod}();
            $this->urlTMP = $images['urlTMP'];
            $this->urlCDN = $images['urlCDN'];
            return $this;
        }

        return null;
    }

    private function getLooneyLuca(): array
    {
        if (!$this->id) {
            $this->id = get_random_number(1, 10000);
        }

        return download_remote_url_and_return_temp_path('looney-luca-ether', $this->id . '.png');
    }

    public function getLoadingPunksNFT(): array
    {
        if (!$this->id) {
            $this->id = get_random_number(0, 9999);
        }

        return download_remote_url_and_return_temp_path('loadingpunks', get_uuid_for_loading_punk($this->id) . '.gif');
    }

    public function getLoadingPunksPixelCount(): array
    {
        if (!$this->id) {
            $this->id = get_random_number(0, 9999);
        }

        return download_remote_url_and_return_temp_path('loadingpunks-pixel-counts', get_uuid_for_loading_punk($this->id) . '.png');
    }

    public function getPipingPunksMoving(): array
    {
        if (!$this->id) {
            $this->id = get_random_number(0, 9999);
        }

        return download_remote_url_and_return_temp_path('pipingpunks-gif', $this->id . '.gif');
    }

    public function getPipingPunksNFT(): array
    {
        if (!$this->id) {
            $this->id = get_random_number(0, 9999);
        }

        return download_remote_url_and_return_temp_path('pipingpunks-png', $this->id . '.png');
    }

    public function getSOLpepens(): array
    {
        if (!$this->id) {
            $this->id = get_random_number(1, 10000);
        }

        return download_remote_url_and_return_temp_path('solpepens', $this->id . '.png');
    }

    public function getRipplePunks(): array
    {
        if (!$this->id) {
            $this->id = get_random_number(0, 9999);
        }

        return download_remote_url_and_return_temp_path('ripplepunks', $this->id . '.png');
    }

    public function getRipplePunksQR(): array
    {
        if (!$this->id) {
            $this->id = get_random_number(0, 9999);
        }

        return download_remote_url_and_return_temp_path('ripplepunks-qr', $this->id . '.png');
    }

    public function getTextCenteredOneLine(): array
    {

    }
}

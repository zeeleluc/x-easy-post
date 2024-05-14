<?php
namespace App\Service;

class ResolveImage
{
    public function __construct(
        private readonly string $imageTypeSlug,
        private ?int $id = null
    ) {
    }

    public static function make(string $imageTypeSlug, int $id = null): ResolveImage
    {
        return new self($imageTypeSlug, $id);
    }

    public function do():? string
    {
        if ($this->imageTypeSlug === 'looney_luca') {
            return $this->getLooneyLucaImage();
        }

        return null;
    }

    private function getLooneyLucaImage(): string
    {
        if (!$this->id) {
            $this->id = get_random_number(1, 10000);
        }

        return download_remote_url_and_return_temp_path('looney-luca-ether', $this->id . '.png');
    }
}

<?php
namespace App\Service;

class ResolveImage
{
    private ?int $id;

    private ?string $type;

    private ?string $text;

    public string $urlTMP;

    public string $urlCDN;

    public function __construct(
        private readonly string $imageTypeSlug,
        private readonly ?array $options = []
    ) {
        $this->id = $this->options['nft_id'];
        $this->text = $this->options['text'];
        $this->type = $this->options['type'];
    }

    public static function make(string $imageTypeSlug, array $options = []): ResolveImage
    {
        return new self($imageTypeSlug, $options);
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
            if ($this->type) {
                $this->id = get_random_looneyluca_id_for_type($this->type);
            } else {
                $this->id = get_random_number(1, 10000);
            }
        }

        return download_remote_url_and_return_temp_path('looney-luca-ether', $this->id . '.png');
    }

    public function getLoadingPunksNFT(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = get_random_cryptopunk_id_for_type($this->type);
            } else {
                $this->id = get_random_number(0, 9999);
            }
        }

        return download_remote_url_and_return_temp_path('loadingpunks', get_uuid_for_loading_punk($this->id) . '.gif');
    }

    public function getLoadingPunksPixelCount(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = get_random_cryptopunk_id_for_type($this->type);
            } else {
                $this->id = get_random_number(0, 9999);
            }
        }

        return download_remote_url_and_return_temp_path('loadingpunks-pixel-counts', get_uuid_for_loading_punk($this->id) . '.png');
    }

    public function getPipingPunksMoving(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = get_random_cryptopunk_id_for_type($this->type);
            } else {
                $this->id = get_random_number(0, 9999);
            }
        }

        return download_remote_url_and_return_temp_path('pipingpunks-gif', $this->id . '.gif');
    }

    public function getPipingPunksNFT(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = get_random_cryptopunk_id_for_type($this->type);
            } else {
                $this->id = get_random_number(0, 9999);
            }
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
            if ($this->type) {
                $this->id = get_random_cryptopunk_id_for_type($this->type);
            } else {
                $this->id = get_random_number(0, 9999);
            }
        }

        return download_remote_url_and_return_temp_path('ripplepunks', $this->id . '.png');
    }

    public function getRipplePunksQR(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = get_random_cryptopunk_id_for_type($this->type);
            } else {
                $this->id = get_random_number(0, 9999);
            }
        }

        return download_remote_url_and_return_temp_path('ripplepunks-qr', $this->id . '.png');
    }

    public function getBaseAliens(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = get_random_basealien_id_for_type($this->type);
            } else {
                $this->id = get_random_number(1, 4444);
            }
        }

        return download_remote_url_and_return_temp_path('base-punks-png', $this->id . '.png');
    }

    public function getBaseAliensMoving(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = get_random_basealien_id_for_type($this->type);
            } else {
                $this->id = get_random_number(1, 4444);
            }
        }

        return download_remote_url_and_return_temp_path('base-punks-gif', $this->id . '.gif');
    }

    public function getTextFourWordsLucDiana(): array
    {
        $textImage = new TextImageMaxFourSingleWordsLucDiana();
        $chunks = explode(' ', $this->text);
        if (isset($chunks[0])) {
            $textImage->textFirstRow($chunks[0]);
        }
        if (isset($chunks[1])) {
            $textImage->textSecondRow($chunks[1]);
        }
        if (isset($chunks[2])) {
            $textImage->textThirdRow($chunks[2]);
        }
        if (isset($chunks[3])) {
            $textImage->textFourthRow($chunks[3]);
        }

        return $textImage->render();
    }

    public function getTextCenteredBaseAliens(): array
    {
        $textImage = new TextImageCenteredBaseAliens();
        $textImage->setText($this->text);

        return $textImage->render();
    }

    public function getTextCenteredLooneyLuca(): array
    {
        $textImage = new TextImageCenteredLooneyLuca();
        $textImage->setText($this->text);

        return $textImage->render();
    }

    public function getTextCenteredRipplePunks(): array
    {
        $textImage = new TextImageCenteredRipplePunks();
        $textImage->setText($this->text);

        return $textImage->render();
    }

    public function getSimpleTextBlackBGWhiteText(): array
    {
        $textImage = new TextSimpleBlackBGWhiteText();
        $textImage->setText($this->text);

        return $textImage->render();
    }

    public function getSimpleTextWhiteBGBlackText(): array
    {
        $textImage = new TextSimpleWhiteBGBlackText();
        $textImage->setText($this->text);

        return $textImage->render();
    }

    public function getSimpleTextBaseAliensBlue(): array
    {
        $textImage = new TextSimpleBaseAliensBlue();
        $textImage->setText($this->text);

        return $textImage->render();
    }

    public function getSimpleTextLooneyLucaOrange(): array
    {
        $textImage = new TextSimpleLooneyLucaOrange();
        $textImage->setText($this->text);

        return $textImage->render();
    }

    public function getSimpleTextSOLpepensPurple(): array
    {
        $textImage = new TextSimpleSOLpepensPurple();
        $textImage->setText($this->text);

        return $textImage->render();
    }

    public function getSimpleTextRipplePunksBlue(): array
    {
        $textImage = new TextSimpleRipplePunksBlue();
        $textImage->setText($this->text);

        return $textImage->render();
    }

    public function getSimpleTextHasMintsBlue(): array
    {
        $textImage = new TextSimpleHasMintsBlue();
        $textImage->setText($this->text);

        return $textImage->render();
    }

    public function getTextAdRichLists(): array
    {
        $textImage = new TextAdRichLists();
        $textImage->setText($this->text);

        return $textImage->render();
    }
}

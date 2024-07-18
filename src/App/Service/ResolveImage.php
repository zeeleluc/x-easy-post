<?php
namespace App\Service;

use App\Service\Images\BaseAliens\Loading;
use App\Service\Images\BaseAliens\OpepenBaseAliens;
use App\Service\Images\BaseAliens\PropertyHighlightBaseAliens;
use App\Service\Images\BaseAliens\PropertyHighlightBaseAliensWithoutText;
use App\Service\Images\BaseAliens\PropertyHighlightBaseAliensWithoutTextAndMany;
use App\Service\Images\BaseAliens\PuzzleBaseAliensBlue;
use App\Service\Images\BaseAliens\Regular as BaseAlienRegular;
use App\Service\Images\BaseAliens\TextImageCenteredBaseAliens;
use App\Service\Images\BaseAliens\TextSimpleBaseAliensBlue;
use App\Service\Images\HasMints\PuzzleBlackBGWhiteText;
use App\Service\Images\HasMints\PuzzleHasMintsBlue;
use App\Service\Images\HasMints\PuzzleWhiteBGBlackText;
use App\Service\Images\HasMints\TextImageMaxFourSingleWordsLucDiana;
use App\Service\Images\HasMints\TextSimpleBlackBGWhiteText;
use App\Service\Images\HasMints\TextSimpleHasMintsBlue;
use App\Service\Images\HasMints\TextSimpleWhiteBGBlackText;
use App\Service\Images\LoadingPunks\HowManyPixels;
use App\Service\Images\LoadingPunks\Regular as RegularLoadingPunks;
use App\Service\Images\LooneyLuca\OpepenLooneyLuca;
use App\Service\Images\LooneyLuca\PuzzleLooneyLucaOrange;
use App\Service\Images\LooneyLuca\Regular as LooneyLucaRegular;
use App\Service\Images\LooneyLuca\TextImageCenteredLooneyLuca;
use App\Service\Images\LooneyLuca\TextSimpleLooneyLucaOrange;
use App\Service\Images\PipingPunks\MovingPipingPunk;
use App\Service\Images\RichLists\TextAdRichLists;
use App\Service\Images\RipplePunks\OpepenRipplePunks;
use App\Service\Images\RipplePunks\PropertyHighlightRipplePunksWithoutTextAndMany;
use App\Service\Images\RipplePunks\PuzzleRipplePunksBlue;
use App\Service\Images\RipplePunks\RegularQR;
use App\Service\Images\RipplePunks\RegularQuartet;
use App\Service\Images\RipplePunks\Regular as RipplePunksRegular;
use App\Service\Images\RipplePunks\RegularRewind;
use App\Service\Images\RipplePunks\RipplePunksQuartetSet;
use App\Service\Images\RipplePunks\TextImageCenteredRipplePunks;
use App\Service\Images\RipplePunks\TextSimpleRipplePunksBlue;

class ResolveImage
{
    private ?int $id;

    private ?string $type;

    private ?string $text;

    public string $urlTMP;

    public string $urlCDN;

    public function __construct(
        private readonly string $imageTypeSlug,
        private readonly string $project,
        private readonly ?array $options = []
    ) {
        $this->id = $this->options['nft_id'];
        $this->text = $this->options['text'];
        $this->type = $this->options['type'];
    }

    public static function make(string $imageTypeSlug, string $project, array $options = []): ResolveImage
    {
        return new self($imageTypeSlug, $project, $options);
    }

    public function do():? ResolveImage
    {
        $getterMethod = 'get' . convert_snakecase_to_camelcase($this->project . ' ' . $this->imageTypeSlug, true);

        if (method_exists($this, $getterMethod)) {
            $images = $this->{$getterMethod}();
            $this->urlTMP = $images['urlTMP'];
            $this->urlCDN = $images['urlCDN'];
            return $this;
        } else {
            echo 'Missing ResolveImage::<strong>' . $getterMethod . '()</strong><br /><br />';

            $getterMethod = 'get' . convert_snakecase_to_camelcase($this->imageTypeSlug, true);
            echo 'Before <strong>' . $getterMethod . '()</strong><br />';

            $getterMethod = 'get' . convert_snakecase_to_camelcase($this->project . ' ' . $this->imageTypeSlug, true);
            echo 'After <strong>' . $getterMethod . '()</strong>';
            exit;
        }

        return null;
    }

    private function getLooneyLucaRegular(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = (new LooneyLucaRegular())->getRandomIdForOption($this->type);
            } else {
                $this->id = (new LooneyLucaRegular())->getRandomId();
            }
        }

        return download_remote_url_and_return_temp_path('looney-luca-ether', $this->id . '.png');
    }

    public function getLoadingPunksRegular(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = (new RegularLoadingPunks())->getRandomIdForOption($this->type);
            } else {
                $this->id = (new RegularLoadingPunks())->getRandomId();
            }
        }

        return download_remote_url_and_return_temp_path('loadingpunks', get_uuid_for_loading_punk($this->id) . '.gif');
    }

    public function getLoadingPunksHowManyPixels(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = (new HowManyPixels())->getRandomIdForOption($this->type);
            } else {
                $this->id = (new HowManyPixels())->getRandomId();
            }
        }

        return download_remote_url_and_return_temp_path('loadingpunks-pixel-counts', get_uuid_for_loading_punk($this->id) . '.png');
    }

    public function getPipingPunksMovingPipingPunk(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = (new MovingPipingPunk())->getRandomIdForOption($this->type);
            } else {
                $this->id = (new MovingPipingPunk())->getRandomId();
            }
        }

        return download_remote_url_and_return_temp_path('pipingpunks-gif', $this->id . '.gif');
    }

    public function getPipingPunksRegular(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = (new MovingPipingPunk())->getRandomIdForOption($this->type);
            } else {
                $this->id = (new MovingPipingPunk())->getRandomId();
            }
        }

        return download_remote_url_and_return_temp_path('pipingpunks-png', $this->id . '.png');
    }

    public function getRipplePunksRegular(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = (new RipplePunksRegular())->getRandomIdForOption($this->type);
            } else {
                $this->id = (new RipplePunksRegular())->getRandomId();
            }
        }

        return download_remote_url_and_return_temp_path('ripplepunks', $this->id . '.png');
    }

    public function getRipplePunksRegularRewind(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = (new RegularRewind())->getRandomIdForOption($this->type);
            } else {
                $this->id = (new RegularRewind())->getRandomId();
            }
        }

        return download_remote_url_and_return_temp_path('ripplepunks-rewind', $this->id . '.gif');
    }


    public function getRipplePunksRegularQuartet(): array
    {
        if (!$this->id) {
            $this->id = (new RegularQuartet())->getRandomId();
        }

        return download_remote_url_and_return_temp_path('ripplepunks-quartet', $this->id . '.png');
    }

    public function getRipplePunksRipplePunksQuartetSet(): array
    {
        if (!$this->type) {
            $this->type = (new RipplePunksQuartetSet())->getRandomOption();
        }

        return download_remote_url_and_return_temp_path('ripplepunks-quartet-sets', $this->type . '.png');
    }

    public function getRipplePunksRegularQR(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = (new RegularQR())->getRandomIdForOption($this->type);
            } else {
                $this->id = (new RegularQR())->getRandomId();
            }
        }

        return download_remote_url_and_return_temp_path('ripplepunks-qr', $this->id . '.png');
    }

    public function getBaseAliensRegular(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = (new BaseAlienRegular())->getRandomIdForOption($this->type);
            } else {
                $this->id = (new BaseAlienRegular())->getRandomId();
            }
        }

        return download_remote_url_and_return_temp_path('base-punks-png', $this->id . '.png');
    }

    public function getBaseAliensLoading(): array
    {
        if (!$this->id) {
            if ($this->type) {
                $this->id = (new Loading())->getRandomIdForOption($this->type);
            } else {
                $this->id = (new Loading())->getRandomId();
            }
        }

        return download_remote_url_and_return_temp_path('base-punks-gif', $this->id . '.gif');
    }

    public function getHasMintsTextImageMaxFourSingleWordsLucDiana(): array
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

    public function getBaseAliensTextImageCenteredBaseAliens(): array
    {
        $textImage = new TextImageCenteredBaseAliens();
        $textImage->setText($this->text);
        if ($this->id) {
            $textImage->setId($this->id);
        }
        if ($this->type) {
            $textImage->setType($this->type);
        }

        return $textImage->render();
    }

    public function getLooneyLucaTextImageCenteredLooneyLuca(): array
    {
        $textImage = new TextImageCenteredLooneyLuca();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getRipplePunksTextImageCenteredRipplePunks(): array
    {
        $textImage = new TextImageCenteredRipplePunks();
        if ($this->text) {
            $textImage->setText($this->text);
        }
        if ($this->id) {
            $textImage->setId($this->id);
        }
        if ($this->type) {
            $textImage->setType($this->type);
        }

        return $textImage->render();
    }

    public function getHasMintsTextSimpleBlackBGWhiteText(): array
    {
        $textImage = new TextSimpleBlackBGWhiteText();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getHasMintsTextSimpleWhiteBGBlackText(): array
    {
        $textImage = new TextSimpleWhiteBGBlackText();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getBaseAliensTextSimpleBaseAliensBlue(): array
    {
        $textImage = new TextSimpleBaseAliensBlue();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getLooneyLucaTextSimpleLooneyLucaOrange(): array
    {
        $textImage = new TextSimpleLooneyLucaOrange();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getRipplePunksTextSimpleRipplePunksBlue(): array
    {
        $textImage = new TextSimpleRipplePunksBlue();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getHasMintsTextSimpleHasMintsBlue(): array
    {
        $textImage = new TextSimpleHasMintsBlue();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getRichListsTextAdRichLists(): array
    {
        $textImage = new TextAdRichLists();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getBaseAliensPropertyHighlightBaseAliens(): array
    {
        $textImage = new PropertyHighlightBaseAliens();
        if ($this->type) {
            $textImage->setType($this->type);
        } else {
            $textImage->setType($textImage->getRandomOption());
        }

        return $textImage->render();
    }

    public function getBaseAliensPropertyHighlightBaseAliensWithoutText(): array
    {
        $textImage = new PropertyHighlightBaseAliensWithoutText();
        if ($this->type) {
            $textImage->setType($this->type);
        } else {
            $textImage->setType($textImage->getRandomOption());
        }

        return $textImage->render();
    }

    public function getBaseAliensPropertyHighlightBaseAliensWithoutTextAndMany(): array
    {
        $textImage = new PropertyHighlightBaseAliensWithoutTextAndMany();
        if ($this->id) {
            $textImage->setId($this->id);
        }
        if ($this->type) {
            $textImage->setType($this->type);
        } else {
            $textImage->setType($textImage->getRandomOption());
        }

        return $textImage->render();
    }

    public function getRipplePunksPropertyHighlightRipplePunksWithoutTextAndMany(): array
    {
        $textImage = new PropertyHighlightRipplePunksWithoutTextAndMany();
        if ($this->id) {
            $textImage->setId($this->id);
        }
        if ($this->type) {
            $textImage->setType($this->type);
        } else {
            $textImage->setType($textImage->getRandomOption());
        }

        return $textImage->render();
    }

    public function getRipplePunksOpepenRipplePunks(): array
    {
        $textImage = new OpepenRipplePunks();
        if ($this->text) {
            $textImage->setText($this->text);
        }
        if ($this->id) {
            $textImage->setId($this->id);
        }
        if ($this->type) {
            $textImage->setType($this->type);
        }

        return $textImage->render();
    }

    public function getBaseAliensOpepenBaseAliens(): array
    {
        $textImage = new OpepenBaseAliens();
        if ($this->text) {
            $textImage->setText($this->text);
        }
        if ($this->id) {
            $textImage->setId($this->id);
        }
        if ($this->type) {
            $textImage->setType($this->type);
        }

        return $textImage->render();
    }

    public function getLooneyLucaOpepenLooneyLuca(): array
    {
        $textImage = new OpepenLooneyLuca();
        if ($this->text) {
            $textImage->setText($this->text);
        }
        if ($this->type) {
            $textImage->setType($this->type);
        }

        return $textImage->render();
    }

    public function getHasMintsPuzzleHasMintsBlue(): array
    {
        $textImage = new PuzzleHasMintsBlue();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getBaseAliensPuzzleBaseAliensBlue(): array
    {
        $textImage = new PuzzleBaseAliensBlue();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getHasMintsPuzzleBlackBGWhiteText(): array
    {
        $textImage = new PuzzleBlackBGWhiteText();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getLooneyLucaPuzzleLooneyLucaOrange(): array
    {
        $textImage = new PuzzleLooneyLucaOrange();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getRipplePunksPuzzleRipplePunksBlue(): array
    {
        $textImage = new PuzzleRipplePunksBlue();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }

    public function getHasMintsPuzzleWhiteBGBlackText(): array
    {
        $textImage = new PuzzleWhiteBGBlackText();
        if ($this->text) {
            $textImage->setText($this->text);
        }

        return $textImage->render();
    }
}

<?php

namespace App\FormFieldValidator;

class Image extends FormFieldValidator
{

    public function validate(): void
    {
        if ($this->value) {
            $validOption = false;
            foreach (self::getOptions() as $option) {
                if (flatten_string($option) === $this->value) {
                    $validOption = true;
                    break;
                }
            }

            if (!$validOption) {
                $this->setMessage('Image option invalid');
            }
        }
    }

    public static function getOptions(): array
    {
        return [
            'Looney Luca',
            'LoadingPunks NFT',
            'LoadingPunks Pixel Count',
            'PipingPunks NFT',
            'PipingPunks Moving',
            'RipplePunks',
            'RipplePunks QR',
            'BaseAliens',
            'BaseAliens Moving',
            'SOLpepens',
            'OpepenRipplePunks',
            'OpepenBaseAliens',
            'OpepenLooneyLuca',
            'PuzzleHasMintsBlue',
            'PuzzleBaseAliensBlue',
            'PuzzleBlackBGWhiteText',
            'PuzzleLooneyLucaOrange',
            'PuzzleRipplePunksBlue',
            'PuzzleSOLpepensPurple',
            'PuzzleWhiteBGBlackText',
            'Text Four Words Luc Diana',
            'Text Centered BaseAliens',
            'Text Centered LooneyLuca',
            'Text Centered RipplePunks',
            'Simple Text HasMints Blue',
            'Simple Text Black BG White Text',
            'Simple Text White BG Black Text',
            'Simple Text Base Aliens Blue',
            'Simple Text LooneyLuca Orange',
            'Simple Text SOLpepens Purple',
            'Simple Text RipplePunks Blue',
            'Text Ad RichLists',
            'Property Highlight BaseAliens',
            'Property Highlight BaseAliens Without Text',
            'Property Highlight BaseAliens Without Text And Many',
            'Property Highlight RipplePunks Without Text And Many',
        ];
    }
}

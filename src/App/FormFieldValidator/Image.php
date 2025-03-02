<?php

namespace App\FormFieldValidator;

use App\Service\Images\BaseImage;
use App\Service\Images\ImagesHelper;

class Image extends FormFieldValidator
{

    public function validate(): void
    {
//        if ($this->value) {
//            $validOption = false;
//            foreach (self::getOptions() as $option) {
//                if (flatten_string($option) === $this->value) {
//                    $validOption = true;
//                    break;
//                }
//            }
//
//            if (!$validOption) {
//                $this->setMessage('Image option invalid');
//            }
//        }
    }

    public static function getOptions(): array
    {
        $options = [];

        foreach (ImagesHelper::getAllImageClasses() as $imageClass) { /* @var $imageClass BaseImage */
            $options[] = $imageClass::getSlug();
        }

        return $options;
    }
}

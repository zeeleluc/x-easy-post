<?php

namespace App\FormFieldValidator;

class Type extends FormFieldValidator
{

    public function validate(): void
    {
        if ($this->value) {
            $validOption = false;
            foreach ($this->getAllTypes() as $type) {
                if ($type === $this->value) {
                    $validOption = true;
                    break;
                }
            }

            if (!$validOption) {
                $this->setMessage('Type option invalid');
            }
        }
    }

    public function getAllTypes(): array
    {
        if (!$this->image) {
            return [];
        }

        return $this->image::getOptions();
    }

    public static function getTypesCryptoPunks(): array
    {
        return array_keys(get_cryptopunks_types_and_ids());
    }

    public static function getTypesLooneyLuca(): array
    {
        return array_keys(get_looneyluca_types_and_ids());
    }

    public static function getTypesBaseAliens(): array
    {
        return array_keys(get_basealiens_types_and_ids());
    }
}

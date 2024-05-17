<?php

namespace App\FormFieldValidator;

class Type extends FormFieldValidator
{

    public function validate(): void
    {
        if ($this->value) {
            $validOption = false;
            foreach (self::getAllTypes() as $type) {
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

    public static function getAllTypes(): array
    {
        $allTypes = [];

        foreach (array_keys(get_cryptopunks_types_and_ids()) as $type) {
            $allTypes[] = $type;
        }

        foreach (array_keys(get_looneyluca_types_and_ids()) as $type) {
            $allTypes[] = $type;
        }

        return $allTypes;
    }

    public static function getTypesCryptoPunks(): array
    {
        return array_keys(get_cryptopunks_types_and_ids());
    }

    public static function getTypesLooneyLuca(): array
    {
        return array_keys(get_looneyluca_types_and_ids());
    }
}

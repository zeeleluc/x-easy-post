<?php

namespace App\FormFieldValidator;

class Type extends FormFieldValidator
{

    public function validate(): void
    {
        if ($this->value) {
            $validOption = false;
            foreach (self::getTypes() as $type) {
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

    public static function getTypes(): array
    {
        $allTypes = [];
        foreach (array_keys(get_cryptopunks_types_and_ids()) as $type) {
            $allTypes[] = $type;
        }

        return $allTypes;
    }
}

<?php

namespace App\FormFieldValidator;

class Type extends FormFieldValidator
{

    public function validate(): void
    {
        if ($this->value) {
            $validOption = false;
            foreach ($this->getAllTypes() as $typeKey => $typeValue) {
                if ($typeKey === $this->value) {
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
}

<?php

namespace App\FormFieldValidator;

class RegularString extends FormFieldValidator
{

    public function validate(): void
    {
        if (!is_string($this->value) || $this->value === '') {
            $this->setMessage('Text missing');
        }
    }
}

<?php

namespace App\FormFieldValidator;

class RegularString extends FormFieldValidator
{

    public function validate(): void
    {
        if (!is_string($this->value)) {
            $this->setMessage('Project name is incorrect.');
        }

        if (ctype_digit($this->value)) {
            $this->setMessage('Project name cannot contain only numbers.');
        }
    }
}

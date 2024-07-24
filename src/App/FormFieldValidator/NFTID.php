<?php

namespace App\FormFieldValidator;

class NFTID extends FormFieldValidator
{

    public function validate(): void
    {
        if (!is_numeric($this->value)) {
            $this->setMessage('Invalid NFT ID');
        }
    }
}

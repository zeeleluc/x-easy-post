<?php

namespace App\FormFieldValidator;

class NFTID extends FormFieldValidator
{

    public function validate(): void
    {
        if (!is_numeric($this->value)) {
            $this->setMessage('Invalid NFT ID');
        } else {
            if (!in_array($this->value, $this->image::getIdRange())) {
                $this->setMessage('Invalid NFT ID');
            }
        }
    }
}

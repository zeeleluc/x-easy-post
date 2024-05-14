<?php

namespace App\FormFieldValidator;

use App\Query\PostQuery;

class NFTID extends FormFieldValidator
{

    public function validate(): void
    {
        if ($this->value && !is_numeric($this->value)) {
            $this->setMessage('Invalid NFT ID');
        }
    }
}

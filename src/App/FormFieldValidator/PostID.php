<?php

namespace App\FormFieldValidator;

use App\Query\PostQuery;

class PostID extends FormFieldValidator
{

    public function validate(): void
    {
        if ($this->value) {
            if (!is_numeric($this->value)) {
                $this->setMessage('Invalid Post ID');
            }

            if (strlen($this->value) !== 19) {
                $this->setMessage('Invalid Post ID');
            }

            $pattern = '/^\d{19}$/';
            if (!preg_match($pattern, $this->value)) {
                $this->setMessage('Invalid Post ID');
            }

            if (!$this->getMessages()) {
                if ((new PostQuery())->doesPostExistForPostId($this->value)) {
                    $this->setMessage('Already replied on this post');
                }
            }
        }
    }
}

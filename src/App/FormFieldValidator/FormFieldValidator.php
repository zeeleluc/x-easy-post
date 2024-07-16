<?php

namespace App\FormFieldValidator;

use App\Service\Images\BaseImage;

abstract class FormFieldValidator
{
    private array $messages = [];

    public function __construct(
        public readonly string $key,
        public readonly string $value,
        public readonly ?BaseImage $image = null
    ) {
    }

    abstract public function validate();

    public function isValid(): bool
    {
        return count($this->messages) === 0;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    protected function setMessage(string $message): void
    {
        if (!in_array($message, $this->messages)) {
            $this->messages[] = $message;
        }
    }
}

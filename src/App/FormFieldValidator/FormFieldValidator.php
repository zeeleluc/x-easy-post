<?php

namespace App\FormFieldValidator;

abstract class FormFieldValidator
{
    private array $messages = [];

    public function __construct(
        public readonly string $key,
        public readonly string $value
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
        $this->messages[] = $message;
    }
}

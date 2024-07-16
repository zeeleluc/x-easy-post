<?php

namespace App\Service\Traits;

trait HasOptions
{
    public function getRandomOption(): string
    {
        $options = $this->options;
        shuffle($options);

        return $options[0];
    }
}

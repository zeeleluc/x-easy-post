<?php

namespace App\Service\Traits;

trait HasOptionsPerId
{
    public function getRandomIdForOption(string $option): int
    {
        $optionsPerId = $this->optionsPerId;
        $options = $optionsPerId[$option];
        shuffle($options);

        return (int) $options[0];
    }
}

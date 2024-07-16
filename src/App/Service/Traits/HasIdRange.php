<?php

namespace App\Service\Traits;

trait HasIdRange
{
    public function getRandomId(): int
    {
        $idRange = self::getIdRange();
        shuffle($idRange);

        return (int) $idRange[0];
    }
}

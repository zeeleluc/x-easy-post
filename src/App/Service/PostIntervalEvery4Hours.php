<?php
namespace App\Service;

use Carbon\Carbon;

class PostIntervalEvery4Hours
{
    private array $postHours = [
        0, 4, 8, 12, 16, 20,
    ];

    public static function skipIntervalsFromNow(int $skipping): Carbon
    {
        $now = now();
        var_dump($now->hour);

        return $now;
    }
}

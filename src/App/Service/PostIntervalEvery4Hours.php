<?php
namespace App\Service;

use Carbon\Carbon;

class PostIntervalEvery4Hours
{
    private array $postHours = [
        0 => 2,
        1 => 2,
        2 => 4,
        3 => 4,
        4 => 6,
        5 => 6,
        6 => 8,
        7 => 8,
        8 => 10,
        9 => 10,
        10 => 12,
        11 => 12,
        12 => 14,
        13 => 14,
        14 => 16,
        15 => 16,
        16 => 18,
        17 => 18,
        18 => 20,
        19 => 20,
        20 => 22,
        21 => 22,
        22 => 24,
        23 => 24,
    ];

    public function skipIntervalsFromNow(int $skipRounds): Carbon
    {
        $now = Carbon::now()->subHours(4);
        $nextSlot = $this->postHours[$now->hour];
        $nextSlot = $now->addHours($nextSlot - $now->hour);
        $nextSlot->addHours($skipRounds * 6);

        return $nextSlot;
    }
}

<?php
namespace App\Service;

use Carbon\Carbon;

class PostIntervalEvery4Hours
{
    private array $postHours = [
        0 => 4,
        1 => 4,
        2 => 4,
        3 => 4,
        4 => 8,
        5 => 8,
        6 => 8,
        7 => 8,
        8 => 12,
        9 => 12,
        10 => 12,
        11 => 12,
        12 => 16,
        13 => 16,
        14 => 16,
        15 => 16,
        16 => 20,
        17 => 20,
        18 => 20,
        19 => 20,
        20 => 24,
        21 => 24,
        22 => 24,
        23 => 24,
    ];

    public function skipIntervalsFromNow(int $skipRounds): Carbon
    {
        $now = Carbon::now()->subHours(4);
//        $nextSlot = $this->postHours[$now->hour];
//        $nextSlot = $now->addHours($nextSlot - $now->hour);
//        $nextSlot->addHours($skipRounds * 4);

//        if ($now->hour < 7) {
//            return $now->addDays($skipRounds - 1);
//        } else {
            return $now->addDays($skipRounds + 1);
//        }

//        return $nextSlot;
    }
}

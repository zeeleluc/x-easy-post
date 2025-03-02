<?php
namespace App\Service;

use Carbon\Carbon;

class PostIntervalEvery4Hours
{
//    private array $postHours = [
//        0 => 6,
//        1 => 6,
//        2 => 6,
//        3 => 6,
//        4 => 6,
//        5 => 6,
//        6 => 12,
//        7 => 12,
//        8 => 12,
//        9 => 12,
//        10 => 12,
//        11 => 12,
//        12 => 18,
//        13 => 18,
//        14 => 18,
//        15 => 18,
//        16 => 18,
//        17 => 18,
//        18 => 24,
//        19 => 24,
//        20 => 24,
//        21 => 24,
//        22 => 24,
//        23 => 24,
//    ];

    public function skipIntervalsFromNow(int $skipRounds): Carbon
    {
        $now = Carbon::now();
        $nextSlot = 15;
        $nextSlot = $now->addHours($nextSlot - $now->hour);
        $nextSlot->addDays($skipRounds);

        return $nextSlot;
    }
}

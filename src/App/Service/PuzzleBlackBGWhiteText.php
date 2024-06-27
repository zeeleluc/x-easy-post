<?php
namespace App\Service;

class PuzzleBlackBGWhiteText extends BasePuzzle
{

    public function __construct()
    {
        $this->backgroundColor = '#111111';
        $this->textColor = '#efefef';
    }
}

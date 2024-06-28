<?php
namespace App\Service;

class PuzzleWhiteBGBlackText extends BasePuzzle
{

    public function __construct()
    {
        $this->backgroundColor = '#efefef';
        $this->textColor = '#111111';
        $this->textColorFaded = '#cccccc';
    }
}

<?php
namespace App\Service\Images\HasMints;

use App\Service\Images\BasePuzzle;
use App\Service\Projects\Projects;

class PuzzleBlackBGWhiteText extends BasePuzzle
{
    protected string $project = Projects::HAS_MINTS;

    protected string $name = 'Puzzle Black Background, White Text';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#111111';
        $this->textColor = '#efefef';
        $this->textColorFaded = '#333333';
    }
}

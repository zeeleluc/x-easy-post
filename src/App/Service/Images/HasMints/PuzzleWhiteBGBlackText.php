<?php
namespace App\Service\Images\HasMints;

use App\Service\Images\BasePuzzle;
use App\Service\Projects\Projects;

class PuzzleWhiteBGBlackText extends BasePuzzle
{
    protected string $project = Projects::HAS_MINTS;

    protected string $name = 'Puzzle White Background, Black Text';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#efefef';
        $this->textColor = '#111111';
        $this->textColorFaded = '#cccccc';
    }
}

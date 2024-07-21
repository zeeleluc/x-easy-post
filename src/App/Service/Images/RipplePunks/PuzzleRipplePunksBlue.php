<?php
namespace App\Service\Images\RipplePunks;

use App\Service\Images\BasePuzzle;
use App\Service\Projects\Projects;

class PuzzleRipplePunksBlue extends BasePuzzle
{
    protected string $project = Projects::RIPPLE_PUNKS;

    protected string $name = 'Puzzle';

    protected string $description = 'A creative way to showcase something about this project';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#C5E5F7';
        $this->textColor = '#111111';
        $this->textColorFaded = '#a4cade';
    }
}

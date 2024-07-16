<?php
namespace App\Service\Images\HasMints;

use App\Service\Images\BasePuzzle;
use App\Service\Projects\Projects;

class PuzzleHasMintsBlue extends BasePuzzle
{
    protected string $project = Projects::HAS_MINTS;

    protected string $name = 'Puzzle HasMints Blue';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#C0DBEC';
        $this->textColor = '#013D62';
        $this->textColorFaded = '#9bbccc';
    }
}

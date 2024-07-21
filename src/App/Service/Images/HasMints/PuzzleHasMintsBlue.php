<?php
namespace App\Service\Images\HasMints;

use App\Service\Images\BasePuzzle;
use App\Service\Projects\Projects;

class PuzzleHasMintsBlue extends BasePuzzle
{
    protected string $project = Projects::HAS_MINTS;

    protected string $name = 'Puzzle HasMints Blue';

    protected string $description = 'A creative way to showcase something about this project';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#C0DBEC';
        $this->textColor = '#013D62';
        $this->textColorFaded = '#9bbccc';
    }
}

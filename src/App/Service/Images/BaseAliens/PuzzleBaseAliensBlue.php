<?php
namespace App\Service\Images\BaseAliens;

use App\Service\Images\BasePuzzle;
use App\Service\Projects\Projects;

class PuzzleBaseAliensBlue extends BasePuzzle
{
    protected string $project = Projects::BASE_ALIENS;

    protected string $name = 'Puzzle';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#3250FC';
        $this->textColor = '#efefef';
        $this->textColorFaded = '#327cfc';
    }
}

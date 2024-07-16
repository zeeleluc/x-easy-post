<?php
namespace App\Service\Images\LooneyLuca;

use App\Service\Images\BasePuzzle;
use App\Service\Projects\Projects;

class PuzzleLooneyLucaOrange extends BasePuzzle
{
    protected string $project = Projects::LOONEY_LUCA;

    protected string $name = 'Puzzle';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#FB6C07';
        $this->textColor = '#efefef';
        $this->textColorFaded = '#f68903';
    }
}

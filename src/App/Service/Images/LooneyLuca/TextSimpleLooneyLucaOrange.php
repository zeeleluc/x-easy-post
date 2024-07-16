<?php
namespace App\Service\Images\LooneyLuca;

use App\Service\Images\BaseSimpleTextImage;
use App\Service\Projects\Projects;

class TextSimpleLooneyLucaOrange extends BaseSimpleTextImage
{
    protected string $project = Projects::LOONEY_LUCA;

    protected string $name = 'Simple Text';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#FB6C07';
        $this->textColor = '#efefef';
    }
}

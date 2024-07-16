<?php
namespace App\Service\Images\HasMints;

use App\Service\Images\BaseSimpleTextImage;
use App\Service\Projects\Projects;

class TextSimpleBlackBGWhiteText extends BaseSimpleTextImage
{
    protected string $project = Projects::HAS_MINTS;

    protected string $name = 'Simple Text Black Background, White Text';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#111111';
        $this->textColor = '#efefef';
    }
}

<?php
namespace App\Service\Images\RipplePunks;

use App\Service\Images\BaseSimpleTextImage;
use App\Service\Projects\Projects;

class TextSimpleRipplePunksBlue extends BaseSimpleTextImage
{
    protected string $project = Projects::RIPPLE_PUNKS;

    protected string $name = 'Simple Text';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#C5E5F7';
        $this->textColor = '#111111';
    }
}

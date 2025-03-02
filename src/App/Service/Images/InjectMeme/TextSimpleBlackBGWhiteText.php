<?php
namespace App\Service\Images\InjectMeme;

use App\Service\Images\BaseSimpleTextImageInjectMeme;
use App\Service\Projects\Projects;

class TextSimpleBlackBGWhiteText extends BaseSimpleTextImageInjectMeme
{
    protected string $project = Projects::INJECT_MEME;

    protected string $name = 'Simple Text Black Background, White Text';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#111111';
        $this->textColor = '#efefef';
    }
}

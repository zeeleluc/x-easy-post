<?php
namespace App\Service;

class TextSimpleBlackBGWhiteText extends BaseSimpleTextImage
{

    public function __construct()
    {
        $this->backgroundColor = '#111111';
        $this->textColor = '#efefef';
    }
}

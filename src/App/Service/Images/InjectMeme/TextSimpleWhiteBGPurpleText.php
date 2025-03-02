<?php
namespace App\Service\Images\InjectMeme;

use App\Service\Images\BaseSimpleTextImageInjectMeme;
use App\Service\Projects\Projects;

class TextSimpleWhiteBGPurpleText extends BaseSimpleTextImageInjectMeme
{
    protected string $project = Projects::INJECT_MEME;

    protected string $name = 'Simple Text White Background, Purple Text';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#efefef';
        $this->textColor = '#9542DC';
    }
}

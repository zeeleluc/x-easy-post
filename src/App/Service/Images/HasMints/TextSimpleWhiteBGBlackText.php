<?php
namespace App\Service\Images\HasMints;

use App\Service\Images\BaseSimpleTextImage;
use App\Service\Projects\Projects;

class TextSimpleWhiteBGBlackText extends BaseSimpleTextImage
{
    protected string $project = Projects::HAS_MINTS;

    protected string $name = 'Simple Text White Background, Black Text';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#efefef';
        $this->textColor = '#111111';
    }
}

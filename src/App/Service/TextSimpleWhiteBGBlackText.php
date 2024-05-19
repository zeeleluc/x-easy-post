<?php
namespace App\Service;

class TextSimpleWhiteBGBlackText extends BaseSimpleTextImage
{

    public function __construct()
    {
        $this->backgroundColor = '#efefef';
        $this->textColor = '#111111';
    }
}

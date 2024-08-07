<?php
namespace App\Service\Images\BaseAliens;

use App\Service\Images\BaseSimpleTextImage;
use App\Service\Projects\Projects;

class TextSimpleBaseAliensBlue extends BaseSimpleTextImage
{
    protected string $project = Projects::BASE_ALIENS;

    protected string $name = 'Simple Text';

    protected string $description = 'Plain text over the project\'s signature color';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#3250FC';
        $this->textColor = '#efefef';
    }
}

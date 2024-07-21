<?php
namespace App\Service\Images\HasMints;

use App\Service\Images\BaseSimpleTextImage;
use App\Service\Projects\Projects;

class TextSimpleHasMintsBlue extends BaseSimpleTextImage
{
    protected string $project = Projects::HAS_MINTS;

    protected string $name = 'Simple Text HasMints Blue';

    protected string $description = 'Plain text over the project\'s signature color';

    public function __construct()
    {
        $this->canHaveImageText = true;
        $this->backgroundColor = '#C0DBEC';
        $this->textColor = '#013D62';
    }
}

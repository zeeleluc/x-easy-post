<?php
namespace App\Template;

use App\Object\BaseObject;

class Layout extends BaseObject
{
    private string $layoutName;

    public function setLayoutName(string $layoutName): Layout
    {
        $this->layoutName = $layoutName;

        return $this;
    }

    public function getLayoutName(): string
    {
        return $this->layoutName;
    }
}

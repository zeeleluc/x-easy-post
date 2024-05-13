<?php
namespace App\Template;

use App\Object\BaseObject;

class View extends BaseObject
{
    private string $viewName;

    public function setViewName(string $viewName): View
    {
        $this->viewName = $viewName;

        return $this;
    }

    public function getViewName(): string
    {
        return $this->viewName;
    }

}

<?php
namespace App\Action;

use App\Object\BaseObject;

class Action extends BaseObject
{
    private BaseAction $action;

    private array $result = [];

    public function setAction(BaseAction $action): void
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setResult(array $result): void
    {
        $this->result = $result;
    }

    public function getResult(): array
    {
        return $this->result;
    }
}

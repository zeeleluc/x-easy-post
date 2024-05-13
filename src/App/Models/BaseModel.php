<?php

namespace App\Models;

abstract class BaseModel
{

    abstract public function initNew(array $values);

    abstract public function fromArray(array $values);

    abstract public function toArray();

    abstract public function save();

    abstract public function update();

    abstract public function delete();

    abstract public function getQueryObject();
}

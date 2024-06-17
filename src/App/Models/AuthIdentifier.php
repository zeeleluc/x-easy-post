<?php

namespace App\Models;

use App\Query\AuthIdentifierQuery;
use ArrayHelpers\Arr;
use Carbon\Carbon;

class AuthIdentifier extends BaseModel
{

    public ?int $id = null;

    public string $authIdentifier;

    public ?Carbon $createdAt = null;

    /**
     * @throws \Exception
     */
    public function initNew(array $values): AuthIdentifier
    {
        $authIdentifier = $this->fromArray($values);

        return $authIdentifier->save();
    }

    public function fromArray(array $values): AuthIdentifier
    {
        $authIdentifier = new $this;
        if ($id = Arr::get($values, 'id')) {
            $authIdentifier->id = $id;
        }
        $authIdentifier->authIdentifier = Arr::get($values, 'auth_identifier');
        if ($createdAt = Arr::get($values, 'created_at')) {
            $authIdentifier->createdAt = Carbon::parse($createdAt);
        }

        return $authIdentifier;
    }

    public function isValid(): bool
    {
        $result = $this->getQueryObject()->getAuthIdentifierByAuthIdentifier($this->authIdentifier);

        if ($result) {
            return true;
        }

        return false;
    }

    public function toArray(): array
    {
        $array = [];

        if ($this->id) {
            $array['id'] = $this->id;
        }
        $array['auth_identifier'] = $this->authIdentifier;
        if ($this->createdAt) {
            $array['created_at'] = $this->createdAt;
        }

        return $array;
    }

    /**
     * @throws \Exception
     */
    public function save(): AuthIdentifier
    {
        return $this->create();
    }

    /**
     * @throws \Exception
     */
    public function create()
    {
        return $this->getQueryObject()->createNewAuthIdentifier($this->toArray());
    }

    public function update()
    {
        throw new \Exception('Not implemented');
    }

    public function delete()
    {
        $this->getQueryObject()->deleteAuthIdentifier($this);
    }

    public function getQueryObject()
    {
        return new AuthIdentifierQuery();
    }
}

<?php
namespace App\Object;

use App\Action\Action;
use App\Query\AuthIdentifierQuery;
use App\Query\ImageQuery;
use App\Query\PostQuery;
use App\Request;
use App\Session;

abstract class BaseObject
{
    private string $objectName;

    private ObjectManager $objectManager;

    public function setObjectManager(ObjectManager $objectManager): void
    {
        $this->objectManager = $objectManager;
    }

    public function getObjectManager(): ObjectManager
    {
        return $this->objectManager;
    }

    public function getPostQuery(): PostQuery
    {
        return ObjectManager::getOne('App\Query\PostQuery');
    }

    public function getImageQuery(): ImageQuery
    {
        return ObjectManager::getOne('App\Query\ImageQuery');
    }

    public function getAuthIdentifierQuery(): AuthIdentifierQuery
    {
        return ObjectManager::getOne('App\Query\AuthIdentifierQuery');
    }

    public function getRequest(): Request
    {
        return ObjectManager::getOne('App\Request');
    }

    public function getSession(): Session
    {
        return ObjectManager::getOne('App\Session');
    }

    public function getAbstractAction(): Action
    {
        return ObjectManager::getOne('App\Action\Action');
    }
}

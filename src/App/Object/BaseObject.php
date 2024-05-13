<?php
namespace App\Object;

use App\Action\Action;
use App\Query\BlockchainTokenQuery;
use App\Query\CollectionQuery;
use App\Query\UserQuery;
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

    public function getBlockchainTokenQuery(): BlockchainTokenQuery
    {
        return ObjectManager::getOne('App\Query\BlockchainTokenQuery');
    }

    public function getCollectionQuery(): CollectionQuery
    {
        return ObjectManager::getOne('App\Query\CollectionQuery');
    }

    public function getUserQuery(): UserQuery
    {
        return ObjectManager::getOne('App\Query\UserQuery');
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

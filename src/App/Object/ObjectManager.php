<?php
namespace App\Object;

use App\Action\Action;
use App\Query\AuthIdentifierQuery;
use App\Query\PostQuery;
use App\Request;
use App\Session;

class ObjectManager
{
    static array $objects = [];

    public static function getAll(): array
    {
        return self::$objects;
    }

    public static function set($object): void
    {
        self::$objects[get_class($object)] = $object;
    }

    /**
     * @throws \Exception
     */
    public static function getOne($objectName):
        BaseObject|
        Action|
        Session|
        Request|
        PostQuery|
        AuthIdentifierQuery
    {
        if (false === array_key_exists($objectName, self::getAll())) {
            throw new \Exception(sprintf('Object \'%s\' not found.', $objectName));
        }

        return self::$objects[$objectName];
    }
}

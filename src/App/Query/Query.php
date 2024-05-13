<?php
namespace App\Query;

use App\Object\BaseObject;

abstract class Query extends BaseObject
{
    protected \MysqliDb $db;

    public function __construct()
    {
        $this->db = $this->db();
    }

    private function db(): \MysqliDb
    {
        return new \MysqliDb(
            env('DB_HOST'),
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_DBNAME')
        );
    }
}

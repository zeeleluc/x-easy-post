<?php
namespace App\Template;

use App\Object\BaseObject;

abstract class Configuration extends BaseObject
{
    public function layouts()
    {
        return [
            'default',
            'hypeomatic',
            'error',
            'async',
        ];
    }

    public function views()
    {
        return [
            'clean' => [
                'dynamic-form-elements',
            ],
            'website' => [
                'home',
                'login',
                'ripplepunks',
            ],
            'hypeomatic' => [
                'home',
            ],
        ];
    }

}

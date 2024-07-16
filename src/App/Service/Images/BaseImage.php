<?php

namespace App\Service\Images;

class BaseImage
{
    protected ?array $idRange = null;

    protected ?bool $canHaveImageText = false;

    protected ?array $options = [];

    protected ?array $optionsPerId = [];

    public static function getClassName(): string
    {
        $calledClass = get_called_class();

        return basename(str_replace('\\', '/', $calledClass));
    }

    public static function getProject(): string
    {
        $calledClass = get_called_class();

        return (new $calledClass())->project;
    }

    public static function getName(): string
    {
        $calledClass = get_called_class();

        return (new $calledClass())->name;
    }

    public static function getSlug(): string
    {
        return flatten_string(self::getClassName());
    }

    public static function getIdRange():? array
    {
        $calledClass = get_called_class();

        return (new $calledClass())->idRange;
    }

    public static function canHaveImageText():? bool
    {
        $calledClass = get_called_class();

        return (new $calledClass())->canHaveImageText;
    }

    public static function getOptions():? array
    {
        $calledClass = get_called_class();

        return (new $calledClass())->options;
    }

    public static function getOptionsPerId():? array
    {
        $calledClass = get_called_class();

        return (new $calledClass())->optionsPerId;
    }

}

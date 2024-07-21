<?php

namespace App\Service\Images;

use App\Service\Projects\Projects;

class BaseImage
{
    protected ?array $idRange = null;

    protected ?bool $canHaveImageText = false;

    protected ?array $options = [];

    protected ?array $optionsPerId = [];

    protected string $imageExtension = 'png';

    protected string $description = '';

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

    public static function getProjectSlug(): string
    {
        return Projects::getSlugFromName(self::getProject());
    }

    public static function getName(): string
    {
        $calledClass = get_called_class();

        return (new $calledClass())->name;
    }

    public static function getDescription(): ?string
    {
        $calledClass = get_called_class();

        return (new $calledClass())->description ?: null;
    }

    public static function getExampleImageUri(): string
    {
        return self::getProjectSlug() . '/' . self::getSlug() . '.' . self::getImageExtension();
    }

    public static function getImageExtension(): string
    {
        $calledClass = get_called_class();

        return (new $calledClass())->imageExtension;
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

        $options = (new $calledClass())->options;

        $options = array_combine($options, $options);

        foreach ($options as $optionKey => $optionValue) {
            $options[$optionKey] = make_nft_type_neat($optionValue);
        }

        ksort ($options);

        return $options;
    }

    public static function getOptionsPerId():? array
    {
        $calledClass = get_called_class();

        return (new $calledClass())->optionsPerId;
    }

}

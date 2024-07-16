<?php
namespace App\Service\Images;

use App\Service\Projects\Projects;

class ImagesHelper
{

    public static function getAllImageClasses(): array
    {
        $instances = [];

        foreach (Projects::getAll() as $project) {
            $instances = array_merge($instances, self::getImagesClassesForProject($project));
        }

        return $instances;
    }

    public static function getImageClassByProjectAndSlug(string $project, string $imageSlug): ?BaseImage
    {
        $project = convert_snakecase_to_project_name($project);

        foreach (self::getImagesClassesForProject($project) as $class) { /* @var $class BaseImage */
            if ($class::getSlug() === $imageSlug) {
                return $class;
            }
        }

        return null;
    }

    public static function getImagesClassesForProject(string $project): array
    {
        $pattern = ROOT . '/src/App/Service/Images/*/*.php';
        $imageClasses = glob($pattern, GLOB_BRACE);

        $instances = [];
        foreach ($imageClasses as $imageClass) {
            $namespace = str_replace(ROOT . '/src', '', $imageClass);
            $namespace = str_replace('.php', '', $namespace);
            $namespace = str_replace('/', '\\', $namespace);

            $imageClassInit = new $namespace();
            if ($imageClassInit::getProject() === $project) {
                $instances[] = $imageClassInit;
            }
        }

        return $instances;
    }

    public static function getImageClassForProjectBySlug(string $project, string $imageSlug):? BaseImage
    {
        foreach (self::getImagesClassesForProject($project) as $class) { /* @var $class BaseImage */
            if ($class::getSlug() === $imageSlug) {
                return $class;
            }
        }

        return null;
    }
}
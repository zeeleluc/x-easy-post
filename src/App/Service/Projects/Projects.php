<?php
namespace App\Service\Projects;

class Projects
{
    const HAS_MINTS = 'HasMints';

    const RIPPLE_PUNKS = 'RipplePunks';

    const PIPING_PUNKS = 'PipingPunks';

    const LOADING_PUNKS = 'LoadingPunks';

    const OPEPE_PUNKS = 'OpepePunks';

    const SHAPED_PUNKS = 'ShapedPunks';

    const LOONEY_LUCA = 'Looney Luca';

    const BASE_ALIENS = 'BaseAliens';

    const RICH_LISTS = 'RichLists';

    const XRPEPENS = 'XRPepens';
    const WEEPING_PLEBS = 'WeepingPlebs';

    public static function getAll(): array
    {
        $projects = [
            flatten_string(self::HAS_MINTS) => self::HAS_MINTS,
            flatten_string(self::RIPPLE_PUNKS) => self::RIPPLE_PUNKS,
            flatten_string(self::PIPING_PUNKS) => self::PIPING_PUNKS,
            flatten_string(self::LOADING_PUNKS) => self::LOADING_PUNKS,
//            flatten_string(self::OPEPE_PUNKS) => self::OPEPE_PUNKS,
//            flatten_string(self::SHAPED_PUNKS) => self::SHAPED_PUNKS,
            flatten_string(self::LOONEY_LUCA) => self::LOONEY_LUCA,
            flatten_string(self::BASE_ALIENS) => self::BASE_ALIENS,
            flatten_string(self::RICH_LISTS) => self::RICH_LISTS,
//            flatten_string(self::XRPEPENS) => self::XRPEPENS,
        ];

        asort($projects);

        return $projects;
    }

    public static function getAllPublic(): array
    {
        $projects = [
            flatten_string(self::RIPPLE_PUNKS) => self::RIPPLE_PUNKS,
            flatten_string(self::PIPING_PUNKS) => self::PIPING_PUNKS,
            flatten_string(self::LOADING_PUNKS) => self::LOADING_PUNKS,
            flatten_string(self::LOONEY_LUCA) => self::LOONEY_LUCA,
            flatten_string(self::BASE_ALIENS) => self::BASE_ALIENS,
            flatten_string(self::WEEPING_PLEBS) => self::WEEPING_PLEBS,
        ];

        asort($projects);

        return $projects;
    }

    public static function getNeatPublicProjectStringForSlug(string $projectSlug): string
    {
        return str_replace('_', '', $projectSlug);
    }

    public static function getNameFromSlug(string $givenProjectSlug): ?string
    {
        foreach (array_merge(self::getAll(), self::getAllPublic()) as $projectSlug => $projectName) {
            if ($projectSlug === $givenProjectSlug) {
                return $projectName;
            }
        }

        return null;
    }

    public static function getSlugForNeatPublicProjectString(string $neatProjectString): ?string
    {
        if ($neatProjectString === 'ripplepunks') {
            return flatten_string(self::RIPPLE_PUNKS);
        }

        if ($neatProjectString === 'pipingpunks') {
            return flatten_string(self::PIPING_PUNKS);
        }

        if ($neatProjectString === 'loadingpunks') {
            return flatten_string(self::LOADING_PUNKS);
        }

        if ($neatProjectString === 'looneyluca') {
            return flatten_string(self::LOONEY_LUCA);
        }

        if ($neatProjectString === 'basealiens') {
            return flatten_string(self::BASE_ALIENS);
        }

        if ($neatProjectString === 'weepingplebs') {
            return flatten_string(self::WEEPING_PLEBS);
        }

        return null;
    }
}

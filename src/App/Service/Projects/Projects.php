<?php
namespace App\Service\Projects;

class Projects
{
    const HAS_MINTS = 'HasMints';

    const INJECT_MEME = 'InjectMeme';

    const RIPPLE_PUNKS = 'RipplePunks';

    const BULLRUN_PUNKS = 'BullRun Punks';

    const PIPING_PUNKS = 'PipingPunks';

    const LOADING_PUNKS = 'LoadingPunks';

    const NO_BASED = 'No-Based';

    const LOADING_PUNKS_BASE = 'LoadingPunks on Base';

    const MAGIC_PUNKS = 'MagicPunks';

    const OPEPE_PUNKS = 'OpepePunks';

    const SHAPED_PUNKS = 'ShapedPunks';

    const LOONEY_LUCA = 'Looney Luca';

    const BASE_ALIENS = 'BaseAliens';

    const RICH_LISTS = 'RichLists';

    const XRPEPENS = 'XRPepens';
    const WEEPING_PLEBS = 'WeepingPlebs';

    const MONEY_MINDED_APES = 'Money Minded Apes';

    public static function getAll(string $forAccount = null): array
    {
        if ($forAccount) {
            if ($forAccount === 'HasMints') {
                $projects = [
                    flatten_string(self::HAS_MINTS) => self::HAS_MINTS,
                    flatten_string(self::LOONEY_LUCA) => self::LOONEY_LUCA,
                ];
            } elseif ($forAccount === 'NoBased') {
                $projects = [
                    flatten_string(self::NO_BASED) => self::NO_BASED,
                ];
            } elseif ($forAccount === 'RipplePunks') {
                $projects = [
                    flatten_string(self::RIPPLE_PUNKS) => self::RIPPLE_PUNKS,
                ];
            } elseif ($forAccount === 'PunkDerivs') {
                $projects = [
                    flatten_string(self::RIPPLE_PUNKS) => self::RIPPLE_PUNKS,
                    flatten_string(self::MAGIC_PUNKS) => self::MAGIC_PUNKS,
                    flatten_string(self::NO_BASED) => self::NO_BASED,
                    flatten_string(self::BULLRUN_PUNKS) => self::BULLRUN_PUNKS,
                    flatten_string(self::PIPING_PUNKS) => self::PIPING_PUNKS,
                    flatten_string(self::LOADING_PUNKS) => self::LOADING_PUNKS,
                    flatten_string(self::LOADING_PUNKS_BASE) => self::LOADING_PUNKS_BASE,
                    flatten_string(self::BASE_ALIENS) => self::BASE_ALIENS,
                ];
            } elseif ($forAccount === 'InjectMeme') {
                $projects = [
                    flatten_string(self::INJECT_MEME) => self::INJECT_MEME,
                ];
            }
        } else {
            $projects = [
                flatten_string(self::HAS_MINTS) => self::HAS_MINTS,
                flatten_string(self::RIPPLE_PUNKS) => self::RIPPLE_PUNKS,
                flatten_string(self::MAGIC_PUNKS) => self::MAGIC_PUNKS,
                flatten_string(self::NO_BASED) => self::NO_BASED,
                flatten_string(self::BULLRUN_PUNKS) => self::BULLRUN_PUNKS,
                flatten_string(self::PIPING_PUNKS) => self::PIPING_PUNKS,
                flatten_string(self::LOADING_PUNKS) => self::LOADING_PUNKS,
                flatten_string(self::LOADING_PUNKS_BASE) => self::LOADING_PUNKS_BASE,
                flatten_string(self::LOONEY_LUCA) => self::LOONEY_LUCA,
                flatten_string(self::BASE_ALIENS) => self::BASE_ALIENS,
            ];
        }

        asort($projects);

        return $projects;
    }

    public static function getAllPublic(): array
    {
        $projects = [
            flatten_string(self::RIPPLE_PUNKS) => self::RIPPLE_PUNKS,
            flatten_string(self::MAGIC_PUNKS) => self::MAGIC_PUNKS,
            flatten_string(self::NO_BASED) => self::NO_BASED,
            flatten_string(self::PIPING_PUNKS) => self::PIPING_PUNKS,
            flatten_string(self::BULLRUN_PUNKS) => self::BULLRUN_PUNKS,
            flatten_string(self::LOADING_PUNKS) => self::LOADING_PUNKS,
            flatten_string(self::LOADING_PUNKS_BASE) => self::LOADING_PUNKS_BASE,
            flatten_string(self::LOONEY_LUCA) => self::LOONEY_LUCA,
            flatten_string(self::BASE_ALIENS) => self::BASE_ALIENS,
            flatten_string(self::WEEPING_PLEBS) => self::WEEPING_PLEBS,
            flatten_string(self::MONEY_MINDED_APES) => self::MONEY_MINDED_APES,
        ];

        asort($projects);

        return $projects;
    }

    public static function getNeatPublicProjectStringForSlug(string $projectSlug): string
    {
        return str_replace('_', '', $projectSlug);
    }

    public static function getSlugFromName(string $projectName): string
    {
        return flatten_string($projectName);
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

        if ($neatProjectString === 'magicpunks') {
            return flatten_string(self::MAGIC_PUNKS);
        }

        if ($neatProjectString === 'nobased') {
            return flatten_string(self::NO_BASED);
        }

        if ($neatProjectString === 'loadingpunks') {
            return flatten_string(self::LOADING_PUNKS);
        }

        if ($neatProjectString === 'bullrunpunks') {
            return flatten_string(self::BULLRUN_PUNKS);
        }

        if ($neatProjectString === 'loadingpunksonbase') {
            return flatten_string(self::LOADING_PUNKS_BASE);
        }

        if ($neatProjectString === 'looneyluca') {
            return flatten_string(self::LOONEY_LUCA);
        }

        if ($neatProjectString === 'basealiens') {
            return flatten_string(self::BASE_ALIENS);
        }

        if ($neatProjectString === 'injectmeme') {
            return flatten_string(self::INJECT_MEME);
        }

        if ($neatProjectString === 'weepingplebs') {
            return flatten_string(self::WEEPING_PLEBS);
        }

        if ($neatProjectString === 'moneymindedapes') {
            return flatten_string(self::MONEY_MINDED_APES);
        }

        return null;
    }
}

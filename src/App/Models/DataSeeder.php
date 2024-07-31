<?php

namespace App\Models;

use App\Query\DataSeederQuery;

class DataSeeder
{
    const CRYPTO_PUNKS_PROPERTIES_BY_ID = 'CRYPTO-PUNKS-PROPERTIES-BY-ID';
    const BASE_ALIENS_PROPERTIES_BY_ID = 'BASE-ALIENS-PROPERTIES-BY-ID';
    const LOONEY_LUCA_PROPERTIES_BY_ID = 'LOONEY-LUCA-PROPERTIES-BY-ID';
    const MONEY_MINDED_APES_PROPERTIES_BY_ID = 'MONEY-MINDED-APES-PROPERTIES-BY-ID';
    const LOONEY_LUCA_BACKGROUND_COLOR_PER_ID = 'LOONEY-LUCA-BACKGROUND-COLOR-PER-ID';
    const WEEPING_PLEBS_BY_ID = 'WEEPING-PLEBS-BY-ID';

    const RIPPLE_PUNKS_METADATA = 'RIPPLE-PUNKS-METADATA';

    public static function get(string $identifier): array
    {
        $query = new DataSeederQuery();

        return $query->get($identifier);
    }

    public static function set(string $identifier, array $data): void
    {
        $query = new DataSeederQuery();
        $query->set($identifier, $data);
    }
}

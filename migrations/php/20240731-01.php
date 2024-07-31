<?php

class Migration
{
    public function run()
    {
        $data = (array)
        json_decode(file_get_contents('./migrations/migration_data/moneymindedapes.json'), true);

        \App\Models\DataSeeder::set(
            \App\Models\DataSeeder::MONEY_MINDED_APES_PROPERTIES_BY_ID,
            $data
        );
    }
}

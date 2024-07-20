<?php

class Migration
{
    public function run()
    {
        $data = (array)
            json_decode(file_get_contents('./migrations/migration_data/weepingplebs.json'), true);
        
        \App\Models\DataSeeder::set(
            \App\Models\DataSeeder::WEEPING_PLEBS_BY_ID,
            $data
        );
    }
}

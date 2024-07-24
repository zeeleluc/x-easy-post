<?php

class Migration
{
    public function run()
    {
        $allMetadata = [];
        foreach (glob(ROOT . '/tmp_data/ripplepunks-metadata/*.json') as $jsonFile) {

            $metadata = (array) json_decode(file_get_contents($jsonFile), true);
            $name = $metadata['name'];
            $id = (int) str_replace('RipplePunk #', '', $name);
            $allMetadata[$id] = $metadata;
        }

        \App\Models\DataSeeder::set(
            \App\Models\DataSeeder::RIPPLE_PUNKS_METADATA,
            $allMetadata
        );
    }
}

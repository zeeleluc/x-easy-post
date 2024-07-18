<?php

class Migration
{
    public function run()
    {
        \App\DataSeeder\Properties::set(
            \App\DataSeeder\Properties::CRYPTO_PUNKS_PROPERTIES_BY_ID,
            (new \App\Service\Images\RipplePunks\Regular())->getOptionsPerId()
        );

        \App\DataSeeder\Properties::set(
            \App\DataSeeder\Properties::BASE_ALIENS_PROPERTIES_BY_ID,
            (new \App\Service\Images\BaseAliens\Regular())->getOptionsPerId()
        );

        \App\DataSeeder\Properties::set(
            \App\DataSeeder\Properties::LOONEY_LUCA_PROPERTIES_BY_ID,
            (new \App\Service\Images\LooneyLuca\Regular())->getOptionsPerId()
        );

        \App\DataSeeder\Properties::set(
            \App\DataSeeder\Properties::LOONEY_LUCA_BACKGROUND_COLOR_PER_ID,
            (new \App\Service\Images\LooneyLuca\TextImageCenteredLooneyLuca())->backgroundColorPerId
        );
    }
}

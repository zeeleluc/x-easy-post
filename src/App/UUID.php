<?php

namespace App;

class UUID
{
    public static function create(): string
    {
        // Generate 16 random bytes
        $data = random_bytes(16);

        // Set the version to 0100 (UUID version 4)
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);

        // Set the variant to 10xx
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Convert the byte array to a UUID string
        return sprintf(
            '%08s-%04s-%04s-%04s-%12s',
            bin2hex(substr($data, 0, 4)),
            bin2hex(substr($data, 4, 2)),
            bin2hex(substr($data, 6, 2)),
            bin2hex(substr($data, 8, 2)),
            bin2hex(substr($data, 10, 6))
        );
    }
}

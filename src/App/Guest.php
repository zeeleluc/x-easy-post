<?php

namespace App;

class Guest
{
    public static function identifier(): string
    {
        $userIP = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $userIP = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $userIP = $_SERVER['REMOTE_ADDR'];
        }

        $sessionId = session_id();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        return hash('sha256', $sessionId . $userIP . $userAgent);
    }
}

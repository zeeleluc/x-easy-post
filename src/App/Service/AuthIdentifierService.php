<?php
namespace App\Service;

use App\Models\AuthIdentifier;
use App\Slack;

class AuthIdentifierService
{
    /**
     * @throws \Exception
     */
    public static function new(): AuthIdentifier
    {
        $authIdentifier = new AuthIdentifier();

        return $authIdentifier->initNew([
            'auth_identifier' => generate_token(100),
            'created_at' => now()
        ]);
    }

    public static function slack(string $text, AuthIdentifier $authIdentifier): void
    {
        $slack = new Slack();

        $link = env('URL') . '/magic-login/' . env('MAGIC_LOGIN_SERVER_KEY') . '/' . $authIdentifier->authIdentifier;

        $text = <<<HTML
{$text}: <{$link}|Login> to create new posts
HTML;

        $slack->sendInfoMessage($text);
    }
}

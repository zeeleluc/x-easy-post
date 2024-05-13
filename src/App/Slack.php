<?php

namespace App;

use JoliCode\Slack\Client;
use JoliCode\Slack\ClientFactory;

class Slack
{
    private string $slackChannel = 'xeasypost';

    private Client $client;

    public function __construct()
    {
        $this->client = ClientFactory::create(env('SLACK_TOKEN'));
    }

    public function sendErrorMessage(string $text): void
    {
        $this->sendMessage('🚫', $text, $this->slackChannel);
    }

    public function sendSuccessMessage(string $text): void
    {
        $this->sendMessage('✅', $text, $this->slackChannel);
    }

    public function sendInfoMessage(string $text): void
    {
        $this->sendMessage('💬', $text, $this->slackChannel);
    }

    private function sendMessage(string $icon, string $text, string $channel): void
    {
        $this->client->chatPostMessage([
            'text' => '`[' . env('ENV') . ']` ' . $icon . ' ' . $text,
            'channel' => $channel,
            'username' => 'web3reef-bot',
        ]);
    }
}

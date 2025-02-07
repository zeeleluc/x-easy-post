<?php
namespace App\Service;

use Abraham\TwitterOAuth\TwitterOAuthException;
use Abraham\TwitterOAuth\TwitterOAuth;

class XPost
{
    private string $oauthId;
//
//    private string $oauthToken;
//
//    private string $oauthTokenSecret;

    private string $text = '';

    private string $account = '';

    private ?string $image = null;

    public function __construct(string $account)
    {
        $this->account = $account;

        $this->oauthId = env('OAUTH_ID__' . strtoupper($this->account));
//        $this->oauthToken = env('OAUTH_TOKEN__' . strtoupper($this->account));
//        $this->oauthTokenSecret = env('OAUTH_TOKEN_SECRET__' . strtoupper($this->account));
    }

    public function setAccount(string $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function setImage(string $path): self
    {
        $this->image = $path;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @throws TwitterOAuthException
     */
    public function post(): array
    {
        $parameters = [
            'text' => html_entity_decode($this->text),
        ];

        $this->uploadAndAttachMediaIds($parameters);

        return array_cast_recursive($this->getConnection('2')
            ->post('tweets', $parameters, ['jsonPayload' => true]));
    }

    /**
     * @throws TwitterOAuthException
     */
    public function reply(string $inReplyTo): array
    {
        $parameters = [
            'text' => html_entity_decode($this->text),
            'reply' => ['in_reply_to_tweet_id' => $inReplyTo],
        ];

        $this->uploadAndAttachMediaIds($parameters);

        return array_cast_recursive($this->getConnection('2')
            ->post('tweets', $parameters, ['jsonPayload' => true]));
    }

    public function clear(): self
    {
        unlink($this->image);

        return $this;
    }

    /**
     * @throws TwitterOAuthException
     */
    private function uploadAndAttachMediaIds(&$parameters = []): void
    {
        if ($this->image) {
            $mediaIds = [];
            $mediaChunks = $this->getConnection('1.1')
                ->upload('media/upload', ['media' => $this->image], ['chunkedUpload' => true]);
            $mediaIds[] = $mediaChunks->media_id_string;

            if ($mediaIds) {
                $parameters['media'] = [
                    'tagged_user_ids' => [$this->oauthId],
                    'media_ids' => $mediaIds,
                ];
            }
        }
    }

    /**
     * @throws TwitterOAuthException
     */
    private function getConnection(string $version): TwitterOAuth
    {
        $apiCredentials = $this->getApiCredentials();

        $connection = new TwitterOAuth(
            $apiCredentials['consumer_key'],
            $apiCredentials['consumer_secret'],
            $apiCredentials['token_identifier'],
            $apiCredentials['token_secret'],
        );
        $connection->setTimeouts(30, 60);

        $connection->setApiVersion($version);

        return $connection;
    }

    private function getApiCredentials(): array
    {
        return [
            'bearer_token' => env('TWITTER_BEARER_TOKEN__' . strtoupper($this->account)),
            'consumer_key' => env('TWITTER_CONSUMER_KEY__' . strtoupper($this->account)),
            'consumer_secret' => env('TWITTER_CONSUMER_SECRET__' . strtoupper($this->account)),
            'token_identifier' => env('TWITTER_TOKEN_IDENTIFIER__' . strtoupper($this->account)),
            'token_secret' => env('TWITTER_TOKEN_SECRET__' . strtoupper($this->account)),
        ];
    }
}






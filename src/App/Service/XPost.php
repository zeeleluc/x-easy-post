<?php
namespace App\Service;

use Abraham\TwitterOAuth\TwitterOAuthException;
use Abraham\TwitterOAuth\TwitterOAuth;

class XPost
{
    private string $oauthId;

    private string $oauthToken;

    private string $oauthTokenSecret;

    private string $text;

    private ?string $image = null;

    public function __construct()
    {
        $this->oauthId = env('OAUTH_ID');
        $this->oauthToken = env('OAUTH_TOKEN');
        $this->oauthTokenSecret = env('OAUTH_TOKEN_SECRET');
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
            'text' => $this->text,
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
            'text' => $this->text,
            'reply' => ['in_reply_to_tweet_id' => $inReplyTo],
        ];

        $this->uploadAndAttachMediaIds($parameters);

        return array_cast_recursive($this->getConnection('2')
            ->post('tweets', $parameters, ['jsonPayload' => true]));
    }

    /**
     * @throws TwitterOAuthException
     */
    private function uploadAndAttachMediaIds(&$parameters = []): void
    {
        if ($this->image) {
            $mediaIds = [];
            $mediaChunks = $this->getConnection('1.1')
                ->upload('media/upload', ['media' => $this->image]);
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
            $this->oauthToken,
            $this->oauthTokenSecret
        );

        $connection->setApiVersion($version);

        return $connection;
    }

    private function getApiCredentials(): array
    {
        return [
            'bearer_token' => env('TWITTER_BEARER_TOKEN'),
            'consumer_key' => env('TWITTER_CONSUMER_KEY'),
            'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
            'token_identifier' => env('TWITTER_TOKEN_IDENTIFIER'),
            'token_secret' => env('TWITTER_TOKEN_SECRET'),
        ];
    }
}






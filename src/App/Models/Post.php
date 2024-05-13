<?php

namespace App\Models;

use App\Query\PostQuery;
use ArrayHelpers\Arr;
use Carbon\Carbon;

class Post extends BaseModel
{

    public ?int $id = null;

    public string $tweetId;

    public bool $posted;

    public string $replyType;

    public array $result;

    public Carbon $createdAt;

    public function initNew(array $values)
    {
        $post = $this->fromArray($values);

        return $post->save();
    }

    public function fromArray(array $values): Post
    {
        $post = new $this;
        if ($id = Arr::get($values, 'id')) {
            $post->id = $id;
        }
        $post->posted = (bool) Arr::get($values, 'posted');
        $post->tweetId = Arr::get($values, 'tweet_id');
        $post->replyType = Arr::get($values, 'reply_type');
        $post->result = (array) json_decode(Arr::get($values, 'result'), true);
        $post->createdAt = Carbon::parse(Arr::get($values, 'created_at'));

        return $post;
    }

    public function toArray(): array
    {
        $array = [];

        if ($this->id) {
            $array['id'] = $this->id;
        }
        $array['posted'] = $this->posted ? 1 : 0;
        $array['tweet_id'] = $this->tweetId;
        $array['reply_type'] = $this->replyType;
        $array['result'] = json_encode($this->result);
        $array['created_at'] = $this->createdAt;

        return $array;
    }

    /**
     * @throws \Exception
     */
    public function save()
    {
        if ($this->getQueryObject()->doesPostExistOnTweetId($this->tweetId)) {
            throw new \Exception('Post `' . $this->tweetId . '` already replied on!');
        }

        return $this->getQueryObject()->createNewPost($this->toArray());
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function getQueryObject()
    {
        return new PostQuery();
    }
}

<?php

namespace App\Models;

use App\Query\PostQuery;
use ArrayHelpers\Arr;
use Carbon\Carbon;

class Post extends BaseModel
{

    public ?int $id = null;

    public ?string $postId = null;

    public ?bool $success = null;

    public ?string $text = null;

    public ?string $image = null;

    public ?string $imageType = null;

    public ?string $readableResult = null;

    public ?array $result = null;

    public ?Carbon $postedAt = null;

    public ?Carbon $createdAt = null;

    /**
     * @throws \Exception
     */
    public function initNew(array $values): Post
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
        if ($postId = Arr::get($values, 'post_id')) {
            $post->postId = $postId;
        }
        if ($success = Arr::get($values, 'success')) {
            $post->success = (bool) $success;
        }
        if ($text = Arr::get($values, 'text')) {
            $post->text = $text;
        }
        if ($image = Arr::get($values, 'image')) {
            $post->image = $image;
        }
        if ($imageType = Arr::get($values, 'image_type')) {
            $post->imageType = $imageType;
        }
        if ($readableResult = Arr::get($values, 'readable_result')) {
            $post->readableResult = $readableResult;
        }
        if ($result = Arr::get($values, 'result')) {
            $post->result = (array) json_decode($result, true);
        }
        if ($postedAt = Arr::get($values, 'posted_at')) {
            $post->postedAt = Carbon::parse($postedAt);
        }
        $post->createdAt = Carbon::parse(Arr::get($values, 'created_at'));

        return $post;
    }

    public function toArray(): array
    {
        $array = [];

        if ($this->id) {
            $array['id'] = $this->id;
        }
        if ($this->postId) {
            $array['post_id'] = $this->postId;
        }
        if (isset($this->success)) {
            $array['success'] = $this->success ? 1 : 0;
        }
        if ($this->image) {
            $array['image'] = $this->image;
        }
        if ($this->text) {
            $array['text'] = $this->text;
        }
        if ($this->imageType) {
            $array['image_type'] = $this->imageType;
        }
        if ($this->readableResult) {
            $array['readable_result'] = $this->readableResult;
        }
        if ($this->result) {
            $array['result'] = json_encode($this->result);
        }
        if ($this->postedAt) {
            $array['posted_at'] = $this->postedAt;
        }
        if ($this->createdAt) {
            $array['created_at'] = $this->createdAt;
        }

        return $array;
    }

    /**
     * @throws \Exception
     */
    public function save()
    {
        if ($this->postId && $this->getQueryObject()->doesPostExistForPostId($this->postId)) {
            throw new \Exception('Post `' . $this->postId . '` already replied on!');
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

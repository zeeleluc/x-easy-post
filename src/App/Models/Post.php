<?php

namespace App\Models;

use App\Query\PostQuery;
use ArrayHelpers\Arr;
use Carbon\Carbon;

class Post extends BaseModel
{

    public ?int $id = null;

    public ?string $postId = null;

    public bool $success;

    public string $image;

    public string $imageType;

    public string $readableResult;

    public array $result;

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
        $post->success = (bool) Arr::get($values, 'success');
        $post->image = Arr::get($values, 'image');
        $post->imageType = Arr::get($values, 'image_type');
        $post->readableResult = Arr::get($values, 'readable_result');
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
        if ($this->postId) {
            $array['post_id'] = $this->postId;
        }
        $array['success'] = $this->success ? 1 : 0;
        $array['image'] = $this->image;
        $array['image_type'] = $this->imageType;
        $array['readable_result'] = $this->readableResult;
        $array['result'] = json_encode($this->result);
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

<?php

namespace App\Models;

use App\Query\PostQuery;
use App\Service\XPost;
use ArrayHelpers\Arr;
use Carbon\Carbon;

class Post extends BaseModel
{

    public ?int $id = null;

    public ?string $postId = null;

    public ?bool $success = null;

    public ?string $account = null;
    public ?string $project = null;
    public ?string $text = null;

    public ?string $textImage = null;

    public ?string $image = null;

    public ?string $imageType = null;

    public ?string $imageAttributeType = null;

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
        $success = Arr::get($values, 'success');
        if (is_null($success)) {
            $post->success = null;
        } else {
            $post->success = (bool) $success;
        }
        if ($account = Arr::get($values, 'account')) {
            $post->account = $account;
        }
        if ($project = Arr::get($values, 'project')) {
            $post->project = $project;
        }
        if ($text = Arr::get($values, 'text')) {
            $post->text = $text;
        }
        if ($textImage = Arr::get($values, 'text_image')) {
            $post->textImage = $textImage;
        }
        if ($image = Arr::get($values, 'image')) {
            $post->image = $image;
        }
        if ($imageType = Arr::get($values, 'image_type')) {
            $post->imageType = $imageType;
        }
        if ($imageAttributeType = Arr::get($values, 'image_attribute_type')) {
            $post->imageAttributeType = $imageAttributeType;
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
        if ($this->account) {
            $array['account'] = $this->account;
        }
        if ($this->project) {
            $array['project'] = $this->project;
        }
        if ($this->image) {
            $array['image'] = $this->image;
        }
        if ($this->text) {
            $array['text'] = $this->text;
        }
        if ($this->textImage) {
            $array['text_image'] = $this->textImage;
        }
        if ($this->imageType) {
            $array['image_type'] = $this->imageType;
        }
        if ($this->imageAttributeType) {
            $array['image_attribute_type'] = $this->imageAttributeType;
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

        if ($this->id) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function copy()
    {
        $attributes = $this->toArray();
        $attributes['id'] = null;
        $attributes['post_id'] = null;
        $attributes['success'] = null;
        $attributes['result'] = null;
        $attributes['posted_at'] = null;
        $attributes['created_at'] = null;

        return $this->getQueryObject()->createNewPost($attributes);
    }

    public function create()
    {
        return $this->getQueryObject()->createNewPost($this->toArray());
    }

    public function update()
    {
        return $this->getQueryObject()->updatePost($this->toArray());
    }

    public function delete()
    {
        $this->getQueryObject()->deletePost($this);
    }

    public function getQueryObject()
    {
        return new PostQuery();
    }

    public function postOnX(): array
    {
        if (isset($this->success) && $this->success) {
            return [
                'success' => false,
                'Already posted successfully',
            ];
        }

        try {
            $xPost = new XPost($this->account);
            if ($this->text) {
                $xPost->setText($this->text);
            }
            if ($this->image) {
                $path = ROOT . '/tmp/' . uniqid();
                $image = file_get_contents($this->image);
                file_put_contents($path, $image);
                chmod($path, 0777);

                $xPost->setImage($path);
            }
            if ($this->postId) {
                $result = $xPost->reply($this->postId);
            } else {
                $result = $xPost->post();
            }

            $success = true;
            if (array_key_exists('status', $result)) {
                $success = false;
                $readableResult = $result['status'] . ': ' . $result['detail'];
            } elseif (array_key_exists('errors', $result)) {
                $success = false;
                $readableResult = $result['title'] . ': ' . $result['detail'];

            } else {
                $readableResult = $result['data']['id'];
            }

            $this->result = $result;
            $this->readableResult = $readableResult;
            $this->success = $success;
            if ($success) {
                $this->postedAt = now();
            }
            $this->save();

            return [
                'success' => $success,
                'message' => $readableResult,
            ];
        } catch (\Exception $exception) {
            var_dump($exception);
        }

    }
}

<?php

namespace App\Models;

use App\Guest;
use App\Query\ImageQuery;
use App\Service\Images\ImagesHelper;
use App\UUID;
use ArrayHelpers\Arr;
use Carbon\Carbon;

class Image extends BaseModel
{

    public ?int $id = null;
    public ?string $uuid = null;
    public ?string $createdBy = null;
    public ?string $project = null;
    public ?string $imageType = null;
    public ?string $textImage = null;
    public ?string $nftId = null;
    public ?string $nftType = null;
    public ?string $url = null;
    public bool $canRedo = false;
    public ?Carbon $createdAt = null;

    /**
     * @throws \Exception
     */
    public function initNew(array $values): Image
    {
        $image = $this->fromArray($values);

        return $image->save();
    }

    public function fromArray(array $values): Image
    {
        $image = new $this;

        if ($id = Arr::get($values, 'id')) {
            $image->id = $id;
        }

        if ($uuid = Arr::get($values, 'uuid')) {
            $image->uuid = $uuid;
        }

        if ($createdBy = Arr::get($values, 'created_by')) {
            $image->createdBy = $createdBy;
        }

        if ($project = Arr::get($values, 'project')) {
            $image->project = $project;
        }

        if ($imageType = Arr::get($values, 'image_type')) {
            $image->imageType = $imageType;
        }

        if ($textImage = Arr::get($values, 'text_image')) {
            $image->textImage = $textImage;
        }

        if ($nftId = Arr::get($values, 'nft_id')) {
            $image->nftId = $nftId;
        }

        if ($nftType = Arr::get($values, 'nft_type')) {
            $image->nftType = $nftType;
        }

        if ($url = Arr::get($values, 'url')) {
            $image->url = $url;
        }

        if ($canRedo = Arr::get($values, 'can_redo')) {
            $image->canRedo = (bool) $canRedo;
        }

        if ($createdAt = Arr::get($values, 'created_at')) {
            $image->createdAt = Carbon::parse($createdAt);
        }

        return $image;
    }

    public function toArray(): array
    {
        $array = [];

        if ($this->id) {
            $array['id'] = $this->id;
        }

        if ($this->uuid) {
            $array['uuid'] = $this->uuid;
        }

        if ($this->createdBy) {
            $array['created_by'] = $this->createdBy;
        }

        if ($this->project) {
            $array['project'] = $this->project;
        }

        if ($this->imageType) {
            $array['image_type'] = $this->imageType;
        }

        if ($this->textImage) {
            $array['text_image'] = $this->textImage;
        }

        if ($this->nftId) {
            $array['nft_id'] = $this->nftId;
        }

        if ($this->nftType) {
            $array['nft_type'] = $this->nftType;
        }

        if ($this->url) {
            $array['url'] = $this->url;
        }

        if ($this->canRedo) {
            $array['can_redo'] = 1;
        } else {
            $array['can_redo'] = 0;
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
        if ($this->uuid) {
            return $this->update();
        } else {
            return $this->create();
        }
    }

    public function create(): Image
    {
        $values = $this->toArray();
        $values['uuid'] = UUID::create();
        $values['created_by'] = Guest::identifier();

        return $this->getQueryObject()->createNewImage($values);
    }

    public function update()
    {
        $values = $this->toArray();
        $values['created_by'] = Guest::identifier();

        return $this->getQueryObject()->updateImage($values);
    }

    public function delete()
    {
        $this->getQueryObject()->deleteImage($this);
    }

    public function getQueryObject()
    {
        return new ImageQuery();
    }

    public function canRedo(): bool
    {
        if (!$this->canRedo) {
            return false;
        }

        return $this->createdBy === Guest::identifier();
    }

    public function getUrl(): string
    {
        return env('URL_HYPEOMATIC') . '/image/' . $this->uuid;
    }

    public function getProjectName(): string
    {
        return convert_snakecase_to_project_name($this->project);
    }

    public function getTitle(): string
    {
        $title = '';

        $image = ImagesHelper::getImageClassByProjectAndSlug($this->project, $this->imageType);
        if ($image) {
            $title .= $image::getName();
        }

        if ($nftId = $this->nftId) {
            $title .= ' #' . $nftId;
        } elseif ($nftType = $this->nftType) {
            $title .= ' / ' . make_nft_type_neat($nftType);
        }

        return $title;
    }
}

<?php
namespace App\Query;

use App\Models\Image;
use Carbon\Carbon;

class ImageQuery extends Query
{
    private string $table = 'images';

    /**
     * @throws \Exception
     */
    public function createNewImage(array $values): Image
    {
        foreach ($values as $key => $value) {
            if ($value instanceof Carbon) {
                $values[$key] = $value->format('Y-m-d H:i:s');
            }
        }

        $result = $this->db->insert($this->table, $values);
        if (!$result) {
            throw new \Exception('Image not created.');
        }

        $values = $this->getImageById($this->db->getInsertId());

        $image = new Image();
        return $image->fromArray($values);
    }

    public function updateImage(array $values): Image
    {
        foreach ($values as $key => $value) {
            if ($value instanceof Carbon) {
                $values[$key] = $value->format('Y-m-d H:i:s');
            }
        }

        $result = $this->db->where('uuid', $values['uuid'])->update($this->table, $values);
        if (!$result) {
            throw new \Exception('Image not updated.');
        }

        $values = $this->getImageById($values['id']);

        $image = new Image();
        return $image->fromArray($values);
    }

    public function deleteImage(Image $image): bool
    {
        return $this->db->where('id', $image->id)->delete($this->table);
    }

    /**
     * @return array|Image[]
     * @throws \Exception
     */
    public function getAll(): array
    {
        $results = $this->db->get($this->table);

        $images = [];
        foreach ($results as $result) {
            $images[] = (new Image())->fromArray($result);
        }

        return $images;
    }

    public function getImageById(int $id): ?array
    {
        return $this->db->where('id', $id)->getOne($this->table);
    }

    public function getImageByUuid(string $uuid): ?Image
    {
        $result = $this->db->where('uuid', $uuid)->getOne($this->table);

        if (!$result) {
            return null;
        }

        return (new Image())->fromArray($result);
    }

    /**
     * @return array|Image[]
     * @throws \Exception
     */
    public function getRecentImages(int $limit = 100, string $projectSlug = null): array
    {
        if ($projectSlug) {
            $sql = <<<SQL
SELECT id, uuid, created_by, project, image_type, text_image, nft_id, nft_type, url, can_redo, created_at
    FROM {$this->table}
    WHERE `project` = '{$projectSlug}'
    ORDER BY created_at DESC
    LIMIT ?;
SQL;
        } else {
            $sql = <<<SQL
SELECT id, uuid, created_by, project, image_type, text_image, nft_id, nft_type, url, can_redo, created_at
    FROM {$this->table}
    ORDER BY created_at DESC
    LIMIT ?;
SQL;
        }

        $results = $this->db->rawQuery($sql, [$limit]);

        $images = [];
        foreach ($results as $result) {
            $images[] = (new Image())->fromArray($result);
        }

        return $images;
    }

    /**
     * @return array|Image[]
     * @throws \Exception
     */
    public function getLatestImagesForProject(string $project, int $limit = 4): array
    {
        $sql = <<<SQL
SELECT id, uuid, created_by, project, image_type, text_image, nft_id, nft_type, url, can_redo, created_at
    FROM {$this->table}
    WHERE project = ?
    ORDER BY created_at DESC
    LIMIT ?;
SQL;

        $results = $this->db->rawQuery($sql, [$project, $limit]);

        $images = [];
        foreach ($results as $result) {
            $images[] = (new Image())->fromArray($result);
        }

        return $images;
    }

    /**
     * @throws \Exception
     */
    public function countImagesForProject(string $project): int
    {
        $sql = <<<SQL
SELECT COUNT(*) as count
    FROM {$this->table}
    WHERE project = ?;
SQL;

        $result = $this->db->rawQuery($sql, [$project]);

        return $result[0]['count'] ?? 0;
    }
}

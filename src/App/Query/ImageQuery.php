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
            throw new \Exception('Image not created.');
        }

        $values = $this->getImageById($values['id']);

        $image = new Image();
        $image->fromArray($values);

        return $image;
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

    public function getImageById(int $id):? array
    {
        return $this->db
            ->where('id', $id)
            ->getOne($this->table);
    }

    public function getImageByUuid(string $uuid):? Image
    {
        $results = $this->db
            ->where('uuid', $uuid)
            ->getOne($this->table);

        if (!$results) {
            return null;
        }

        return (new Image())->fromArray($results);
    }
}

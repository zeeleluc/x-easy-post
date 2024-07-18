<?php
namespace App\Query;

class DataSeederQuery extends Query
{

    private string $table = 'data_seeders';

    /**
     * @throws \Exception
     */
    public function set(string $identifier, array $data): void
    {
        $this->db->insert($this->table, [
            'identifier' => $identifier,
            'data' => json_encode($data),
        ]);
    }

    public function get(string $identifier): array
    {
        $result = $this->db
            ->where('identifier', $identifier)
            ->getOne($this->table);

        return (array) json_decode($result['data'], true);
    }
}

<?php
namespace App\Query;

class MigrationQuery extends Query
{

    private string $table = 'migrations';

    public function has(string $identifier): bool
    {
        return (bool) $this->db
            ->where('identifier', $identifier)
            ->get($this->table);
    }

    public function getAllIdentifiers(): array
    {
        $results = $this->db
            ->get($this->table);

        if (!$results) {
            return [];
        }

        return array_column($results, 'identifier');
    }

    public function executeMigration(string $sql, string $identifier): bool
    {
        try {
            $this->db->rawQuery($sql);
            $this->addMigrationDone('sql-' . $identifier);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function addMigrationDone(string $identifier): int
    {
        return $this->db->insert($this->table, ['identifier' => $identifier]);
    }
}

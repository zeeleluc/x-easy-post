<?php
namespace App\Query;

use App\Models\AuthIdentifier;
use Carbon\Carbon;

class AuthIdentifierQuery extends Query
{

    private string $table = 'auth_identifiers';

    /**
     * @throws \Exception
     */
    public function createNewAuthIdentifier(array $values): AuthIdentifier
    {
        foreach ($values as $key => $value) {
            if ($value instanceof Carbon) {
                $values[$key] = $value->format('Y-m-d H:i:s');
            }
        }

        $result = $this->db->insert($this->table, $values);
        if (!$result) {
            throw new \Exception('Auth Identifier not created.');
        }

        $values = $this->getAuthIdentifierById($this->db->getInsertId());

        $authIdentifier = new AuthIdentifier();
        return $authIdentifier->fromArray($values);
    }

    public function deleteAuthIdentifier(AuthIdentifier $authIdentifier): bool
    {
        return $this->db->where(
            'auth_identifier',
            $authIdentifier->authIdentifier
        )->delete($this->table);
    }

    /**
     * @throws \Exception
     */
    public function deleteAllAuthIdentifiers(): void
    {
        foreach ($this->getAll() as $authIdentifier) {
            $authIdentifier->delete();
        }
    }

    /**
     * @return array|AuthIdentifier[]
     * @throws \Exception
     */
    public function getAll(): array
    {
        $results = $this->db->get($this->table);

        $authIdentifiers = [];
        foreach ($results as $result) {
            $authIdentifiers[] = (new AuthIdentifier())->fromArray($result);
        }

        return $authIdentifiers;
    }

    public function doesAuthIdentifierExists(string $authIdentifier): bool
    {
        return (bool) $this->db
            ->where('auth_identifier', $authIdentifier)
            ->getOne($this->table);
    }

    public function getAuthIdentifierByAuthIdentifier(string $authIdentifier):? array
    {
        return $this->db
            ->where('auth_identifier', $authIdentifier)
            ->getOne($this->table);
    }

    public function getAuthIdentifierById(int $id):? array
    {
        return $this->db
            ->where('id', $id)
            ->getOne($this->table);
    }

    /**
     * @throws \Exception
     */
    public function deleteOldTokens(): void
    {
        $date = now()->subHour();

        $sql = <<<SQL
DELETE
    FROM {$this->table}
        WHERE created_at < '{$date->format('Y-m-d H:i:s')}';
SQL;

        $this->db->rawQuery($sql);
    }
}

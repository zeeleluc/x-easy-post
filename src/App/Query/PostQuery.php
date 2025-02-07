<?php
namespace App\Query;

use App\Models\Post;
use Carbon\Carbon;

class PostQuery extends Query
{
    private string $table = 'posts';

    /**
     * @throws \Exception
     */
    public function createNewPost(array $values): Post
    {
        $values['account'] = current_account();

        foreach ($values as $key => $value) {
            if ($value instanceof Carbon) {
                $values[$key] = $value->format('Y-m-d H:i:s');
            } elseif ($key === 'text') {
                $values[$key] = htmlspecialchars($value);
            }
        }

        $result = $this->db->insert($this->table, $values);
        if (!$result) {
            throw new \Exception('Post not created.');
        }

        $values = $this->getPostById($this->db->getInsertId());

        $post = new Post();
        return $post->fromArray($values);
    }

    public function updatePost(array $values): Post
    {
        foreach ($values as $key => $value) {
            if ($value instanceof Carbon) {
                $values[$key] = $value->format('Y-m-d H:i:s');
            }
        }

        $result = $this->db->where('id', $values['id'])->update($this->table, $values);
        if (!$result) {
            throw new \Exception('Post not updated.');
        }

        $values = $this->getPostById($values['id']);

        $post = new Post();
        return $post->fromArray($values);
    }

    public function deletePost(Post $post): bool
    {
        return $this->db->where('id', $post->id)->delete($this->table);
    }

    /**
     * @return array|Post[]
     * @throws \Exception
     */
    public function getAll(): array
    {
        $results = $this->db->where('account', current_account())->get($this->table);

        $posts = [];
        foreach ($results as $result) {
            $posts[] = (new Post())->fromArray($result);
        }

        return $posts;
    }

    public function doesPostExistForPostId(string $postId): bool
    {
        return (bool) $this->db
            ->where('account', current_account())
            ->where('success', 1)
            ->where('post_id', $postId)
            ->getOne($this->table);
    }

    public function getPostById(int $id): ?array
    {
        return $this->db
            ->where('id', $id)
            ->getOne($this->table);
    }

    /**
     * @return array|Post[]
     * @throws \Exception
     */
    public function getLastPosts(Carbon $date): array
    {
        $results = $this->db
            ->orderBy('created_at')
            ->where('account', current_account())
            ->where('created_at', $date->format('Y-m-d H:i:s'), '>')
            ->get($this->table);

        $posts = [];
        foreach ($results as $result) {
            $posts[] = (new Post())->fromArray($result);
        }

        return $posts;
    }

    public function getActualPostsOnX(Carbon $date): array
    {
        $sql = <<<SQL
SELECT *
    FROM {$this->table}
    WHERE account = ?
    AND posted_at IS NOT NULL
    AND success = 1
    AND posted_at > ?
    ORDER BY posted_at DESC;
SQL;

        $results = $this->db->rawQuery($sql, [
            current_account(),
            $date->format('Y-m-d H:i:s')
        ]);

        $posts = [];
        foreach ($results as $result) {
            $posts[] = (new Post())->fromArray($result);
        }

        return $posts;
    }

    /**
     * @return array|Post[]
     * @throws \Exception
     */
    public function getLastPostsPosted(Carbon $date): array
    {
        $sql = <<<SQL
SELECT *
    FROM {$this->table}
    WHERE account = ?
    AND posted_at IS NOT NULL
    AND success = 1
    AND created_at > ?
    ORDER BY posted_at DESC;
SQL;

        $results = $this->db->rawQuery($sql, [
            current_account(),
            $date->format('Y-m-d H:i:s')
        ]);

        $posts = [];
        foreach ($results as $result) {
            $posts[] = (new Post())->fromArray($result);
        }

        return $posts;
    }

    /**
     * @return array|Post[]
     * @throws \Exception
     */
    public function getLastPostsScheduled(): array
    {
        $sql = <<<SQL
SELECT *
    FROM {$this->table}
    WHERE account = ?
    AND posted_at IS NULL
    ORDER BY created_at DESC;
SQL;

        $results = $this->db->rawQuery($sql, [
            current_account(),
        ]);

        $posts = [];
        foreach ($results as $result) {
            $posts[] = (new Post())->fromArray($result);
        }

        return $posts;
    }

    /**
     * @return array|Post[]
     * @throws \Exception
     */
    public function getLastPostsFailed(Carbon $date): array
    {
        $sql = <<<SQL
SELECT *
    FROM {$this->table}
    WHERE account = ?
    AND success = 0
    ORDER BY created_at DESC;
SQL;

        $results = $this->db->rawQuery($sql, [
            current_account(),
        ]);

        $posts = [];
        foreach ($results as $result) {
            $posts[] = (new Post())->fromArray($result);
        }

        return $posts;
    }

    public function getCountPostsInLastPeriod(): int
    {
        return count($this->db
            ->where('account', current_account())
            ->where('success', 1)
            ->where('created_at', now()->subDay()->format('Y-m-d H:i:s'), '>')
            ->get($this->table));
    }

    public function getCountScheduledPosts(string $account = null): int
    {
        $sql = <<<SQL
SELECT COUNT(*) AS row_count
    FROM {$this->table}
    WHERE account = ?
    AND posted_at IS NULL;
SQL;

        $results = $this->db->rawQuery($sql, [
            $account ?: current_account(),
        ]);

        return $results[0]['row_count'];
    }

    public function getNextScheduledPost(string $account): ?Post
    {
        $sql = <<<SQL
SELECT *
    FROM {$this->table}
    WHERE account = ?
    AND posted_at IS NULL
    ORDER BY created_at ASC
    LIMIT 1;
SQL;

        $results = $this->db->rawQuery($sql, [
            $account,
        ]);
        if (!$results) {
            return null;
        }

        return (new Post())->fromArray($results[0]);
    }
}

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
        foreach ($values as $key => $value) {
            if ($value instanceof Carbon) {
                $values[$key] = $value->format('Y-m-d H:i:s');
            } else {
                $values[$key] = htmlspecialchars($value);
            }
        }

        $result = $this->db->insert($this->table, $values);
        if (!$result) {
            throw new \Exception('Post not created.');
        }

        $values = $this->getPostById($this->db->getInsertId());

        $post = new Post();
        $post->fromArray($values);

        return $post;
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
            throw new \Exception('Post not created.');
        }

        $values = $this->getPostById($values['id']);

        $post = new Post();
        $post->fromArray($values);

        return $post;
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
        $results = $this->db->get($this->table);

        $posts = [];
        foreach ($results as $result) {
            $posts[] = (new Post())->fromArray($result);
        }

        return $posts;
    }

    public function doesPostExistForPostId(string $postId): bool
    {
        return (bool) $this->db
            ->where('success', 1)
            ->where('post_id', $postId)
            ->getOne($this->table);
    }


    public function getPostById(int $id):? array
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
        $posts = [];
        $results = $this->db
            ->orderBy('created_at')
            ->where('created_at', $date->format('Y-m-d H:i:s'), '>')
            ->get($this->table);

        foreach ($results as $result) {
            $posts[] = (new Post())->fromArray($result);
        }

        return $posts;
    }

    public function getCountPostsInLastPeriod(): int
    {
        return count($this->db
            ->where('success', true)
            ->where('created_at', now()->subDay()->format('Y-m-d H:i:s'), '>')
            ->get($this->table));
    }

    public function getCountScheduledPosts(): int
    {
        $sql = <<<SQL
SELECT COUNT(*) AS row_count FROM {$this->table}
    WHERE posted_at IS NULL
SQL;

        $results = $this->db->rawQuery($sql);

        return $results[0]['row_count'];
    }

    public function getNextScheduledPost():? Post
    {
        $sql = <<<SQL
SELECT * FROM {$this->table}
    WHERE posted_at IS NULL
    ORDER BY created_at ASC
    LIMIT 1
SQL;

        $results = $this->db->rawQuery($sql);
        if (!$results) {
            return null;
        }

        return (new Post())->fromArray($results[0]);
    }
}

<?php
namespace App\Query;

use App\Models\Post;
use ArrayHelpers\Arr;
use Carbon\Carbon;

class PostQuery extends Query
{

    private string $table = 'posts';

    public function createNewPost(array $values): Post
    {
        foreach ($values as $key => $value) {
            if ($value instanceof Carbon) {
                $values[$key] = $value->format('Y-m-d H:i:s');
            }
        }

        $result = $this->db->insert($this->table, $values);
        if (!$result) {
            throw new \Exception('Post not created.');
        }

        $values = $this->getPostByPostId(Arr::get($values, 'post_id'));

        $post = new Post();
        $post->fromArray($values);

        return $post;
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
            ->where('posted', 1)
            ->where('post_id', $postId)
            ->getOne($this->table);
    }


    public function getPostByPostId(string $postId): array
    {
        return $this->db
            ->where('post_id', $postId)
            ->getOne($this->table);
    }

    /**
     * @param int $limit
     * @return array|Post[]
     * @throws \Exception
     */
    public function getLastPosts(int $limit = 10): array
    {
        $posts = [];
        $results = $this->db
            ->orderBy('created_at')
            ->get($this->table);

        foreach ($results as $result) {
            $posts[] = (new Post())->fromArray($result);
        }

        return $posts;
    }

    public function getCountPostsInLastPeriod(): int
    {
        return count($this->db
            ->where('posted', true)
            ->where('created_at', now()->subHours(3)->format('Y-m-d H:i:s'), '>')
            ->get($this->table));
    }
}

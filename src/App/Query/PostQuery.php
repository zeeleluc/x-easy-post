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

        return $this->getPostByEmail(Arr::get($values, 'email'));
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

    public function doesPostExistOnTweetId(string $tweetId): bool
    {
        return (bool) $this->db
            ->where('posted', 1)
            ->where('tweet_id', $tweetId)
            ->getOne($this->table);
    }
}

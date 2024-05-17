<?php
namespace App\Action\Actions\Cli;

use App\Action\BaseAction;
use App\Query\PostQuery;

class NextPost extends BaseAction
{

    private PostQuery $postQuery;

    public function __construct()
    {
        $this->postQuery = new PostQuery();
    }

    public function run()
    {
        $post = $this->postQuery->getNextScheduledPost();

        if ($post) {
            $post->postOnX();
        }
    }
}

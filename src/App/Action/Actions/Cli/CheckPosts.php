<?php
namespace App\Action\Actions\Cli;

use App\Action\BaseAction;
use App\Query\PostQuery;
use App\Slack;

class CheckPosts extends BaseAction
{

    private PostQuery $postQuery;

    public function __construct()
    {
        $this->postQuery = new PostQuery();
    }

    public function run()
    {
        $post = $this->postQuery->getNextScheduledPost();

        if (!$post) {
            $slack = new Slack();
            $slack->sendErrorMessage('No more posts to post!');
        }
    }
}

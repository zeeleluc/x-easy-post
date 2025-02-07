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
        foreach (get_all_accounts() as $account) {
            $post = $this->postQuery->getNextScheduledPost($account);

            if ($post) {
                $post->postOnX();
            }
        }
    }
}

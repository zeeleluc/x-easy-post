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
        echo 'Run ...' . PHP_EOL;
        foreach (get_all_accounts() as $account) {
            echo ' - try account `' . $account . '` ...' . PHP_EOL;
            $post = $this->postQuery->getNextScheduledPost($account);

            if ($post) {
                echo ' - does have post` ...' . PHP_EOL;
                $post->postOnX();
            } else {
                echo ' - does NOT have post` ...' . PHP_EOL;
            }
        }
        echo 'Done ...' . PHP_EOL;
    }
}

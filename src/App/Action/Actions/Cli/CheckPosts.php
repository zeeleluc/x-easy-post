<?php
namespace App\Action\Actions\Cli;

use App\Action\BaseAction;
use App\Query\PostQuery;
use App\Service\AuthIdentifierService;
use App\Slack;

class CheckPosts extends BaseAction
{

    private PostQuery $postQuery;

    public function __construct()
    {
        $this->postQuery = new PostQuery();
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        foreach (get_all_accounts() as $account) {
            $post = $this->postQuery->getNextScheduledPost($account);

            if (!$post) {
                $authIdentifier = AuthIdentifierService::new();
                AuthIdentifierService::slack('No more posts to show for `' . $account . '`', $authIdentifier);
                return;
            }

            $countScheduledPost = $this->postQuery->getCountScheduledPosts();
            if ($countScheduledPost <= 3) {
                $authIdentifier = AuthIdentifierService::new();
                AuthIdentifierService::slack(
                    'Only `' . $countScheduledPost . '` scheduled post' . ($countScheduledPost > 1 ? 's' : '') . ' for `' . $account . '`',
                    $authIdentifier
                );
            }
        }
    }
}

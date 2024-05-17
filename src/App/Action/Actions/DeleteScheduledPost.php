<?php
namespace App\Action\Actions;

use App\Action\BaseAction;
use App\Models\Post;
use App\Query\PostQuery;

class DeleteScheduledPost extends BaseAction
{
    public function __construct()
    {
        parent::__construct('');

        $id = (int) $this->getRequest()->getParam('id');
        if (!$id) {
            abort('Error');
        }

        $post = (new PostQuery())->getPostById($id);
        if (!$post) {
            abort('Error');
        }

        $post = (new Post())->fromArray($post);
        $post->delete();

        success('', 'Deleted scheduled post #' . $post->id);
    }

    public function run()
    {
        parent::run();

        return $this;
    }


}

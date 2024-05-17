<?php
namespace App\Action\Actions;

use App\Action\BaseAction;
use App\Models\Post;
use App\Query\PostQuery;

class RetryPost extends BaseAction
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
        if (isset($post->success) && $post->success) {
            abort('Error');
        }

        $result = $post->postOnX();

        if ($result['success']) {
            success('', 'Success: ' . $result['message']);
        } else {
            abort('', 'Failed: ' . $result['message']);
        }
    }

    public function run()
    {
        parent::run();

        return $this;
    }


}

<?php
namespace App\Action\Actions;

use App\Action\BaseFormAction;
use App\FormFieldValidator\PostID;
use App\FormFieldValidator\RegularString;
use App\Models\Post;
use App\Query\PostQuery;
use App\Service\XPost;
use App\Variable;

class Home extends BaseFormAction
{
    public function __construct()
    {
        parent::__construct('');

        $this->setLayout('default');
        $this->setView('website/home');

        if ($this->getRequest()->isGet()) {
            $this->performGet();
        } elseif ($this->getRequest()->isPost()) {
            $this->performPost();
        }
    }

    /**
     * @throws \Exception
     */
    protected function performGet()
    {
        parent::performGet();

        $this->setVariable(new Variable('lastPosts', (new PostQuery())->getLastPosts()));
        $this->setVariable(new Variable('countPostsInLastPeriod', (new PostQuery())->getCountPostsInLastPeriod()));
    }

    /**
     * @throws \Exception
     */
    protected function performPost()
    {
        $postId = $this->getRequest()->getPostParam('post_id');
        if (filter_var($postId, FILTER_VALIDATE_URL)) {
            $parts = explode('/', $postId);
            $postId = end($parts);
        }

        $this->validateFormValues([
            new PostID('post_id', $postId),
            new RegularString('text', $this->getRequest()->getPostParam('text')),
        ]);
    }

    protected function handleForm(): void
    {
        $postId = $this->validatedFormValues['post_id'];
        $text = $this->validatedFormValues['text'];

        $post = new Post();
        $post->postId = $postId;
        $post->posted = true;
        $post->replyType = 'Testing';
        $post->result = ['adfafas'];
        $post->save();

//        $xPost = new XPost();
//        $xPost->setText('Have you seen this collection already?');
//        $xPost->setImageLooneyLuca();
//        $xPost->reply('');

        success('', 'Posted ' . $text . ' ' . $postId);
    }

    public function run()
    {
        parent::run();

        return $this;
    }


}

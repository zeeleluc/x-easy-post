<?php
namespace App\Action\Actions;

use App\Action\BaseFormAction;
use App\FormFieldValidator\Image;
use App\FormFieldValidator\NFTID;
use App\FormFieldValidator\PostID;
use App\FormFieldValidator\RegularString;
use App\Models\Post;
use App\Query\PostQuery;
use App\Service\ResolveImage;
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
            new NFTID('nft_id', $this->getRequest()->getPostParam('nft_id')),
            new Image('image', $this->getRequest()->getPostParam('image')),
        ]);
    }

    protected function handleForm(): void
    {
        $postId = $this->validatedFormValues['post_id'] ?: null;
        $text = $this->validatedFormValues['text'];
        $image = $this->validatedFormValues['image'];
        $nftId = $this->validatedFormValues['nft_id'] ?: null;

        $xPost = new XPost();
        $xPost->setText($text);
        if ($image) {
            if ($resolvedImage = ResolveImage::make($image, $nftId)->do()) {
                $xPost->setImage($resolvedImage);
            }
        }
        if ($postId) {
            $result = $xPost->reply($postId);
        } else {
            $result = $xPost->post();
        }
//        $result = [];

        $posted = true;
        if (array_key_exists('status', $result)) {
            $posted = false;
            $readableResult = $result['status'] . ': ' . $result['detail'];
        } else {
            $readableResult = $result['data']['id'];
        }

        $post = new Post();
        if ($postId) {
            $post->postId = $postId;
        }
        $post->posted = $posted;
        $post->replyType = $image;
        $post->readableResult = $readableResult;
        $post->result = $result;
        $post->save();

        success('', 'Posted ' . $text . ' ' . $postId);
    }

    public function run()
    {
        parent::run();

        return $this;
    }


}

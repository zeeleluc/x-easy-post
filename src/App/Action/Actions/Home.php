<?php
namespace App\Action\Actions;

use App\Action\BaseFormAction;
use App\FormFieldValidator\Image;
use App\FormFieldValidator\NFTID;
use App\FormFieldValidator\PostID;
use App\FormFieldValidator\RegularString;
use App\FormFieldValidator\Type;
use App\Models\Post;
use App\Query\PostQuery;
use App\Service\GatherShillingProgress;
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
    protected function performGet(): void
    {
        parent::performGet();

        $this->setVariable(new Variable('shilledPerProject', (new GatherShillingProgress())->perProject()));

        $this->setVariable(new Variable('lastPostsReplies', (new PostQuery())->getLastPostsReplies(now()->subWeek())));
        $this->setVariable(new Variable('lastPostsPosted', (new PostQuery())->getLastPostsPosted(now()->subWeek())));
        $this->setVariable(new Variable('lastPostsScheduled', (new PostQuery())->getLastPostsScheduled(now()->subWeek())));

        $this->setVariable(new Variable('countPostsInLastPeriod', (new PostQuery())->getCountPostsInLastPeriod()));

        $countScheduledPosts = (new PostQuery())->getCountScheduledPosts();
        $this->setVariable(new Variable('countScheduledPosts', $countScheduledPosts));
        $this->setVariable(new Variable('lastPostScheduledForDate', now()->addHours($countScheduledPosts)));
    }

    /**
     * @throws \Exception
     */
    protected function performPost(): void
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
            new Type('type', $this->getRequest()->getPostParam('type')),
        ]);
    }

    protected function handleForm(): void
    {
        $postId = $this->validatedFormValues['post_id'] ?: null;
        $text = $this->validatedFormValues['text'];
        $imageType = $this->validatedFormValues['image'];
        $nftId = $this->validatedFormValues['nft_id'] ?: null;
        $type = $this->validatedFormValues['type'] ?: null;
        $resolvedImage = ResolveImage::make($imageType, [
            'nft_id' => $nftId,
            'type' => $type,
            'text' => $text,
        ])->do();

        if ($postId) {
            $this->doReply($imageType, $text, $postId, $resolvedImage);
        } else {
            $this->schedulePost($imageType, $text, $resolvedImage);
        }
    }
    
    private function schedulePost(string $imageType, string $text, ResolveImage $resolvedImage = null)
    {
        $post = new Post();
        $post->text = $text;
        $post->image = $resolvedImage?->urlCDN;
        $post->imageType = $imageType;
        $post->save();

        success('', 'Scheduled');
    }
    
    private function doReply(string $imageType, string $text, string $postId, ResolveImage $resolvedImage = null): void
    {
        // reply
        $xPost = new XPost();
        if ($resolvedImage) {
            if (!in_array($imageType, [
                'text_four_words_luc_diana',
                'text_centered_base_aliens',
                'text_centered_looney_luca',
                'text_centered_ripple_punks',
            ])) {
                $xPost->setText($text);
            }
            $xPost->setImage($resolvedImage->urlTMP);
        } else {
            $xPost->setText($text);
        }
        $result = $xPost->reply($postId);
        $xPost->clear();
        
        $success = true;
        if (array_key_exists('status', $result)) {
            $success = false;
            $readableResult = $result['status'] . ': ' . $result['detail'];
        } elseif (array_key_exists('errors', $result)) {
            $success = false;
            $readableResult = $result['title'] . ': ' . $result['detail'];

        } else {
            $readableResult = $result['data']['id'];
        }

        // store reply post results
        $post = new Post();
        if ($postId) {
            $post->postId = $postId;
        }
        $post->success = $success;
        $post->text = $text;
        $post->image = $resolvedImage->urlCDN;
        $post->imageType = $imageType;
        $post->readableResult = $readableResult;
        $post->result = $result;
        if ($success) {
            $post->postedAt = now();
        }
        $post->save();

        if ($success) {
            success('', 'Success: ' . $text);
        } else {
            abort('Not success: ' . $text);
        }
    }

    public function run()
    {
        parent::run();

        return $this;
    }


}

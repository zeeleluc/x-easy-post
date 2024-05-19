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
use App\Variable;

class Home extends BaseFormAction
{
    public function __construct()
    {
        if (!$this->getSession()->getItem('loggedIn')) {
            abort('login');
        }

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

        $this->setVariable(new Variable('lastPostsPosted', (new PostQuery())->getLastPostsPosted(now()->subWeek())));
        $this->setVariable(new Variable('lastPostsScheduled', (new PostQuery())->getLastPostsScheduled(now()->subWeek())));
        $this->setVariable(new Variable('lastPostsFailed', (new PostQuery())->getLastPostsFailed(now()->subWeek())));

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
            $this->scheduleReply($imageType, $type, $text, $postId, $resolvedImage);
        } else {
            $this->schedulePost($imageType, $type, $text, $resolvedImage);
        }
    }
    
    private function schedulePost(string $imageType, ?string $imageAttributeType, string $text, ResolveImage $resolvedImage = null)
    {
        $post = new Post();
        $post->text = $text;
        $post->image = $resolvedImage?->urlCDN;
        $post->imageType = $imageType;
        $post->imageAttributeType = $imageAttributeType;
        $post->save();

        success('#scheduled', 'Scheduled');
    }
    
    private function scheduleReply(string $imageType, ?string $imageAttributeType, string $text, string $postId, ResolveImage $resolvedImage = null): void
    {
        $post = new Post();
        $post->postId = $postId;
        $post->success = null;
        $post->text = $text;
        $post->image = $resolvedImage->urlCDN;
        $post->imageType = $imageType;
        $post->imageAttributeType = $imageAttributeType;
        $post->readableResult = null;
        $post->result = null;
        $post->save();

        success('#scheduled', 'Reply scheduled');
    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

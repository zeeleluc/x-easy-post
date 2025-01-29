<?php
namespace App\Action\Actions;

use App\Action\BaseFormAction;
use App\FormFieldValidator\Image;
use App\FormFieldValidator\ImageText;
use App\FormFieldValidator\NFTID;
use App\FormFieldValidator\PostID;
use App\FormFieldValidator\Project;
use App\FormFieldValidator\RegularString;
use App\FormFieldValidator\Type;
use App\Models\Post;
use App\Query\PostQuery;
use App\Service\Images\ImagesHelper;
use App\Service\Projects\GatherShillingProgress;
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
        $this->setVariable(new Variable('shilledScheduledPerProject', (new GatherShillingProgress())->perProject(true)));

        $this->setVariable(new Variable('lastPostsPosted', (new PostQuery())->getLastPostsPosted(now()->subWeek())));
        $this->setVariable(new Variable('lastPostsScheduled', (new PostQuery())->getLastPostsScheduled()));
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

            preg_match('/^\d+/', $postId, $matches);
            if (!empty($matches)) {
                $postId = $matches[0];
            } else {
                $postId = null;
            }
        }

        $validateFields = [];

        if ($postId) {
            $validateFields[] = new PostID('post_id', $postId);
        }

        if ($project = $this->getRequest()->getPostParam('project')) {

            $validateFields[] = new Project('project', $project);

            if ($image = $this->getRequest()->getPostParam('image')) {
                $validateFields[] = new Image('image', $image);

                if ($value = $this->getRequest()->getPostParam('text_image')) {
                    $validateFields[] = new ImageText('text_image', $value);
                }

                if ($value = $this->getRequest()->getPostParam('nft_id')) {
                    $validateFields[] = new NFTID('nft_id', $value, ImagesHelper::getImageClassByProjectAndSlug($project, $image));
                }

                if ($value = $this->getRequest()->getPostParam('type')) {
                    $validateFields[] = new Type(
                        'type',
                        $value,
                        ImagesHelper::getImageClassByProjectAndSlug($project, $image)
                    );
                }
            }
        }

        if ($value = $this->getRequest()->getPostParam('text')) {
            $validateFields[] = new RegularString('text', $value);
        }

        $this->validateFormValues($validateFields);
    }

    protected function handleForm(): void
    {
        $project = $this->validatedFormValues['project'] ?: null;
        $text = $this->validatedFormValues['text'] ?: null;
        $text = trim($text);
        $postId = $this->validatedFormValues['post_id'] ?: null;
        $textImage = $this->validatedFormValues['text_image'] ?: null;
        $textImage = trim($textImage);
        $imageType = $this->validatedFormValues['image'] ?: null;
        $nftId = $this->validatedFormValues['nft_id'] ?: null;
        $type = $this->validatedFormValues['type'] ?: null;

        if ($imageType) {
            $resolvedImage = ResolveImage::make($imageType, $project,  [
                'nft_id' => $nftId,
                'type' => $type,
                'text' => $textImage,
            ])->do();
        } else {
            $resolvedImage = null;
        }

        if ($postId) {
            $this->scheduleReply($project, $imageType, $type, $text, $textImage, $postId, $resolvedImage);
        } else {
            $this->schedulePost($project, $imageType, $type, $text, $textImage, $resolvedImage);
        }
    }
    
    private function schedulePost(
        ?string $project,
        ?string $imageType,
        ?string $imageAttributeType,
        ?string $text,
        ?string $textImage,
        ResolveImage $resolvedImage = null
    ): void {
        $post = new Post();
        $post->project = $project;
        $post->text = $text;
        $post->textImage = $textImage;
        $post->image = $resolvedImage?->urlCDN;
        $post->imageType = $imageType;
        $post->imageAttributeType = $imageAttributeType;
        $post->save();

        success('#scheduled', 'Scheduled');
    }
    
    private function scheduleReply(
        ?string $project,
        ?string $imageType,
        ?string $imageAttributeType,
        ?string $text,
        ?string $textImage,
        string $postId,
        ResolveImage $resolvedImage = null
    ): void {
        $post = new Post();
        $post->project = $project;
        $post->postId = $postId;
        $post->success = null;
        $post->text = $text;
        $post->textImage = $textImage;
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

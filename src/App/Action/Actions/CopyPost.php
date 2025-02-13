<?php
namespace App\Action\Actions;

use App\Action\BaseAction;
use App\Models\Post;
use App\Query\PostQuery;
use App\Service\ResolveImage;

class CopyPost extends BaseAction
{
    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct('');

        $id = (int) $this->getRequest()->getParam('id');
        if (!$id) {
            abort('', 'Error');
        }

        $post = (new PostQuery())->getPostById($id);
        if (!$post) {
            abort('', 'Error');
        }

        $post = (new Post())->fromArray($post);

        $resolveImage = ResolveImage::make($post->imageType, $post->project, [
            'nft_id' => null,
            'type' => $post->imageAttributeType ?: null,
            'text' => $post->textImage,
        ])->do();

        $post = $post->copy();
        $post->image = $resolveImage->urlCDN;
        $post->save();

        success('#scheduled', 'Successfully copied post');
    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

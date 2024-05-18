<?php
namespace App\Action\Actions;

use App\Action\BaseAction;
use App\Models\Post;
use App\Query\PostQuery;
use App\Service\ResolveImage;

class RedoImage extends BaseAction
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
        if (isset($post->success) && $post->success) {
            abort('', 'Error');
        }

        $resolveImage = ResolveImage::make($post->imageType, [
            'nft_id' => null,
            'type' => $post->imageAttributeType ?: null,
            'text' => $post->text,
        ])->do();

        $post->image = $resolveImage->urlCDN;
        $post->save();

        success('#scheduled', 'Successfully re-did image');
    }

    public function run()
    {
        parent::run();

        return $this;
    }


}

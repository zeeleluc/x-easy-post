<?php
namespace App\Action\Actions\Hypeomatic;

use App\Action\BaseAction;
use App\Query\ImageQuery;
use App\Service\ResolveImage;

class Redo extends BaseAction
{
    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct('');

        $uuid = $this->getRequest()->getParam('uuid');
        if (!$uuid) {
            abort();
        }

        $image = (new ImageQuery())->getImageByUuid($uuid);
        if (!$image) {
            abort();
        }

        $resolveImage = ResolveImage::make($image->imageType, $image->project, [
            'nft_id' => null,
            'type' => $image->nftType ?: null,
            'text' => $image->textImage,
        ])->do();

        $image->url = $resolveImage->urlCDN;
        $image->save();

        success('image/' . $image->uuid);
    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

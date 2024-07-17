<?php
namespace App\Action\Actions\Hypeomatic;

use App\Action\BaseFormAction;
use App\FormFieldValidator\Image as FormFieldValidatorImage;
use App\FormFieldValidator\ImageText;
use App\FormFieldValidator\NFTID;
use App\FormFieldValidator\PostID;
use App\FormFieldValidator\Project;
use App\FormFieldValidator\Type;
use App\Models\Image;
use App\Service\Images\ImagesHelper;
use App\Service\Projects\Projects;
use App\Service\ResolveImage;
use App\Variable;

class Home extends BaseFormAction
{
    public function __construct()
    {
        parent::__construct('');

        $this->setLayout('hypeomatic');
        $this->setView('hypeomatic/home');


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

        $project = $this->getRequest()->getParam('action') ?: null;
        if ($project) {
            $projectSlug = Projects::getSlugForNeatPublicProjectString($project);
            if (!$projectSlug) {
                throw new \Exception('Project `' . $project . '` not found.');
            }

            $this->setVariable(new Variable('selectedProjectSlug', $projectSlug));
        }

        $this->setVariable(new Variable('recentImages', $this->getImageQuery()->getRecentImages(24)));
    }

    /**
     * @throws \Exception
     */
    protected function performPost(): void
    {

        $validateFields = [];

        if ($project = $this->getRequest()->getPostParam('project')) {

            $validateFields[] = new Project('project', $project);

            if ($image = $this->getRequest()->getPostParam('image')) {
                $validateFields[] = new FormFieldValidatorImage('image', $image);

                if ($value = $this->getRequest()->getPostParam('text_image')) {
                    $validateFields[] = new ImageText('text_image', $value);
                }

                if ($value = $this->getRequest()->getPostParam('nft_id')) {
                    $validateFields[] = new NFTID('nft_id', $value);
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

        $this->validateFormValues($validateFields);
    }

    protected function handleForm(): void
    {
        $project = $this->validatedFormValues['project'] ?: null;
        $textImage = $this->validatedFormValues['text_image'] ?: null;
        $imageType = $this->validatedFormValues['image'] ?: null;
        $nftId = $this->validatedFormValues['nft_id'] ?: null;
        $type = $this->validatedFormValues['type'] ?: null;

        $resolvedImage = ResolveImage::make($imageType, $project,  [
            'nft_id' => $nftId,
            'type' => $type,
            'text' => $textImage,
        ])->do();

        $values = $this->validatedFormValues;
        $values['image_type'] = $imageType;
        $values['nft_type'] = $type;
        $values['url'] = $resolvedImage->urlCDN;

        if ($nftId) {
            $values['can_redo'] = 0;
        } else {
            $values['can_redo'] = 1;
        }

        $image = new Image();
        $image = $image->initNew($values);

        redirect('image/' . $image->uuid);
    }

    public function run()
    {
        parent::run();

        return $this;
    }
}

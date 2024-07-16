<?php

namespace App\Service\Form;

use App\Service\Images\ImagesHelper;

class FormHelper
{

    public ?string $hasProject = null;

    public bool $hasImage = false;
    public ?string $selectedImage = null;
    public array $imageOptions = [];
    public bool $hasImageText = false;
    public ?string $imageText = '';
    public ?array $nftIdRange = null;
    public ?int $nftId = null;
    public ?array $options = [];
    public ?string $selectedOption = '';

    public ImagesHelper $imagesHelper;

    public function __construct()
    {
        $this->imagesHelper = new ImagesHelper();
    }

    public function parse(array $logic): FormHelper
    {
//        echo '<pre>'; var_dump($logic); echo '</pre>';

        if (array_key_exists('project', $logic)) {

            $project = $logic['project'];
            $projectName = convert_snakecase_to_project_name($project);

            $imagesClasses = $this->imagesHelper->getImagesClassesForProject($projectName);
            foreach ($imagesClasses as $imageClass) {
                $this->imageOptions[$imageClass::getClassName()] = $imageClass::getName();
            }

            // options to select image
            if ($this->imageOptions) {
                $this->hasImage = true;
            }

            // already selected image
            if (array_key_exists('image', $logic) && $logic['image']) {
                $this->selectedImage = $logic['image'];

                $imageClass = $this->imagesHelper->getImageClassForProjectBySlug($projectName, $this->selectedImage);
                if ($imageClass) {
                    $this->nftIdRange = $imageClass::getIdRange();
                    $this->hasImageText = $imageClass::canHaveImageText();
                    $this->options = $imageClass::getOptions();
                }

                if (array_key_exists('text_image', $logic) && $logic['text_image']) {
                    $this->imageText = $logic['text_image'];
                }

                if (array_key_exists('nft_id', $logic) && $logic['nft_id']) {
                    $this->nftId = $logic['nft_id'];
                }

                if (array_key_exists('type', $logic) && $logic['type']) {
                    $this->selectedOption = $logic['type'];
                }
            }
        }

        return $this;
    }
}

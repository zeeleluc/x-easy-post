<?php
namespace App\Action\Actions\Cli;

use App\Action\BaseAction;
use App\Query\PostQuery;
use App\Service\XPost;

class NextPost extends BaseAction
{

    private PostQuery $postQuery;

    public function __construct()
    {
        $this->postQuery = new PostQuery();
    }

    public function run()
    {
        $post = $this->postQuery->getNextScheduledPost();

        if ($post) {
            $xPost = new XPost();
            if ($post->text) {
                if (!in_array($post->imageType, [
                    'text_four_words_luc_diana',
                    'text_centered_base_aliens',
                    'text_centered_looney_luca',
                    'text_centered_ripple_punks',
                ])) {
                    $xPost->setText($post->text);
                }
            }
            if ($post->image) {
                $path = ROOT . '/tmp/' . uniqid();
                $image = file_get_contents($post->image);
                file_put_contents($path, $image);
                chmod($path, 0777);

                $xPost->setImage($path);
            }
            $result = $xPost->post();

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

            $post->readableResult = $readableResult;
            $post->success = $success;
            if ($success) {
                $post->postedAt = now();
            }
            $post->save();
        }
    }
}

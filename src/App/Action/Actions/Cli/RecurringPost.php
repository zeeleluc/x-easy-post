<?php
namespace App\Action\Actions\Cli;

use App\Action\BaseAction;
use App\Service\XPost;

class RecurringPost extends BaseAction
{

    public function __construct(public string $type)
    {

    }

    public function run()
    {
        foreach (get_all_accounts() as $account) {
            $xPost = new XPost($account);
            $text = $this->getText($account);
            if ($text) {
                $xPost->setText($text);
                $images = $this->getImages($account);
                if ($images) {
                    $xPost->setImage($images[0]);
                }
                $xPost->post();
            }
        }
    }

    private function getText($account): string
    {
        if (in_array(date('N'), [2, 6])) { // Tuesday and Saturday
            if ($this->type === 'gm') {
                if ($account === 'HasMints') {
                    return 'GM web3, enjoy your day, tell your friends it\'s another day to mint 💦';
                } elseif ($account === 'NoBased') {
                    return 'GM from No-Based on @base ✊🏻';
                } elseif ($account === 'RipplePunks') {
                    return 'GM Punks on XRPL - keep grinding 👑';
                } elseif ($account === 'PunkDerivs') {
                    return 'GM Punks!';
                }
            }
        }

        return '';
    }

    private function getImages($account): array
    {
        if ($this->type === 'gm') {
            if ($account === 'HasMints') {
                return [ROOT . '/assets/images/luc.png'];
            } elseif ($account === 'NoBased') {
                return [download_remote_url_and_return_temp_path('nobased', '15.png')['urlTMP']];
            } elseif ($account === 'RipplePunks') {
                return [download_remote_url_and_return_temp_path('ripplepunks', '15.png')['urlTMP']];
            } elseif ($account === 'PunkDerivs') {
                return [ROOT . '/assets/images/pd-logo.png'];
            }
        }

        return [];
    }
}

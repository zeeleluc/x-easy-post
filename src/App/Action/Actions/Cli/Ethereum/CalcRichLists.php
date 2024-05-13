<?php
namespace App\Action\Actions\Cli\Ethereum;

use App\Action\Actions\Cli\Interfaces\CliActionInterface;
use App\Action\BaseAction;
use App\Services\Ethereum\CalcRichListService;

class CalcRichLists extends BaseAction implements CliActionInterface
{
    private const CHAIN = 'ethereum';

    /**
     * @throws \Exception
     */
    public function run(string $forProject = null)
    {
        foreach ($this->getUserQuery()->getAll() as $user) {

            $project = $user->projectName;

            if ($forProject && $project !== $forProject) {
                continue;
            }

            $countsPerWallet = (new CalcRichListService($project))->getCountsPerWallet();

            // filter out unwanted wallets for the rich list
            $unwantedWallets = env('WALLETS_IGNORE_' . strtoupper($project));
            if ($unwantedWallets) {
                $unwantedWallets = explode(',', $unwantedWallets);
                foreach ($unwantedWallets as $unwantedWallet) {
                    if (array_key_exists($unwantedWallet, $countsPerWallet)) {
                        unset($countsPerWallet[$unwantedWallet]);
                    }
                }
            }

            if ($countsPerWallet) {
                $fileName = ROOT . '/data/richlists-cache/' . $user->projectSlug . '-' . self::CHAIN . '.json';
                $result = file_put_contents($fileName, json_encode($countsPerWallet));

                if (!$result) {
                    $slack = new \App\Slack();
                    $slack->sendErrorMessage('Writing rich list data to file `' . $fileName . '` failed!');
                }
            }
        }
    }
}

<?php
namespace App\Action\Actions\Cli\XRPL;

use App\Action\Actions\Cli\Interfaces\CliActionInterface;
use App\Action\BaseAction;
use App\Slack;
use Carbon\Carbon;

class AnalyzeNFTs extends BaseAction implements CliActionInterface
{
    private const CHAIN = 'xrpl';

    private Slack $slack;

    public function __construct()
    {
        $this->slack = new Slack();
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        foreach ($this->getCollectionQuery()->getAllForChain(self::CHAIN) as $collection) {
            $issuer = $collection->config['issuer'];
            $taxon = $collection->config['taxon'] ?? null;

            $tableNameNFTs = $this->getTableNFTs($issuer, $taxon);
            $oldestCreatedAt = Carbon::parse($this->getBlockchainTokenQuery()->getOldestRecord($tableNameNFTs)['created_at']);
            $newestCreatedAt = Carbon::parse($this->getBlockchainTokenQuery()->getNewestRecord($tableNameNFTs)['created_at']);

            $diffInSeconds = $oldestCreatedAt->diffInSeconds($newestCreatedAt);
            $tenMinutesInSeconds = 60;

            if ($diffInSeconds > $tenMinutesInSeconds) {
                $text = 'Diff between oldest and newest record for `' . $collection->name . '` is more than 1 hour.';
                $slack = new Slack();
                $slack->sendErrorMessage($text);
            }
        }
    }

    private function getTableNFTs(string $issuer, int $taxon = null)
    {
        if ($taxon) {
            return self::CHAIN . '_' . $issuer . '_' . $taxon . '_nfts';
        }

        return self::CHAIN . '_' . $issuer . '_nfts';
    }
}

<?php
namespace App\Action\Actions\Cli\Ethereum;

use App\Action\Actions\Cli\Interfaces\CliActionInterface;
use App\Action\Actions\Cli\Interfaces\UpdateDataNFTInterface;
use App\Action\Actions\Cli\Traits\UpdateDataNFTTrait;
use App\Action\BaseAction;
use App\Slack;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class UpdateDataNFT extends BaseAction implements
    CliActionInterface,
    UpdateDataNFTInterface
{
    use UpdateDataNFTTrait;

    private const CHAIN = 'ethereum';

    private Client $client;

    private Slack $slack;

    public function __construct()
    {
        $this->client = new Client();
        $this->slack = new Slack();
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        $this->handleTables();
        $this->updateNFTdata();
    }

    /**
     * @throws \Exception
     */
    public function updateNFTdata(): void
    {
        $collectionsDoneByIssuerTaxon = [];

        foreach ($this->getCollectionQuery()->getAllForChain(self::CHAIN) as $collection) {

            $contract = $collection->config['contract'];
            $configIdentifier = $contract;

            if (in_array($configIdentifier, $collectionsDoneByIssuerTaxon)) {
                continue;
            }
            try {
                $cursor = null;
                do {

                    $response = $this->client->request('GET', $this->assembleUrl($contract, $cursor), [
                        'headers' => [
                            'Accept' => 'application/json',
                            'X-API-Key' => env('MORALIS_API_KEY'),
                        ],
                    ]);

                    $response = (array) json_decode($response->getBody(), true);

                    $tableNameNFTs = $this->getTableNFTs($contract);
                    foreach ($response['result'] as $result) {
                        $this->getBlockchainTokenQuery()->insertNFTdata(
                            $tableNameNFTs,
                            $result,
                            'token_id',
                            $result['token_id']
                        );
                    }

                    $cursor = array_key_exists('cursor', $response) ?
                        $response['cursor'] :
                        null;

                    echo $cursor . PHP_EOL;

                } while(is_string($cursor));

            } catch (GuzzleException $e) {
                $this->slack->sendErrorMessage($e->getMessage());
            }

            $collectionsDoneByIssuerTaxon[] = $configIdentifier;
        }
    }

    /**
     * @throws \Exception
     */
    public function handleTables()
    {
        foreach ($this->getCollectionQuery()->getAllForChain(self::CHAIN) as $collection) {
            $contract = $collection->config['contract'];

            $tableNameNFTs = $this->getTableNFTs($contract);
            if (!$this->getBlockchainTokenQuery()->hasTable($tableNameNFTs)) {
                $this->getBlockchainTokenQuery()->createTableNFTsEthereum($tableNameNFTs);
            }
        }
    }

    private function assembleUrl(string $contract, string $cursor = null): string
    {
        $url = env('MORALIS_API_URL') . 'nft/' . $contract . '/owners?chain=eth&format=decimal&limit=100';

        if ($cursor) {
            return $url . '&cursor=' . $cursor;
        }

        return $url;
    }
}

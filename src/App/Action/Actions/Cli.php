<?php
namespace App\Action\Actions;

use App\Action\Actions\Cli\CheckPosts;
use App\Action\Actions\Cli\ClearOldMagicTokens;
use App\Action\Actions\Cli\Migrate;
use App\Action\Actions\Cli\NextPost;
use App\Action\BaseAction;
use App\Models\AuthIdentifier;
use App\Service\AuthIdentifierService;

class Cli extends BaseAction
{

    private string $action;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->terminal = true;
        parent::__construct();

        if (!$_SERVER['argv']) {
            exit;
        }

        if (!isset($_SERVER['argv'][1])) {
            exit;
        }

        $this->action = $_SERVER['argv'][1];

        if ($this->action === 'migrate') {
            (new Migrate())->run();
        }

        if ($this->action === 'next-post') {
            (new NextPost())->run();
        }

        if ($this->action === 'check-posts') {
            (new CheckPosts())->run();
        }

        if ($this->action === 'create-auth-identifier') {
            AuthIdentifierService::new();
        }

        if ($this->action === 'clear-old-magic-tokens') {
            (new ClearOldMagicTokens())->run();
        }


        if ($this->action === 'read-metadata') {

            $data = [];
            foreach (glob(ROOT . '/data/weepingplebs/*.json') as $metadata) {

                $metadata = (array) json_decode(file_get_contents($metadata), true);
                $name = $metadata['name'];
                $id = (int) str_replace('WeepingPleb #', '', $name);

                foreach ($metadata['attributes'] as $attribute) {
                    if (!is_numeric($attribute['value'])) {
                        $attribute['value'] = trim($attribute['value']);
                        if (!array_key_exists('Attribute:' . $attribute['value'], $data)) {
                            $data['Attribute:' . $attribute['value']] = [];
                        }
                    }
                }

                foreach ($metadata['attributes'] as $attribute) {
                    if (!is_numeric($attribute['value'])) {
                        $attribute['value'] = trim($attribute['value']);
                        $data['Attribute:' . $attribute['value']][] = $id;
                    }
                }
            }

            file_put_contents('./migrations/migration_data/weepingplebs.json', json_encode($data));
        }
    }
}

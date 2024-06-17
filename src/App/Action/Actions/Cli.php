<?php
namespace App\Action\Actions;

use App\Action\Actions\Cli\CheckPosts;
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
            $authIdentifier = AuthIdentifierService::new();
            AuthIdentifierService::slack($authIdentifier);
        }


//        if ($this->action === 'tmp') {
//
//            $phpCode = '<?php' . PHP_EOL . PHP_EOL;
//            $phpCode .= 'function get_ids_for_basealiens_type(string $type): array' . PHP_EOL;
//            $phpCode .= '{' . PHP_EOL;
//            $phpCode .= '    return [' . PHP_EOL;
//
//            $types = [];
//            foreach (glob(ROOT . '/data/basealiens/*.json') as $metadata) {
//
//                $metadata = (array) json_decode(file_get_contents($metadata), true);
//                $name = $metadata['name'];
//                $id = (int) str_replace('BaseAlien #', '', $name);
//
//                foreach ($metadata['attributes'] as $attribute) {
//                    if (!is_numeric($attribute['value'])) {
//                        $attribute['value'] = trim($attribute['value']);
//                        if (!array_key_exists('Attribute:' . $attribute['value'], $types)) {
//                            $types['Attribute:' . $attribute['value']] = [];
//                        }
//                    }
//                }
//
//                foreach ($metadata['attributes'] as $attribute) {
//                    if (!is_numeric($attribute['value'])) {
//                        $attribute['value'] = trim($attribute['value']);
//                        $types['Attribute:' . $attribute['value']][] = $id;
//                    }
//                }
//            }
//
//            foreach ($types as $type => $ids) {
//                $phpCode .= "            '" . $type . "' => [" . PHP_EOL;
//                foreach ($ids as $id) {
//                    $phpCode .= "                " . $id . "," . PHP_EOL;
//                }
//                $phpCode .= "            ]," . PHP_EOL;
//            }
//
//            $phpCode .= '    ];' . PHP_EOL;
//            $phpCode .= '}' . PHP_EOL;
//            $phpCode .= PHP_EOL;
//            file_put_contents('basealiens_type_attributes.php', $phpCode);
//
//        }
    }
}

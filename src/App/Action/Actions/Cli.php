<?php
namespace App\Action\Actions;

use App\Action\Actions\Cli\Migrate;
use App\Action\BaseAction;

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

        if ($this->action === 'tmp') {
            echo '$array => [' . PHP_EOL;
            $metadataFiles = glob(ROOT . '/data/metadata/*.json');
            $i = 1;
            foreach ($metadataFiles as $metadataFile) {
                $json = file_get_contents($metadataFile);
                $array = (array) json_decode($json, true);

                $hasBackground = false;
                foreach ($array['attributes'] as $attribute) {
                    if ($attribute['trait_type'] === 'Background') {
                        $hasBackground = true;
                    }
                }

                if (!$hasBackground) {
                    $color = pick_color(ROOT . '/data/looneyluca/' . $i . '.png', 1, 1);
                    echo '    ' . $i . ' => ' . $color . ',' . PHP_EOL;
                }
                $i++;
            }
            echo ']';
        }
    }
}

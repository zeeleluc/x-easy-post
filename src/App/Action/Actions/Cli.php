<?php
namespace App\Action\Actions;

use App\Action\Actions\Cli\Migrate;
use App\Action\Actions\Cli\NextPost;
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

        if ($this->action === 'next-post') {
            (new NextPost())->run();
        }

        if ($this->action === 'tmp') {
            echo '$array => [' . PHP_EOL;
            $metadataFiles = glob(ROOT . '/data/metadata/*.json');
            foreach ($metadataFiles as $metadataFile) {
                $json = file_get_contents($metadataFile);
                $array = (array) json_decode($json, true);
                $name = $array['name'];
                $id = (int) str_replace('Looney Luca #', '', $name);

                $hasBackground = false;
                foreach ($array['attributes'] as $attribute) {
                    if ($attribute['trait_type'] === 'Background') {
                        $hasBackground = true;
                        break;
                    }
                }

                if (!$hasBackground) {
                    $color = pick_color(ROOT . '/data/looneyluca/' . $id . '.png', 1, 1);
                    echo '    ' . $id . ' => ' . $color . ',' . PHP_EOL;
                }
            }
            echo ']';
        }

        if ($this->action === 'tmp-cryptopunks') {

            $phpCode = '<?php' . PHP_EOL . PHP_EOL;
            $phpCode .= 'function get_ids_for_cryptopunks_type(string $type): array' . PHP_EOL;
            $phpCode .= '{' . PHP_EOL;
            $phpCode .= '    return [' . PHP_EOL;

            $csvFile = file(ROOT . '/data/properties.csv');
            $data = [];

            $types = [];
            foreach ($csvFile as $i => $line) {
                $data[] = str_getcsv($line);

                $properties = $data[$i];

                $id = (int) $properties[0];
                $type = trim($properties[1]);
                $gender = trim($properties[2]);
                $attributes = explode('/', $properties[5]);

                if ($type !== 'Human') {
                    if (!array_key_exists('Type:' . $type, $types)) {
                        $types['Type:' . $type] = [];
                    }
                }

                if ($gender !== 'Female' && $gender !== 'Male') {
                    if (!array_key_exists('Gender:' . $gender, $types)) {
                        $types['Gender:' . $gender] = [];
                    }
                }

                foreach ($attributes as $attribute) {
                    $attribute = trim($attribute);
                    if (!array_key_exists('Attribute:' . $attribute, $types)) {
                        $types['Attribute:' . $attribute] = [];
                    }
                }

                if ($type !== 'Human') {
                    $types['Type:' . $type][] = $id;
                }
                if ($gender !== 'Female' && $gender !== 'Male') {
                    $types['Gender:' . $gender][] = $id;
                }
                foreach ($attributes as $attribute) {
                    $attribute = trim($attribute);
                    $types['Attribute:' . $attribute][] = $id;
                }
            }

            foreach ($types as $type => $ids) {
                $phpCode .= "        '" . $type . "' => [" . PHP_EOL;
                foreach ($ids as $id) {
                    $phpCode .= "            " . $id . "," . PHP_EOL;
                }
                $phpCode .= "        ]," . PHP_EOL;
            }

            $phpCode .= '    ];' . PHP_EOL;
            $phpCode .= '}' . PHP_EOL;
            $phpCode .= PHP_EOL;
            file_put_contents('punks_type_attributes.php', $phpCode);

        }
    }
}

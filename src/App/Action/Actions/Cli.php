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
    }
}

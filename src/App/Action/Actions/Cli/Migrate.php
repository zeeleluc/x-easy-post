<?php
namespace App\Action\Actions\Cli;

use App\Action\Actions\Cli\Interfaces\CliActionInterface;
use App\Action\BaseAction;
use App\Query\MigrationQuery;

class Migrate extends BaseAction implements CliActionInterface
{

    private MigrationQuery $migrationQuery;

    private array $migrationsDone;

    public function __construct()
    {
        $this->migrationQuery = new MigrationQuery();
        $this->migrationsDone = $this->migrationQuery->getAllIdentifiers();
    }

    public function run()
    {
        $this->runSqlMigrations();
        $this->runPhpMigrations();
    }

    private function runPhpMigrations(): void
    {
        $migrations = $this->getPhpMigrationFiles($this->migrationsDone);

        if (!$migrations) {
            echo 'No PHP migrations found.' . PHP_EOL;
        } else {
            foreach ($migrations as $migration) {

                require_once(ROOT . '/migrations/php/' . $migration . '.php');

                $migrationInstance = new \Migration();
                $migrationInstance->run();

                $this->migrationQuery->addMigrationDone('php-' . $migration);

                echo 'PHP migration ' . $migration . ' done!' . PHP_EOL;
            }
        }
    }

    private function getPhpMigrationFiles(array $excludeMigrations = []): array
    {
        $migrationsToDo = [];

        $migrationFiles = glob(ROOT . '/migrations/php/*.php');
        foreach ($migrationFiles as $migrationFile) {
            $pathInfo = pathinfo($migrationFile);
            $identifier = $pathInfo['filename'];

            if (!in_array('php-' . $identifier, $excludeMigrations)) {
                $migrationsToDo[] = $identifier;
            }
        }

        return $migrationsToDo;
    }

    private function runSqlMigrations(): void
    {
        $migrations = $this->getSqlMigrationFiles($this->migrationsDone);

        if (!$migrations) {
            echo 'No SQL migrations found.' . PHP_EOL;
        } else {
            foreach ($migrations as $migration) {
                $sql = file_get_contents(ROOT . '/migrations/sql/' . $migration . '.sql');
                $this->migrationQuery->executeMigration($sql, $migration);

                echo 'SQL migration ' . $migration . ' done!' . PHP_EOL;
            }
        }
    }

    private function getSqlMigrationFiles(array $excludeMigrations = []): array
    {
        $migrationsToDo = [];

        $migrationFiles = glob(ROOT . '/migrations/sql/*.sql');
        foreach ($migrationFiles as $migrationFile) {
            $pathInfo = pathinfo($migrationFile);
            $identifier = $pathInfo['filename'];

            if (!in_array('sql-' . $identifier, $excludeMigrations)) {
                $migrationsToDo[] = $identifier;
            }
        }

        return $migrationsToDo;
    }
}

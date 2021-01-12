<?php

namespace app\core\db;

use app\core\Application;
use app\migrations\m0001_create_users_table;

class Database
{
    public \PDO $pdo;

    public function __construct($dbConfig)
    {
        $dsn = $dbConfig['DB_DSN'];
        $user = $dbConfig['DB_USER'];
        $password = $dbConfig['DB_PASSWORD'];
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function query($sql)
    {
        return $this->pdo->query($sql);
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $migrationsFromDatabase = $this->getMigrationsFromDatabase();
        $migrationsFromRepository = $this->getMigrationsFromRepository();
        $migrationsToApply = array_diff($migrationsFromRepository, $migrationsFromDatabase);
        $appliedMigrations = [];
        foreach ($migrationsToApply as $migrationToApply) {
            $migrationFile = Application::$root . '/migrations/' . $migrationToApply . '.php';
            require_once $migrationFile;
            $migrationObject = new $migrationToApply();
            echo "Applying migration {$migrationToApply}..." . PHP_EOL;
            $migrationObject->up();
            echo "Migration {$migrationToApply} successfully applied" . PHP_EOL;
            $appliedMigrations[] = $migrationToApply;
        }
        if (!empty($appliedMigrations)) $this->saveAppliedMigrations($appliedMigrations);
    }

    public function reverseMigrations()
    {
        $this->dropMigrationsTable();
        $migrationsFromRepository = $this->getMigrationsFromRepository();
        $reversedMigrations = [];
        foreach ($migrationsFromRepository as $migrationFromRepository) {
            $migrationFile = Application::$root . '/migrations/' . $migrationFromRepository . '.php';
            require_once $migrationFile;
            $migrationObject = new $migrationFromRepository();
            echo "Reversing migration {$migrationFromRepository}..." . PHP_EOL;
            $migrationObject->down();
            echo "Migration {$migrationFromRepository} successfully reversed" . PHP_EOL;
            $reversedMigrations[] = $migrationFromRepository;
        }
    }

    private function createMigrationsTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS migrations
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
        ";
        $this->pdo->query($sql);
    }

    private function dropMigrationsTable()
    {
        $sql = "DROP TABLE IF EXISTS migrations";
        $this->pdo->query($sql);
    }

    private function getMigrationsFromDatabase()
    {
        $sql = 'SELECT `name` FROM migrations';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return array_column($result, 'name');
    }

    private function getMigrationsFromRepository()
    {
        $migrationsDirectory = Application::$root . '/migrations';
        $fullDirectoryContents = scandir($migrationsDirectory);
        $migrationsFromRepository = [];
        foreach ($fullDirectoryContents as $directoryItem) {
            if ($directoryItem === '.' || $directoryItem === '..') {
                continue;
            }
            $migrationsFromRepository[] = pathinfo($directoryItem, PATHINFO_FILENAME);
        }
        return $migrationsFromRepository;
    }

    private function saveAppliedMigrations($appliedMigrations)
    {
        $values = array_map(fn($m) => "('{$m}')", $appliedMigrations);
        $implodedValues = implode(',', $values);
        $sql = "INSERT INTO migrations (`name`) VALUES {$implodedValues}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }
}
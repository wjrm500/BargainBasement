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
        // die(var_dump($migrationsToApply));
        foreach ($migrationsToApply as $migrationToApply) {
            $migrationFile = Application::$root . '/migrations/' . $migrationToApply . '.php';
            require_once $migrationFile;
            $migrationObject = new $migrationToApply();
            echo "Applying migration {$migrationToApply}..." . PHP_EOL;
            $migrationObject->up();
            echo "Migration {$migrationToApply} successfully applied" . PHP_EOL;
            $appliedMigrations[] = $migrationToApply;
        }
        if (!empty($appliedMigrations)) $this->saveAppliedMigrations($appliedMigrations, 'up');
    }

    public function reverseMigrations($reverseUntil = '')
    {
        $this->createMigrationsTable();
        $migrationsFromDatabase = $this->getMigrationsFromDatabase();
        $migrationsToReverse = $migrationsFromDatabase;
        $reversedMigrations = [];
        foreach (array_reverse($migrationsToReverse) as $migrationToReverse) {
            if ($migrationToReverse === $reverseUntil) break;
            $migrationFile = Application::$root . '/migrations/' . $migrationToReverse . '.php';
            require_once $migrationFile;
            $migrationObject = new $migrationToReverse();
            echo "Reversing migration {$migrationToReverse}..." . PHP_EOL;
            $migrationObject->down();
            echo "Migration {$migrationToReverse} successfully reversed" . PHP_EOL;
            $reversedMigrations[] = $migrationToReverse;
        }
        if (!empty($reversedMigrations)) $this->saveAppliedMigrations($reversedMigrations, 'down');
    }

    private function createMigrationsTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS migrations
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255),
                    direction VARCHAR(4),
                    migrated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
        ";
        $this->pdo->query($sql);
    }

    private function getMigrationsFromDatabase()
    {
        $sql = "
            SELECT m.`name`
            FROM migrations AS m
                JOIN (
                        SELECT `name`, MAX(migrated_at) AS max_migrated_at
                        FROM migrations
                        GROUP BY `name`
                        ) AS sq
                    ON m.`name` = sq.`name`
                        AND m.migrated_at = sq.max_migrated_at
            WHERE direction = 'up'
        ";
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

    private function saveAppliedMigrations($appliedMigrations, $direction)
    {
        $values = array_map(fn($m) => "('{$m}', '{$direction}')", $appliedMigrations);
        $implodedValues = implode(',', $values);
        $sql = "INSERT INTO migrations (`name`, direction) VALUES {$implodedValues}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }
}
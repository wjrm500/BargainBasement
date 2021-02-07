<?php

namespace app\core\db;

use app\core\Application;
use app\core\Model;

abstract class DbModel extends Model
{
    public int $id = 0;
    public \PDO $pdo;

    abstract public static function tableName(): string;

    public function __construct()
    {
        $this->pdo = Application::$app->database->pdo;
    }

    public function save($returnLastInsertId = false)
    {
        $tableName = static::tableName();
        $attributes = array_filter(static::attributes(), fn($attribute) => isset($this->{$attribute}));
        $implodedAttributes = implode(',', $attributes);
        $values = array_map(fn($attribute) => ":$attribute", $attributes);
        $implodedValues = implode(',', $values);
        $sql = "INSERT INTO {$tableName} ({$implodedAttributes}) VALUES ({$implodedValues})";
        $statement = $this->pdo->prepare($sql);
        foreach ($attributes as $attribute) {
            $statement->bindParam(":$attribute", $this->{$attribute});
        }
        $result = $statement->execute();
        return $returnLastInsertId ? Application::$app->database->pdo->lastInsertId() : $result;
    }

    public function update()
    {
        $tableName = static::tableName();
        $attributes = array_filter(static::attributes(), fn($attribute) => isset($this->{$attribute}));
        $values = array_map(fn($attribute) => "$attribute = :$attribute", $attributes);
        $implodedValues = implode(',', $values);
        $sql = "UPDATE {$tableName} SET {$implodedValues} WHERE id = {$this->id}";
        $statement = $this->pdo->prepare($sql);
        foreach ($attributes as $attribute) {
            $statement->bindParam(":$attribute", $this->{$attribute}, $this->pdo::PARAM_STR);
        }
        $result = $statement->execute();
        return $result;
    }

    public function delete()
    {
        $tableName = static::tableName();
        $sql = "DELETE FROM {$tableName} WHERE id = {$this->id}";
        return $this->pdo->query($sql);
    }

    public function bindAndSave($data, $returnLastInsertId = false)
    {
        $this->bindData($data);
        return $this->save($returnLastInsertId);
    }

    private static function getExecutedStatement(Array $whereConditions)
    {
        $tableName = static::tableName();
        $sql = "SELECT * FROM {$tableName} WHERE ";
        $whereClauseArr = [];
        foreach ($whereConditions as $key => $value) {
            if (is_array($value)) {
                $values = implode(',', $value);
                $whereClauseArr[] = "{$key} IN {$values}";
            }
            $whereClauseArr[] = "{$key} = '{$value}'";
        }
        $whereClause = implode(' AND ', $whereClauseArr);
        $sql .= $whereClause;
        $statement = Application::$app->database->pdo->prepare($sql);
        $statement->execute();
        return $statement;
    }

    public function load(Array $whereConditions)
    {
        $statement = self::getExecutedStatement($whereConditions);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $this->bindData($statement->fetch());
    }

    public static function find(Array $whereConditions, $fetchAll = false)
    {
        $statement = self::getExecutedStatement($whereConditions);
        $statement->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $fetchAll ? $statement->fetchAll() : $statement->fetch();
    }

    public static function findAll()
    {
        $tableName = static::tableName();
        $sql = "SELECT * FROM {$tableName}";
        $statement = Application::$app->database->pdo->prepare($sql);
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $statement->fetchAll();
    }
}
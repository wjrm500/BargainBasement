<?php

namespace app\core\db;

use app\core\Application;
use app\core\Model;

abstract class DbModel extends Model
{
    public int $id = 0;

    abstract public static function tableName(): string;

    public function save($returnLastInsertId = false)
    {
        $tableName = static::tableName();
        $attributes = array_filter(static::attributes(), fn($attribute) => isset($this->{$attribute}));
        $implodedAttributes = implode(',', $attributes);
        $values = array_map(fn($attribute) => ":$attribute", $attributes);
        $implodedValues = implode(',', $values);
        $sql = "INSERT INTO {$tableName} ({$implodedAttributes}) VALUES ({$implodedValues})";
        $statement = Application::$app->database->prepare($sql);
        foreach ($attributes as $attribute) {
            $statement->bindParam(":$attribute", $this->{$attribute});
        }
        $result = $statement->execute();
        return $returnLastInsertId ? Application::$app->database->pdo->lastInsertId() : $result;
    }

    public function bindAndSave($data, $returnLastInsertId = false)
    {
        $this->bindData($data);
        return $this->save($returnLastInsertId);
    }

    public static function find(Array $whereConditions, $fetchAll = false)
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
        $statement = Application::$app->database->prepare($sql);
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $fetchAll ? $statement->fetchAll() : $statement->fetch();
    }

    public static function findAll()
    {
        $tableName = static::tableName();
        $sql = "SELECT * FROM {$tableName}";
        $statement = Application::$app->database->prepare($sql);
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $statement->fetchAll();
    }
}
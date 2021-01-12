<?php

namespace app\core\db;

use app\core\Application;
use app\core\Model;
use PDOException;

abstract class DbModel extends Model
{
    abstract public static function tableName(): string;

    public function save()
    {
        $tableName = static::tableName();
        $attributes = array_filter($this->attributes(), fn($attribute) => isset($this->{$attribute}));
        $implodedAttributes = implode(',', $attributes);
        $values = array_map(fn($attribute) => ":$attribute", $attributes);
        $implodedValues = implode(',', $values);
        $sql = "INSERT INTO {$tableName} ({$implodedAttributes}) VALUES ({$implodedValues})";
        $statement = Application::$app->database->prepare($sql);
        foreach ($attributes as $attribute) {
            $statement->bindParam(":$attribute", $this->{$attribute});
        }
        return $statement->execute();
    }

    public static function find(Array $whereConditions)
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
        return $statement->fetchObject(static::class);
    }
}
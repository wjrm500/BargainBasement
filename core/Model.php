<?php

namespace app\core;

abstract class Model
{
    public const RULE_REQUIRED = 'requiredRule';
    public const RULE_MATCH = 'matchRule';
    public const RULE_UNIQUE = 'uniqueRule';
    public const RULE_EXISTS = 'existsRule';
    public const RULE_PASSWORD = 'passwordRule';
    public array $errors = [];

    abstract public function attributes(): array;

    abstract public function labels(): array;

    abstract public function rules(): array;

    public function bindData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function validate()
    {
        $attributes = $this->attributes();
        $rules = $this->rules();
        foreach ($attributes as $attribute) {
            $rulesForAttribute = $rules[$attribute];
            foreach ($rulesForAttribute as $ruleForAttribute) {
                if ($ruleForAttribute === self::RULE_REQUIRED) {
                    if (!$this->{$attribute}) {
                        $this->addError($attribute, $ruleForAttribute);
                    }
                }
                if (is_array($ruleForAttribute) && in_array(self::RULE_MATCH, $ruleForAttribute)) {
                    $attributeToMatch = $ruleForAttribute['attributeToMatch'];
                    if ($this->{$attribute} !== $this->{$attributeToMatch}) {
                        $this->addError($attribute, $ruleForAttribute);
                    }
                }
                if (is_array($ruleForAttribute) && in_array(self::RULE_UNIQUE, $ruleForAttribute)) {
                    $class = $ruleForAttribute['class'];
                    $attributeName = $ruleForAttribute['attribute'] ?? $attribute;
                    if ($class::find([$attributeName => $this->{$attribute}])) {
                        $this->addError($attribute, $ruleForAttribute);
                    };
                }
                if (is_array($ruleForAttribute) && in_array(self::RULE_EXISTS, $ruleForAttribute)) {
                    $class = $ruleForAttribute['class'];
                    $attributeName = $ruleForAttribute['attribute'] ?? $attribute;
                    if (!$class::find([$attributeName => $this->{$attribute}])) {
                        $this->addError($attribute, $ruleForAttribute);
                    };
                }
                if (is_array($ruleForAttribute) && in_array(self::RULE_PASSWORD, $ruleForAttribute)) {
                    /** @var string $class */
                    /** @var string $searchField */
                    /** @var string $searchAttribute */
                    extract($ruleForAttribute);
                    if ($dbUser = $class::find([$searchField => $this->{$searchAttribute}])) {
                        $dbPassword = $dbUser->password;
                        if (!password_verify($this->password, $dbPassword)) {
                            $this->addError($attribute, $ruleForAttribute);
                        }
                    };
                }
            }
        }
        return empty($this->errors);
    }

    public function errorMessages()
    {
        return [
            self::RULE_REQUIRED => '{attribute} is required',
            self::RULE_MATCH    => '{attribute} must match {attributeToMatch}',
            self::RULE_UNIQUE   => '{attribute} must be unique',
            self::RULE_EXISTS   => '{attribute} does not exist',
            self::RULE_PASSWORD => '{attribute} incorrect'
        ];
    }

    public function addError($attribute, $ruleForAttribute)
    {
        if (is_string($ruleForAttribute)) {
             $errorMessage = $this->errorMessages()[$ruleForAttribute];
             $errorMessage = str_replace('{attribute}', $this->labels()[$attribute], $errorMessage);
             
        }
        if (is_array($ruleForAttribute)) {
            $errorMessage = $this->errorMessages()[$ruleForAttribute[0]];
            $errorMessage = str_replace('{attribute}', $this->labels()[$attribute], $errorMessage);
            foreach (array_slice($ruleForAttribute, 1) as $key => $value) {
                $messageKey = '{' . $key . '}';
                if (strpos($errorMessage, $messageKey) !== false) {
                    $errorMessage = str_replace($messageKey, $this->labels()[$value], $errorMessage);
                }
            }
        }
        $this->errors[$attribute][] = $errorMessage;
    }
    
    public function hasError($attribute)
    {
        if (isset($this->errors[$attribute])) {
            return true;
        }
        return false;
    }

    public function getFirstError($attribute)
    {
        if (isset($this->errors[$attribute])) {
            return $this->errors[$attribute][0];
        }
        return '';
    }

    abstract public function save();
}
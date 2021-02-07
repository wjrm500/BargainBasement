<?php

namespace app\core;

abstract class Model
{
    public const RULE_REQUIRED = 'requiredRule';
    public const RULE_MATCH = 'matchRule';
    public const RULE_UNIQUE = 'uniqueRule';
    public const RULE_EXISTS = 'existsRule';
    public const RULE_PASSWORD = 'passwordRule';
    public const RULE_IMAGE_MAX_SIZE = 'imageMaxSizeRule';
    public const RULE_IMAGE_SQUARE = 'imageSquareRule';
    public const RULE_IMAGE_HEIGHT = 'imageHeightRule';
    public const RULE_IMAGE_WIDTH = 'imageWidthRule';

    public const IMG_DIR = '/assets/images/';

    public array $errors = [];

    abstract public static function attributes(): array;
    
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
        $attributes = static::attributes();
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
                if (is_array($ruleForAttribute) && in_array(self::RULE_IMAGE_MAX_SIZE, $ruleForAttribute)) {
                    $maxSize = $ruleForAttribute['maxSize'];
                    $file = Application::$app->request->getFile($attribute);
                    if ($file['size'] > $maxSize) {
                        $this->addError($attribute, $ruleForAttribute);
                    }
                }
                if (is_array($ruleForAttribute) && in_array(self::RULE_IMAGE_SQUARE, $ruleForAttribute)) {
                    $file = Application::$app->request->getFile($attribute);
                    if (file_exists($file['tmp_name'])) {
                        list($uploadWidth, $uploadHeight) = getimagesize($file['tmp_name']);
                        if ($uploadHeight !== $uploadWidth) {
                            $this->addError($attribute, $ruleForAttribute);
                            break;
                        }
                        $specifiedHeight = $ruleForAttribute['height'];
                        if (isset($specifiedHeight) && $specifiedHeight !== $uploadHeight) {
                            switch ($file['type']) {
                                case 'image/jpeg':
                                    $image = imagecreatefromjpeg($file['tmp_name']);
                                case 'image/png':
                                    $image = imagecreatefrompng($file['tmp_name']);
                            }
                            $resizedImage = imagescale($image, $specifiedHeight, $specifiedHeight);
                        }
                    }
                }
                if (is_array($ruleForAttribute) && in_array(self::RULE_IMAGE_HEIGHT, $ruleForAttribute)) {
                    $specifiedHeight = $ruleForAttribute['height'];
                    $file = Application::$app->request->getFile($attribute);
                    if (file_exists($file['tmp_name'])) {
                        $uploadHeight = getimagesize($file['tmp_name'])[1];
                        if ($specifiedHeight !== $uploadHeight) {
                            $this->addError($attribute, $ruleForAttribute);
                        }
                    }
                }
                if (is_array($ruleForAttribute) && in_array(self::RULE_IMAGE_WIDTH, $ruleForAttribute)) {
                    $specifiedWidth = $ruleForAttribute['width'];
                    $file = Application::$app->request->getFile($attribute);
                    if (file_exists($file['tmp_name'])) {
                        $uploadWidth = getimagesize($file['tmp_name'])[0];
                        if ($specifiedWidth !== $uploadWidth) {
                            $this->addError($attribute, $ruleForAttribute);
                        }
                    }
                }
            }
            if (isset($file) && !$this->hasError($attribute)) {
                $targetLocation = Application::$root . static::IMG_DIR . $file['name'];
                if (isset($resizedImage)) { // If image has been resized, we need to move the resized image resource into the target location
                    switch ($file['type']) {
                        case 'image/jpeg':
                            $image = imagejpeg($resizedImage, $targetLocation);
                        case 'image/png':
                            $image = imagepng($resizedImage, $targetLocation);
                    }
                } else { // If the image has not been resized, we can just specify the location of the uploaded image in move_uploaded_file
                    $uploadLocation = $file['tmp_name'];
                    move_uploaded_file($uploadLocation, $targetLocation);
                }
            }
            unset($file); // $file needs to be unset at this point so that the code block above does not cause an invalid file to be uploaded after validation of a subsequent attribute
        }
        return empty($this->errors);
    }

    public function errorMessages()
    {
        return [
            self::RULE_REQUIRED       => '{attribute} is required',
            self::RULE_MATCH          => '{attribute} must match {attributeToMatch}',
            self::RULE_UNIQUE         => '{attribute} must be unique',
            self::RULE_EXISTS         => '{attribute} does not exist',
            self::RULE_PASSWORD       => '{attribute} incorrect',
            self::RULE_IMAGE_MAX_SIZE => '{attribute} must be no larger than {maxSize}',
            self::RULE_IMAGE_SQUARE   => '{attribute} must be square',
            self::RULE_IMAGE_HEIGHT   => '{attribute} must have a height of {height}',
            self::RULE_IMAGE_WIDTH    => '{attribute} must have a width of {width}'
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
                    $errorMessage = str_replace($messageKey, $this->labels()[$value] ?? $value, $errorMessage);
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

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    abstract public function attributeCustomInputTypes(): array;

    public function hasCustomInputType($attribute)
    {
        return isset($this->attributeCustomInputTypes()[$attribute]);
    }
}
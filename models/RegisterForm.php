<?php

namespace app\models;

class RegisterForm extends User
{
    public string $confirmPassword = '';

    public function attributes(): array
    {
        return array_merge(
            parent::attributes(),
            ['confirmPassword']
        );
    }

    public function labels(): array
    {
        return [
            'username'        => 'Username',
            'password'        => 'Password',
            'confirmPassword' => 'Confirm password'
        ];
    }

    public function rules(): array
    {
        return [
                'username'        => [
                    self::RULE_REQUIRED,
                    [
                        self::RULE_UNIQUE,
                        'class' => 'app\models\User'
                    ]
                ],
                'password'        => [self::RULE_REQUIRED],
                'confirmPassword' => [
                    self::RULE_REQUIRED,
                    [
                        self::RULE_MATCH,
                        'attributeToMatch' => 'password'
                    ]
                ]
        ];
    }

    public function save($returnLastInsertId = true)
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        unset($this->confirmPassword);
        return $this->id = parent::save($returnLastInsertId);
    }
}
<?php

namespace app\models;

use app\core\Application;

class LoginForm extends User
{
    public function labels(): array
    {
        return [
            'username'        => 'Username',
            'password'        => 'Password'
        ];
    }

    public function rules(): array
    {
        return [
            'username' => [
                self::RULE_REQUIRED,
                [
                    self::RULE_EXISTS,
                    'class' => 'app\models\User'
                ]
            ],
            'password' => [
                self::RULE_REQUIRED,
                [
                    self::RULE_PASSWORD,
                    'class'           => 'app\models\User',
                    'searchField'     => 'username',
                    'searchAttribute' => 'username'
                ]
            ]
        ];
    }

    public function login()
    {
        return Application::$app->login($this);
    }
}
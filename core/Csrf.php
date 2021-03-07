<?php

namespace app\core;

use EasyCSRF\NativeSessionProvider;
use EasyCSRF\EasyCSRF;

class Csrf
{
    private NativeSessionProvider $sessionProvider;
    public EasyCSRF $easyCsrf;

    public const TOKEN_NAME = 'csrf_token';

    public function __construct()
    {
        $this->sessionProvider = new NativeSessionProvider();
        $this->easyCsrf = new EasyCSRF($this->sessionProvider);
    }

    public function getToken()
    {
        return $this->easyCsrf->generate(self::TOKEN_NAME);
    }

    public function checkToken($token = null)
    {
        $this->easyCsrf->check(self::TOKEN_NAME, $token ?? $_POST[self::TOKEN_NAME]);
    }
}
<?php

namespace app\core\middlewares;

use Exception;

abstract class BaseMiddleware
{
    public Exception $exception;

    public function getException()
    {
        return $this->exception;
    }

    public function setException(Exception $exception)
    {
        $this->exception = $exception;
    }

    abstract public function execute();
}
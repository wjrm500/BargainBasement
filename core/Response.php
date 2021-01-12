<?php

namespace app\core;

class Response
{
    public function setStatusCode($statusCode)
    {
        http_response_code($statusCode);
    }

    public function redirect($location)
    {
        header("Location: {$location}");
        exit();
    }
}
<?php

namespace app\core;

class Response
{
    public function setStatusCode($statusCode)
    {
        http_response_code($statusCode);
    }

    public function redirect($location, $redirectUrl = null)
    {
        if ($redirectUrl) {
            $redirectUrl = urlencode($redirectUrl);
            header("Location: {$location}?redirect-url={$redirectUrl}");
        } else {
            header("Location: {$location}");
        }
        exit();
    }
}
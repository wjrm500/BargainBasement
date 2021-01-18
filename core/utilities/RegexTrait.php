<?php

namespace app\core\utilities;

trait RegexTrait
{
    public function convertPathToRegexPattern(string $route)
    {
        $routeRegexPattern = preg_replace('/{[^{}]*}/', '(.*)', $route);
        $routeRegexPattern = str_replace('/', '\/', $routeRegexPattern);
        return '/' .  $routeRegexPattern . '/';
    }
}
<?php

namespace app\core\exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    protected $code = '404';
    protected $message = 'No valid callback found for this path.';    
}
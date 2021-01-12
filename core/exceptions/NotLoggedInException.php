<?php

namespace app\core\exceptions;

use Exception;

class NotLoggedInException extends Exception
{
    protected $code = '403';
    protected $message = 'You must be logged in to view this page. <a href="/register">Sign up</a> here!';    
}
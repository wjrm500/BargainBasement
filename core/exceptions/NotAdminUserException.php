<?php

namespace app\core\exceptions;

use Exception;

class NotAdminUserException extends Exception
{
    protected $code = '403';
    protected $message = 'You must be an admin user to view this page.';    
}
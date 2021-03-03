<?php

namespace app\core\exceptions;

use Exception;

class LayoutTreeContentMissingException extends Exception
{
    protected $code = '403';
    protected $message = 'Number of views does not match number of LayoutTree placeholders';    
}
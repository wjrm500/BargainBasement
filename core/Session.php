<?php

namespace app\core;

use app\consts\BootstrapColorConsts;
use ReflectionClass;

class Session
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION[self::FLASH_KEY])) {
            $_SESSION[self::FLASH_KEY] = [];
        }
        array_walk(
            $_SESSION[self::FLASH_KEY],
            function(&$flashMessage) {
                $flashMessage['remove'] = true;
            }  
        );
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public function get($key)
    {
        return $_SESSION[$key];
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }
    
    public function isFlashy()
    {
        return boolval($_SESSION[self::FLASH_KEY]);
    }

    public function getFlashMessages()
    {
        return $_SESSION[self::FLASH_KEY];
    }

    public function setFlashMessage($message, $bootstrapColor = null)
    {
        $ref = new ReflectionClass(BootstrapColorConsts::class);
        if (!in_array($bootstrapColor, array_values($ref->getConstants()))) {
            $bootstrapColor = BootstrapColorConsts::PRIMARY;
        }
        $_SESSION[self::FLASH_KEY][] = [
            'bootstrapColor' => $bootstrapColor,
            'message'        => $message,
            'remove'         => false
        ];
    }

    public function __destruct()
    {
        $_SESSION[self::FLASH_KEY] = array_filter(
            $_SESSION[self::FLASH_KEY],
            function($flashMessage) {
                return !$flashMessage['remove'];
            }
        );
    }
}
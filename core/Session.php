<?php

namespace app\core;

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

    public function setFlashMessage($message, $bootstrapColor = 'primary')
    {
        if (!in_array($bootstrapColor, $this->getBootstrapColors())) {
            $bootstrapColor = 'primary';
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

    private function getBootstrapColors()
    {
        return [
            'primary', # blue
            'secondary', # grey
            'success', # green
            'danger', # red
            'warning', # yellow
            'info', # teal
            'light',
            'dark',
            'muted',
            'white'
        ];
    }
}
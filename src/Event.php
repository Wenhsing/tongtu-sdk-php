<?php

namespace Wenhsing\Tongtu;

use Symfony\Component\EventDispatcher\EventDispatcher;

class Event
{
    protected static $eventDispatcher;

    public function __call($method, $params = [])
    {
        call_user_func_array([self::getEventDispatcher(), $method], $params);
    }

    public static function __callStatic($method, $params = [])
    {
        forward_static_call_array([self::getEventDispatcher(), $method], $params);
    }

    public static function getEventDispatcher()
    {
        if (is_null(self::$eventDispatcher)) {
            self::setEventDispatcher(new EventDispatcher());
        }
        return self::$eventDispatcher;
    }

    public static function setEventDispatcher(EventDispatcher $eventDispatcher)
    {
        self::$eventDispatcher = $eventDispatcher;
    }
}

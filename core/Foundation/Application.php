<?php

namespace Core\Foundation;


class Application
{

    const VERSION = '1.0.2';

    const FRAMEWORK = 'kframe';

    public static function version()
    {
        return static::VERSION;
    }

    public static function framework()
    {
        return static::FRAMEWORK;
    }

}
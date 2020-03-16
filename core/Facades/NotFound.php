<?php

namespace Core\Facades;


class NotFound
{

    public static $home_url;

    public static function home_url($home_url) {
        static::$home_url = $home_url;
    }

    public static function get_home_url() {
        return static::$home_url;
    }

}
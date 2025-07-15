<?php

namespace Core\Support;

use Core\Dotenv\Dotenv;

class LoadEnv
{
    public function __construct($path)
    {
        $dotenv = new Dotenv($path);
        $dotenv->load();
    }
}

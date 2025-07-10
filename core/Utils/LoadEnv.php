<?php

/**
 * sent second @param, file name if load file other than .env
 */

use Core\Dotenv\Dotenv;

$dotenv = new Dotenv(root_path);
$dotenv->load();

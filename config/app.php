<?php

return [
    'app_env' => env('APP_ENV', 'testing'),
    'table' => env('AUTH_TABLE', 'users'),
    'log_channel' => env('LOG_CHANNEL') ?: 'single',
];
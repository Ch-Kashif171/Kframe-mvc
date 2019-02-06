<?php

return [

    'driver' => env('MAIL_DRIVER','smtp'),

    'host' => env('MAIL_HOST','smtp.gmail.com'),

    'port' => env('MAIL_PORT','587'),

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS','example@gmail.com'),
        'name' => env('MAIL_FROM_NAME','Kframe'),
    ],

    'encryption' => env('MAIL_ENCRYPTION','tls'),

    'username' => env('MAIL_USERNAME'),

    'password' => env('MAIL_PASSWORD'),

];

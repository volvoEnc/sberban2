<?php

return [
    'debug' => env('EXPONENT_PUSH_NOTIFICATION_DEBUG', true),

    'interests' => [
        'driver' => env('EXPONENT_PUSH_NOTIFICATION_INTERESTS_STORAGE_DRIVER', 'database'),

        'database' => [
            'events' => [],

            'table_name' => 'user_notification_token',
        ],
    ],
];
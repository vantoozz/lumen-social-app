<?php declare(strict_types = 1);

return [
    'defaults' => [
        'guard' => 'db'
    ],
    'guards' => [
        'db' => [
            'driver' => 'db',
            'provider' => 'users',
        ],
    ],
];

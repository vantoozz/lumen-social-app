<?php

return [
    'defaults' => [
        'guard' => 'db'
    ],
    'guards' => [
        'db' => [
            'driver' => 'db',
            'table' => 'users',
        ],
    ],
];

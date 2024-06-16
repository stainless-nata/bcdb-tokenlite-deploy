<?php

return [
    'driver' => env('BIGCHAINDB_DRIVER', 'http://localhost:2466/'),
    'headers' => [
        'Content-Type' => 'application/json',
    ],
];

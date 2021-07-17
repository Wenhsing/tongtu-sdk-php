<?php

return [
    'enable' => false,

    'app_key' => env('TONGTU_APP_KEY', ''),
    'app_secret' => env('TONGTU_APP_SECRET', ''),

    'log' => [
        'name' => 'tongtu',
        'outpath'  => storage_path('logs/'),
    ],
];

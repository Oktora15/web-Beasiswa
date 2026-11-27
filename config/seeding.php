<?php

return [
    'admin' => [
        'email' => env('SEED_ADMIN_EMAIL'),
        'password' => env('SEED_ADMIN_PASSWORD'),
    ],
    'students' => [
        1 => [
            'email' => env('SEED_STUDENT_1_EMAIL'),
            'password' => env('SEED_STUDENT_1_PASSWORD'),
        ],
        2 => [
            'email' => env('SEED_STUDENT_2_EMAIL'),
            'password' => env('SEED_STUDENT_2_PASSWORD'),
        ],
        3 => [
            'email' => env('SEED_STUDENT_3_EMAIL'),
            'password' => env('SEED_STUDENT_3_PASSWORD'),
        ],
    ],
];

<?Php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],


    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
        'api_parent' => [
            'driver' => 'jwt',
            'provider' => 'my_parents',
        ],
        'api_students' => [
            'driver' => 'jwt',
            'provider' => 'students',
        ],
        'api_teachers' => [
            'driver' => 'jwt',
            'provider' => 'teachers',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'api_parent' => [
            'driver' => 'eloquent',
            'model' => App\Models\My_Parent::class,
        ]


    ],
];














?>
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Php;

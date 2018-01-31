<?php

return [
    // This is relative to your web container, not your host machine
    // host machine `mysql -u root -p1234 example_data --port=3307 -h 127.0.0.1`
    'example_data' => [
        'user' => 'root',
        'pass' => '1234',
        'name' => 'example_data',
        'host' => 'mysql',
        'port' => '3306'
    ],
    'bc_no_db' => [
        'user' => 'root',
        'pass' => '1234',
        'name' => '',
        'host' => 'mysql',
        'port' => '3306'
    ],
    'your_project_no_db' => [
        'user' => !empty(getenv('DL_DB_NAME')) ? getenv('DL_DB_USER') : 'root',
        'pass' => !empty(getenv('DL_DB_PASS')) ? getenv('DL_DB_PASS') : '1234',
        'name' => !empty(getenv('DL_DB_NAME')) ? getenv('DL_DB_NAME') : '',
        'host' => !empty(getenv('DL_DB_HOST')) ? getenv('DL_DB_HOST') : 'mysql',
        'port' => !empty(getenv('DL_DB_PORT')) ? getenv('DL_DB_PORT') : '3306',
    ],
    'your_project' => [
        'user' => !empty(getenv('DL_DB_NAME')) ? getenv('DL_DB_USER') : 'root',
        'pass' => !empty(getenv('DL_DB_PASS')) ? getenv('DL_DB_PASS') : '1234',
        'name' => !empty(getenv('DL_DB_NAME')) ? getenv('DL_DB_NAME') : 'your_project',
        'host' => !empty(getenv('DL_DB_HOST')) ? getenv('DL_DB_HOST') : 'mysql',
        'port' => !empty(getenv('DL_DB_PORT')) ? getenv('DL_DB_PORT') : '3306',
    ]
];

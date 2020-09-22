<?php
return [
    'route'=> [
        'prefix'=> 'admin', //路由前缀
        'middleware' => [
            'handle_cors'
        ],
        'middleware_auth' => [
            'handle_cors',
            'laravel-vue-admin.auth'
        ]
    ],
    'snowflake'=>[
        'start_time_stamp'=>'2020-10-01'
    ]
];

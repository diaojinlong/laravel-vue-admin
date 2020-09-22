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
    ]
];

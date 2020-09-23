<?php
return [
    'route' => [
        'prefix' => 'admin', //路由前缀
        'middleware' => [
            'handle_cors'
        ],
        'middleware_auth' => [
            'handle_cors',
            'laravel-vue-admin.auth'
        ]
    ],
    'snowflake' => [
        'start_time_stamp' => '2020-09-01'
    ],
    'pages' => [
        'limit' => 10, //默认分页返回条数
        'limit_max' => 100 //分页最大返回条数
    ],
    'cache' => [
        'admin_info_by_admin_id' => [ //管理员信息key:adminId
            'key' => 'admin_info_by_admin_id:',
            'timeout' => 7200
        ],
        'admin_role_ids_by_admin_id' => [ //管理员角色key:adminId
            'key' => 'admin_role_ids_by_admin_id:',
            'timeout' => 7200
        ],
        'admin_role_ids_by_menu_id' => [ //菜单角色key:menuId
            'key' => 'admin_role_ids_by_menu_id:',
            'timeout' => 7200
        ],
        'admin_menu_ids_by_role_id' => [ //菜单信息key:roleId
            'key' => 'admin_menu_ids_by_role_id:',
            'timeout' => 7200
        ],
        'admin_permission_by_role_ids' => [ //多角色权限
            'key' => 'admin_permission_by_role_ids:',
            'timeout' => 7200
        ]
    ],
    'databases' => [
        'admin_token' => [
            'key' => 'admin_token:',
            'timeout' => 3600 * 24 * 7
        ],
        'admin_token_list' => [
            'key' => 'admin_token_list:'
        ]
    ]
];

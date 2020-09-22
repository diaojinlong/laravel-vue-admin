<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = DB::getConfig('prefix');

        Schema::create('menu', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->comment('菜单编号');
            $table->string('api', 100)->nullable(false)->default('')
                ->comment('API接口');
            $table->string('component', 100)->nullable(false)->default('')
                ->comment('VUE组件');
            $table->string('redirect', 100)->nullable(false)->default('')
                ->comment('重定向路由');
            $table->tinyInteger('always_show')->nullable(false)->default(1)
                ->comment('显示根路由:1=是,2=否')->unsigned();
            $table->string('path', 100)->nullable(false)->default('')
                ->comment('路由路径');
            $table->string('name', 50)->nullable(false)->default('')
                ->comment('路由别名');
            $table->string('title', 50)->nullable(false)->default('')
                ->comment('菜单名称');
            $table->string('icon', 50)->nullable(false)->default('')
                ->comment('icon图标');
            $table->tinyInteger('is_menu')->nullable(false)->default(1)
                ->comment('是否菜单:1=是,2=否')->unsigned();
            $table->tinyInteger('hidden')->nullable(false)->default(1)
                ->comment('菜单显示:1=是,2=否')->unsigned();
            $table->bigInteger('pid')->nullable(false)->default(0)
                ->comment('上级菜单编号')->unsigned();
            $table->integer('sort')->nullable(false)->default(0)
                ->comment('排序')->unsigned();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `" . $prefix . "menu` comment '菜单表'");

        Schema::create('role', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->comment('角色编号');
            $table->string('name', 50)->nullable(false)->default('')
                ->comment('角色名称');
            $table->string('info', 100)->nullable(false)->default('')
                ->comment('备注信息');
            $table->tinyInteger('status')->nullable(false)->default(1)
                ->comment('状态:1=启用,2=禁用')->unsigned();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `" . $prefix . "role` comment '角色表'");

        Schema::create('admin', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->comment('管理员编号');
            $table->string('username', 50)->nullable(false)->default('')
                ->comment('用户名');
            $table->string('password', 50)->nullable(false)->default('')
                ->comment('密码');
            $table->string('real_name', 50)->nullable(false)->default('')
                ->comment('真实姓名');
            $table->char('tel', 11)->nullable(false)->default('')
                ->comment('联系电话');
            $table->string('avatar', 255)->nullable(false)->default('')
                ->comment('头像');
            $table->string('info', 255)->nullable(false)->default('')
                ->comment('描述');
            $table->tinyInteger('status')->nullable(false)->default(1)
                ->comment('状态:1=启用,2=禁用')->unsigned();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `" . $prefix . "admin` comment '管理员表'");

        Schema::create('admin_role', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->comment('编号');
            $table->bigInteger('admin_id')->nullable(false)->default(0)
                ->comment('管理员编号')->unsigned();
            $table->bigInteger('role_id')->nullable(false)->default(0)
                ->comment('角色编号')->unsigned();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `" . $prefix . "admin_role` comment '管理员角色关联表'");

        Schema::create('role_menu', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->comment('编号');
            $table->bigInteger('role_id')->nullable(false)->default(0)
                ->comment('角色编号')->unsigned();
            $table->bigInteger('menu_id')->nullable(false)->default(0)
                ->comment('菜单编号')->unsigned();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `" . $prefix . "role_menu` comment '角色菜单关联表'");

        $this->seeder();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
        Schema::dropIfExists('role');
        Schema::dropIfExists('admin');
        Schema::dropIfExists('admin_role');
        Schema::dropIfExists('role_menu');
    }

    public function seeder()
    {
        $date = date('Y-m-d H:i:s');
        $menus = array(
            [
                'api' => '',
                'component' => '',
                'redirect' => '',
                'always_show' => 1,
                'path' => '',
                'name' => '',
                'title' => '控制台',
                'icon' => 'lock',
                'is_menu' => 2,
                'hidden' => 2,
                'sort' => 0,
                'created_at' => $date,
                'updated_at' => $date,
                'children' => [
                    [
                        'api' => 'admin/count/number',
                        'component' => '',
                        'redirect' => '',
                        'always_show' => 1,
                        'path' => '',
                        'name' => '',
                        'title' => '显示统计数量',
                        'icon' => 'lock',
                        'is_menu' => 2,
                        'hidden' => 2,
                        'sort' => 0,
                        'created_at' => $date,
                        'updated_at' => $date
                    ],
                    [
                        'api' => 'admin/count/curve',
                        'component' => '',
                        'redirect' => '',
                        'always_show' => 1,
                        'path' => '',
                        'name' => '',
                        'title' => '显示曲线图',
                        'icon' => 'lock',
                        'is_menu' => 2,
                        'hidden' => 2,
                        'sort' => 0,
                        'created_at' => $date,
                        'updated_at' => $date
                    ],
                    [
                        'api' => 'admin/count/radar',
                        'component' => '',
                        'redirect' => '',
                        'always_show' => 1,
                        'path' => '',
                        'name' => '',
                        'title' => '显示雷达图',
                        'icon' => 'lock',
                        'is_menu' => 2,
                        'hidden' => 2,
                        'sort' => 0,
                        'created_at' => $date,
                        'updated_at' => $date
                    ],
                    [
                        'api' => 'admin/count/round',
                        'component' => '',
                        'redirect' => '',
                        'always_show' => 1,
                        'path' => '',
                        'name' => '',
                        'title' => '显示饼状图',
                        'icon' => 'lock',
                        'is_menu' => 2,
                        'hidden' => 2,
                        'sort' => 0,
                        'created_at' => $date,
                        'updated_at' => $date
                    ],
                    [
                        'api' => 'admin/count/column',
                        'component' => '',
                        'redirect' => '',
                        'always_show' => 1,
                        'path' => '',
                        'name' => '',
                        'title' => '显示柱状图',
                        'icon' => 'lock',
                        'is_menu' => 2,
                        'hidden' => 2,
                        'sort' => 0,
                        'created_at' => $date,
                        'updated_at' => $date
                    ]
                ]
            ],
            [
                'api' => '',
                'component' => 'Layout',
                'redirect' => '',
                'always_show' => 1,
                'path' => '/permission',
                'name' => 'Permission',
                'title' => '权限管理',
                'icon' => 'lock',
                'is_menu' => 1,
                'hidden' => 2,
                'sort' => 0,
                'created_at' => $date,
                'updated_at' => $date,
                'children' => [
                    [
                        'api' => '',
                        'component' => 'role/list',
                        'redirect' => '',
                        'always_show' => 2,
                        'path' => 'role',
                        'name' => 'RolePermission',
                        'title' => '角色管理',
                        'icon' => '',
                        'is_menu' => 1,
                        'hidden' => 2,
                        'sort' => 0,
                        'created_at' => $date,
                        'updated_at' => $date,
                        'children' => [
                            [
                                'api' => 'admin/role/list',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '查看角色',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ],
                            [
                                'api' => 'admin/role/add',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '新建角色',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ],
                            [
                                'api' => 'admin/role/edit',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '编辑角色',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ],
                            [
                                'api' => 'admin/role/del',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '删除角色',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ]
                        ]
                    ],
                    [
                        'api' => '',
                        'component' => 'admin/list',
                        'redirect' => '',
                        'always_show' => 2,
                        'path' => 'admin',
                        'name' => 'Admin',
                        'title' => '管理员管理',
                        'icon' => '',
                        'is_menu' => 1,
                        'hidden' => false,
                        'sort' => 0,
                        'created_at' => $date,
                        'updated_at' => $date,
                        'children' => [
                            [
                                'api' => 'admin/list',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '查看管理员',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ],
                            [
                                'api' => 'admin/add',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '新建管理员',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ],
                            [
                                'api' => 'admin/edit',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '编辑管理员',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ],
                            [
                                'api' => 'admin/del',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '删除管理员',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ],
                            [
                                'api' => 'admin/up_status',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '更改状态',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ]
                        ]
                    ],
                    [
                        'api' => '',
                        'component' => 'menu/list',
                        'redirect' => '',
                        'always_show' => 2,
                        'path' => 'menu',
                        'name' => 'Menu',
                        'title' => '菜单管理',
                        'icon' => '',
                        'is_menu' => 1,
                        'hidden' => 2,
                        'sort' => 0,
                        'created_at' => $date,
                        'updated_at' => $date,
                        'children' => [
                            [
                                'api' => 'admin/menu/list',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '查看菜单',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ],
                            [
                                'api' => 'admin/menu/add',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '新增菜单',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ],
                            [
                                'api' => 'admin/menu/edit',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '编辑菜单',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ],
                            [
                                'api' => 'admin/menu/del',
                                'component' => '',
                                'redirect' => '',
                                'always_show' => 2,
                                'path' => '',
                                'name' => '',
                                'title' => '删除菜单',
                                'icon' => '',
                                'is_menu' => 2,
                                'hidden' => 1,
                                'sort' => 0,
                                'created_at' => $date,
                                'updated_at' => $date
                            ]
                        ]
                    ]
                ]
            ],
            [
                'api' => '',
                'component' => 'Layout',
                'redirect' => '',
                'always_show' => 1,
                'path' => '/setting',
                'name' => '',
                'title' => '系统设置',
                'icon' => 'setting',
                'is_menu' => 1,
                'hidden' => 2,
                'sort' => 0,
                'created_at' => $date,
                'updated_at' => $date,
                'children' => [
                    [
                        'api' => '',
                        'component' => 'setting/index',
                        'redirect' => '',
                        'always_show' => 2,
                        'path' => 'index',
                        'name' => '',
                        'title' => '网站设置',
                        'icon' => '',
                        'is_menu' => 1,
                        'hidden' => 2,
                        'sort' => 0,
                        'created_at' => $date,
                        'updated_at' => $date
                    ],
                    [
                        'api' => '',
                        'component' => 'setting/cache',
                        'redirect' => '',
                        'always_show' => 2,
                        'path' => 'role',
                        'name' => '',
                        'title' => '缓存管理',
                        'icon' => '',
                        'is_menu' => 1,
                        'hidden' => 2,
                        'sort' => 0,
                        'created_at' => $date,
                        'updated_at' => $date
                    ]
                ]
            ]
        );
        $data = [];
        $this->treeToMenuSeeder($menus, $data);
        DB::table('menu')->insert($data);


    }

    public function treeToMenuSeeder($menus, &$data, $pid=0)
    {
        foreach ($menus as $menu) {
            $row = $menu;
            $row['id'] = resolve('snowflake')->id();
            $row['pid'] = $pid;
            unset($row['children']);
            $data[] = $row;
            if(isset($menu['children'])){
                $this->treeToMenuSeeder($menu['children'], $data, $row['id']);
            }
        }
    }
}

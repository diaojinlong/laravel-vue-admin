# laravel-vue-admin -- 开发中
基于PHP Laravel框架及前端vue-element-admin框架搭建的后台管理系统，含有基本的RBAC角色权限管理功能，方便开发人员快速搭建后台系统。

laravel网站:
https://learnku.com/docs/laravel/7.x

vue-element-admin网站：
https://panjiachen.github.io/vue-element-admin-site/zh/


# 安装并运行

1、安装laravel框架(建议laravel5.8)

    composer create-project --prefer-dist laravel/laravel project-name

2、安装laravel-vue-admin扩展包

    composer require diaojinlong/laravel-vue-admin

3、修改config/app.php配置文件

    在providers下新增
    DiaoJinLong\LaravelVueAdmin\LaravelVueAdminServiceProvider::class

    在aliases下新增
    'LaravelVueAdmin' => DiaoJinLong\LaravelVueAdmin\Facades\LaravelVueAdmin::class


4、新增laravel-vue-admin配置文件

    php artisan vendor:publish --provider="DiaoJinLong\LaravelVueAdmin\LaravelVueAdminServiceProvider"  --force


5、运行数据填充

    php artisan migrate



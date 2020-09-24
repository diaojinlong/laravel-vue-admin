# laravel-vue-admin -- 开发中
基于PHP Laravel框架及前端vue-element-admin框架搭建的后台管理系统，含有基本的RBAC角色权限管理功能，方便开发人员快速搭建后台系统。

laravel网站:
https://learnku.com/docs/laravel/7.x

vue-element-admin网站：
https://panjiachen.github.io/vue-element-admin-site/zh/


# 安装

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
    
# 注意事项

1、安装完成laravel请配置redis,登录的用户token需要存储到redis,建议redis驱动使用phpredis不要使用默认的predis。
    
    .env中新增
    REDIS_CLIENT=phpredis
    
2、为了提升运行速度建议将缓存也更改为redis
    
    .env中修改
    CACHE_DRIVER=redis
    
# 运行

使用postman请求下方接口，如返回json则表明PHP后端接口已运行成功。

    请求地址：http://127.0.0.1/admin/auth/login
    
    请求方式：POST
        
    请求参数：
    {
        "username": "admin",
        "password": "123456"
    }
    
    
    返回JSON数据：
    {
        "code": 200,
        "massage": "success",
        "data": {
            "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOm51bGwsImF1ZCI6bnVsbCwiaWF0IjoxNjAwODU2MzA5LCJkYXRhIjp7ImlkIjo4MDIyODYzNDU4MDc0NjI0LCJ1c2VybmFtZSI6ImFkbWluIn19.H060sDkNsBJ6iFld7D9EOo5J2D7N2pUzjfEAZAw5ffU"
        }
    }



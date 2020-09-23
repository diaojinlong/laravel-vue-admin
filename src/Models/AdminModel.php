<?php

namespace DiaoJinLong\LaravelVueAdmin\Models;

use Illuminate\Support\Facades\Cache;

class AdminModel extends BaseModel
{

    protected $table = 'admin';

    /**
     * 校验密码
     * @param $password
     * @param $admin
     * @return bool
     */
    public function passwordVerify($password, $admin)
    {
        if ($admin->password === md5(md5($password) . md5($admin->created_at))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取用户信息
     * @param $id
     * @param $clear
     * @return mixed
     */
    public function rowCache($id, $clear = false)
    {
        $key = config('laravel_vue_admin.cache.admin_info_by_admin_id.key');
        $timeout = config('laravel_vue_admin.cache.admin_info_by_admin_id.timeout');
        $admin = Cache::get($key . $id);
        if (!$admin || $clear) {
            $admin = $this->find($id);
            Cache::put($key . $id, $admin, $timeout);
        }
        return $admin;
    }

    /**
     * 清理缓存数据
     * @param $id
     */
    public function rowClearCache($id)
    {
        Cache::forget(config('laravel_vue_admin.cache.admin_info_by_admin_id.key') . $id);
    }
}

<?php

namespace DiaoJinLong\LaravelVueAdmin\Models;

use Illuminate\Support\Facades\Cache;

class AdminRoleModel extends BaseModel
{

    protected $table = 'admin_role';

    /**
     * 获取管理员的角色
     * @param $adminId
     */
    public function getAdminRoleIds($adminId, $clear = false)
    {
        $key = config('laravel-vue-admin.cache.admin_role_ids_by_admin_id.key');
        $roleIds = Cache::get($key . $adminId);
        if (empty($roleIds) || $clear) {
            $roleIds = $this->join('role', 'admin_role.role_id', '=', 'role.id')
                ->where('role.status', 1)
                ->where('admin_role.admin_id', $adminId)->pluck('admin_role.role_id');
            $roleIds = $roleIds ? $roleIds->toArray() : [];
            Cache::put($key . $adminId, $roleIds, config('laravel-vue-admin.cache.admin_role_ids_by_admin_id.timeout'));
        }
        return $roleIds;
    }

    /**
     * 更新管理员角色
     * @param $adminId
     * @param $roleIds
     */
    public function updateRole($adminId, $roleIds)
    {
        $this->whereAdminId($adminId)->delete();
        foreach ($roleIds as $roleId) {
            $model = new self();
            $model->admin_id = $adminId;
            $model->role_id = $roleId;
            $model->save();
        }
        $this->clearCache($adminId);
        return true;
    }

    /**
     * 清理缓存
     * @param $adminId
     */
    public function clearCache($adminId)
    {
        $key = config('laravel-vue-admin.cache.admin_role_ids_by_admin_id.key');
        Cache::forget($key . $adminId);
    }
}

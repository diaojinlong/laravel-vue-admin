<?php

namespace DiaoJinLong\LaravelVueAdmin\Models;

use Illuminate\Support\Facades\Cache;

class RoleMenuModel extends BaseModel
{

    protected $table = 'role_menu';

    /**
     * 通过角色编号获取所有菜单编号
     * @param $roleId
     * @param bool $clear
     * @return mixed
     */
    public function getMenuIdsByRoleId($roleId, $clear = false)
    {
        $key = config('laravel-vue-admin.cache.admin_menu_ids_by_role_id.key');
        $menuIds = Cache::get($key . $roleId);
        if (empty($menuIds) || $clear) {
            $menuIds = $this->join('menu', 'role_menu.menu_id', '=', 'menu.id')
                ->where('role_menu.role_id', $roleId)
                ->pluck('role_menu.menu_id');
            $menuIds = $menuIds ? $menuIds->toArray() : [];
            Cache::put(
                $key . $roleId,
                $menuIds,
                config('laravel-vue-admin.cache.admin_menu_ids_by_role_id.timeout')
            );
        }
        return $menuIds;
    }

    /**
     * 通过菜单编号获取所有角色
     * @param $menuId
     * @param bool $clear
     * @return mixed
     */
    public function getRoleIdsByMenuId($menuId, $clear = false)
    {
        $key = config('laravel-vue-admin.cache.admin_role_ids_by_menu_id.key');
        $roleIds = Cache::get($key . $menuId);
        if (empty($roleIds) || $clear) {
            $roleIds = $this->join('role', 'role_menu.role_id', '=', 'role.id')
                ->where('role.status', 1)
                ->where('role_menu.menu_id', $menuId)
                ->pluck('role_menu.role_id');
            $roleIds = $roleIds ? $roleIds->toArray() : [];
            Cache::put(
                $key . $menuId,
                $roleIds,
                config('laravel-vue-admin.cache.admin_role_ids_by_menu_id.timeout')
            );
        }
        return $roleIds;
    }

    /**
     * 更新角色权限
     * @param $roleId
     * @param $menuIds
     */
    public function updateMenu($roleId, $menuIds)
    {
        $this->whereRoleId($roleId)->delete();
        foreach ($menuIds as $menuId) {
            $model = new self();
            $model->role_id = $roleId;
            $model->menu_id = $menuId;
            $model->save();
            $this->getRoleIdsByMenuId($menuId, true);
        }
        $this->clearCache($roleId);
        return true;
    }

    /**
     * 获取指定角色的权限
     * @param $roles
     */
    public function getPermission($roleIds)
    {
        $permissions = [];
        if ($roleIds) {
            foreach($roleIds as $roleId) {
                $key = config('laravel-vue-admin.cache.admin_permission_by_role_id.key') . $roleId;
                $permission = Cache::get($key);
                if ($permission === null) {
                    $permission = $this->join('menu', 'role_menu.menu_id', '=', 'menu.id')
                        ->where('menu.api', '<>', '')
                        ->where('role_menu.role_id', $roleId)
                        ->pluck('menu.api');
                    $permission = $permission ? $permission->toArray() : [];
                    Cache::put($key, $permission);
                }
                if ($permission) {
                    array_merge($permissions, $permission);
                }
            }
            return array_unique($permissions);
        } else {
            return $permissions;
        }
    }


    /**
     * 清理缓存
     * @param $roleId
     */
    public function clearCache($roleId)
    {
        Cache::forget(config('laravel-vue-admin.cache.admin_menu_ids_by_role_id.key') . $roleId);
        Cache::forget(config('laravel-vue-admin.cache.admin_permission_by_role_id.key') . $roleId);
    }

    /**
     * 清理所有缓存
     * @throws \Exception
     */
    public function clearCacheAll()
    {

    }
}

<?php

namespace DiaoJinLong\LaravelVueAdmin\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
use DiaoJinLong\LaravelVueAdmin\Models\AdminModel;
use DiaoJinLong\LaravelVueAdmin\Models\AdminRoleModel;

class Auth
{

    //无需验证权限的方法
    public $notPermissionAction = [
        'auth/logout',
        'auth/info',
        'auth/up_pwd',
        'count/index',
        'upload/image',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uri = str_replace(config('laravel-vue-admin.route.prefix') . '/', '', $request->path());

        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        if (empty($token)) {
            $token = $request->input('token');
        }
        if (empty($token)) {
            return error(401, '请登录');
        }
        $adminId = (int)Redis::get(config('laravel-vue-admin.databaes.admin_token.key') . $token);
        if (empty($adminId)) {
            return error(401, '请登录');
        }
        $adminInfo = (new AdminModel())->rowCache($adminId);
        if (empty($adminInfo)) {
            return error(402, '账号不存在');
        }
        if ($adminInfo->status == 2) {
            return error(403, '账号已停用');
        }
        Redis::expire(config('laravel-vue-admin.databaes.admin_token.key') . $token, config('laravel-vue-admin.databaes.admin_token.timeout'));
        $roleIds = (new AdminRoleModel())->getAdminRoleIds($adminId);

        if (!in_array($uri, $this->notPermissionAction)) { //不是免权限验证方法
            $permission = (new RoleMenuModel())->getPermission($roleIds);
            if (!in_array($uri, $permission)) {
                return error(404, '您无权操作，请联系管理员');
            }
        }
        $request->attributes->add(
            [
                'token' => $token,
                'admin_id' => $adminId,
                'admin_info' => $adminInfo,
                'role_ids' => $roleIds
            ]
        );
        return $next($request);
    }
}

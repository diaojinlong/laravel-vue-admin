<?php
namespace DiaoJinLong\LaravelVueAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Firebase\JWT\JWT;
use DiaoJinLong\LaravelVueAdmin\Models\AdminModel;
use DiaoJinLong\LaravelVueAdmin\Models\MenuModel;
use DiaoJinLong\LaravelVueAdmin\Models\AdminRoleModel;
use DiaoJinLong\LaravelVueAdmin\Models\RoleMenuModel;

class AuthController extends BaseController {

    /**
     * showdoc
     * @catalog 管理后台/登录相关
     * @title 登录
     * @description
     * @method post
     * @url https://www.xxx.com/admin/auth/login
     * @header Accept 必选 string 固定值:application/json
     * @header Content-Type 必选 string 固定值:application/json
     * @json_param {"username":"admin","password":"123456"}
     * @param username 必选 string 用户名
     * @param password 必选 string 密码
     * @return {"code":200,"message":"success","data":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIiLCJhdWQiOiIiLCJpYXQiOjE1ODM0ODIwODUsImRhdGEiOnsiaWQiOjEsInVzZXJuYW1lIjoiYWRtaW4ifX0.rafL2V5mueHiPghSHLqPKBwufHeyFXu7KwZHE8B7Mi4"}}
     * @return_param code int 20000成功
     * @return_param message string 错误信息
     * @return_param data object 接口相关数据
     * @return_param data->token string 登录Token令牌
     * @remark 无
     * @number 1
     */
    public function login(Request $request){
        $username = $request->input('username');
        $password = $request->input('password');
        $admin = (new AdminModel())->whereUsername($username)->first();
        if (empty($admin)) {
            return error('账号不存在');
        }
        $verify = (new AdminModel())->passwordVerify($password, $admin);
        if ($verify === false) {
            return error('密码不正确');
        }
        if ($admin->status != 1) {
            return error('账号已禁用');
        }
        $data = [
            'iss' => config('jwt.iss'),
            'aud' => config('jwt.aud'),
            'iat' => time(),
            'data' => [
                'id' => $admin->id,
                'username' => $admin->username
            ]
        ];
        $token = JWT::encode($data, config('jwt.key'));
        Redis::set(
            config('laravel-vue-admin.databaes.admin_token.key') . $token,
            $admin->id,
            config('laravel-vue-admin.databaes.admin_token.timeout')
        );
        Redis::lPush(config('laravel-vue-admin.databaes.admin_token_list.key') . $admin->id, $token);
        return success(['token' => $token]);
    }

    /**
     * showdoc
     * @catalog 管理后台/登录相关
     * @title 退出登录
     * @description
     * @method post
     * @url https://www.xxx.com/admin/auth/logout
     * @header Accept 必选 string 固定值:application/json
     * @header Content-Type 必选 string 固定值:application/json
     * @header Authorization 必选 string 登录Token令牌,格式：Bearer {Token}
     * @return {"code":200,"message":"退出成功","data":{}}
     * @return_param code int 200成功
     * @return_param message string 错误信息
     * @remark 无
     * @number 10
     */
    public function logout(Request $request)
    {
        $token = $request->get('token');
        $adminId = $request->get('admin_id');
        Redis::del(config('laravel-vue-admin.databaes.admin_token.key') . $token);
        (new AdminModel())->rowClearCache($adminId);
        return success('退出成功');
    }

    /**
     * showdoc
     * @catalog 管理后台/登录相关
     * @title 获取管理员信息
     * @description
     * @method get
     * @url https://www.xxx.com/admin/auth/info
     * @header Authorization 必选 string 登录Token令牌,格式：Bearer {Token}
     * @return {"code":200,"message":"ok","data":{"name":"超级管理员","avatar":"http://www.xxx.com/img/admin/header.gif","introduction":"我是公司CEO，拥有后台所有权限！","roles":[1],"menu":[{"component":"Layout","redirect":"","alwaysShow":"true","path":"/permission","name":"Permission","meta":{"title":"权限管理","icon":"lock","roles":[1]},"hidden":"false","children":[{"component":"role/list","redirect":"","alwaysShow":"false","path":"role","name":"RolePermission","meta":{"title":"角色管理","icon":"","roles":[1]},"hidden":"false"},{"component":"admin/list","redirect":"","alwaysShow":"false","path":"admin","name":"Admin","meta":{"title":"管理员管理","icon":"","roles":[1]},"hidden":"false"},{"component":"menu/list","redirect":"","alwaysShow":"false","path":"menu","name":"Menu","meta":{"title":"菜单管理","icon":"","roles":[1]},"hidden":"false"}]},{"component":"Layout","redirect":"","alwaysShow":"true","path":"/setting","name":"","meta":{"title":"系统设置","icon":"setting","roles":[1]},"hidden":"false","children":[{"component":"setting/index","redirect":"","alwaysShow":"false","path":"index","name":"","meta":{"title":"网站设置","icon":"","roles":[1]},"hidden":"false"},{"component":"setting/cache","redirect":"","alwaysShow":"false","path":"role","name":"","meta":{"title":"缓存管理","icon":"","roles":[1]},"hidden":"false"}]}],"permission":["admin/count/radar","admin/list","admin/role/add","admin/menu/del","admin/up_status","admin/role/list","admin/del","admin/add","admin/menu/edit","admin/count/round","admin/role/edit","admin/role/del","admin/count/column","admin/menu/add","admin/count/curve","admin/count/number","admin/edit","admin/menu/list"]}}
     * @return_param code int 200成功
     * @return_param message string 错误信息
     * @return_param data object 接口相关数据
     * @return_param data->name string 管理员姓名
     * @return_param data->avatar string 头像
     * @return_param data->introduction string 描述
     * @return_param data->roles array 角色编号
     * @return_param data->menu array vue左侧菜单
     * @return_param data->permission array 权限数组
     * @remark 无
     * @number 5
     */
    public function info(Request $request)
    {
        $menu = (new MenuModel())->getMenu();
        $adminId = $request->get('admin_id');
        $admin = (new AdminModel())->rowCache($adminId);
        $roles = (new AdminRoleModel())->getAdminRoleIds($adminId);
        if (empty($admin) || empty($roles)) {
            return error('管理员不存在或角色不存在');
        }
        $permission = (new RoleMenuModel())->getPermission($roles);
        $data = array(
            'name' => $admin->real_name,
            'avatar' => img_url($admin->avatar),
            'introduction' => $admin->info,
            'roles' => $roles,
            'menu' => $menu,
            'permission' => array_values($permission)
        );
        return success($data);
    }
}

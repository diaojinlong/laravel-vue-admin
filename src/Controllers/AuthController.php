<?php
namespace DiaoJinLong\LaravelVueAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Firebase\JWT\JWT;
use DiaoJinLong\LaravelVueAdmin\Models\AdminModel;

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
            return $this->error('账号不存在');
        }
        $verify = (new AdminModel())->passwordVerify($password, $admin);
        if ($verify === false) {
            return $this->error('密码不正确');
        }
        if ($admin->status != 1) {
            return $this->error('账号已禁用');
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
        return $this->success(['token' => $token]);
    }
}

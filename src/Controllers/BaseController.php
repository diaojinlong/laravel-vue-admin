<?php

namespace DiaoJinLong\LaravelVueAdmin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseController extends Controller
{

    public function success($code = 200, $massage = 'success', $data = [])
    {
        if (is_string($code)) {
            $massage = $code;
            $code = 200;
        }
        if (is_array($code)) {
            $data = $code;
            $code = 200;
        }
        return $this->jsonResponse($code, $massage, $data);
    }

    public function error($code = 400, $massage = 'error', $data = [])
    {
        if (is_string($code)) {
            $massage = $code;
            $code = 400;
        }
        if (is_array($code)) {
            $data = $code;
            $code = 400;
        }
        return $this->jsonResponse($code, $massage, $data);
    }

    private function jsonResponse($code, $massage, $data)
    {
        $response = response()->json([
            'code' => $code,
            'massage' => $massage,
            'data' => $data === [] ? (object)[] : $data
        ]);
        return $response;
    }
}

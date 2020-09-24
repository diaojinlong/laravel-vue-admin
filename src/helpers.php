<?php

if (!function_exists('success')) {

    /**
     * 返回成功json响应
     * @param int $code
     * @param string $massage
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    function success($code = 200, $massage = 'success', $data = [])
    {
        if (is_string($code)) {
            $massage = $code;
            $code = 200;
        }
        if (is_array($code)) {
            $data = $code;
            $code = 200;
        }
        return json_response($code, $massage, $data);
    }
}

if (!function_exists('error')) {

    /**
     * 返回失败json响应
     * @param int $code
     * @param string $massage
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    function error($code = 400, $massage = 'error', $data = [])
    {
        if (is_string($code)) {
            $massage = $code;
            $code = 400;
        }
        if (is_array($code)) {
            $data = $code;
            $code = 400;
        }
        return json_response($code, $massage, $data);
    }
}

if (!function_exists('json_response')) {

    /**
     * 组合json响应数据
     * @param $code
     * @param $massage
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    function json_response($code, $massage, $data)
    {
        $response = response()->json([
            'code' => $code,
            'massage' => $massage,
            'data' => $data === [] ? (object)[] : $data
        ]);
        return $response;
    }
}

if (!function_exists('img_host')) {
    /**
     * 获取图片host
     * @param $url
     * @return string
     */
    function img_host()
    {
        $host = config('app.asset_url');
        $upload = config('image.upload');
        switch ($upload) {
            case 'oss':
                $host = config('oss.host');
                break;
        }
        return $host;
    }
}


if (!function_exists('img_url')) {
    /**
     * 获取图片完整的url
     * @param $url
     * @return string
     */
    function img_url($url)
    {
        if ($url) {
            return img_host() . $url;
        } else {
            return '';
        }
    }
}

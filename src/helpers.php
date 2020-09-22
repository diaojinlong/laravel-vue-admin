<?php

use Godruoyi\Snowflake\Snowflake;

if (!function_exists('create_id')) {

    /**
     * 获取主键ID
     * @return string
     */
    function create_id()
    {
        return resolve('snowflake')->id();
    }
}

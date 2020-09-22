<?php
namespace DiaoJinLong\LaravelVueAdmin\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelVueAdmin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-vue-admin';
    }
}

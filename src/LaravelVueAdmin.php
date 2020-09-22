<?php
namespace DiaoJinLong\LaravelVueAdmin;

class LaravelVueAdmin {
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function test($str){
        echo $str;
    }
}

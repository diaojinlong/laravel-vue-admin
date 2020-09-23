<?php

namespace DiaoJinLong\LaravelVueAdmin\Models;

use Illuminate\Support\Facades\Cache;

class RoleModel extends BaseModel
{

    protected $table = 'role';

    /**
     * 获取所有角色
     * @return array
     */
    public function getAll()
    {
        $field = array(
            'id',
            'name',
            'info',
            'status'
        );
        $list = $this->select($field)->get();
        return $list ? $list->toArray() : [];
    }

}

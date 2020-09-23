<?php

namespace DiaoJinLong\LaravelVueAdmin\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    /**
     * 分页
     * @param $model
     * @param string $orderBy
     * @return array
     */
    public function _page($model, $orderBy = ['id' => 'desc'])
    {
        $page = (int)request()->input('page', 1);
        $limit = (int)request()->input('limit', config('laravel-vue-admin.pages.limit'));
        if ($limit > config('laravel-vue-admin.pages.limit_max')) {
            $limit = config('laravel-vue-admin.pages.limit_max');
        }
        $total = $model->count();
        $offset = ceil($page - 1) * $limit;
        foreach ($orderBy as $order => $sort) {
            $model->orderBy($orderBy, $sort);
        }
        $items = $model->offset($offset)->limit($limit)->get();
        $items = $items ? $items->toArray() : [];
        $pageCount = (int)ceil($total / $limit);
        return array(
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'page_count' => $pageCount,
            'items' => $items
        );
    }
}

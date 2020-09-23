<?php

namespace DiaoJinLong\LaravelVueAdmin\Models;

use Illuminate\Support\Facades\Cache;

class MenuModel extends BaseModel
{

    protected $table = 'menu';

    public function getList()
    {
        $field = array(
            'id',
            'api',
            'component',
            'redirect',
            'always_show',
            'path',
            'name',
            'title',
            'icon',
            'is_menu',
            'hidden',
            'pid',
            'sort'
        );
        $list = $this->select($field)->orderBy('sort', 'desc')->orderBy('id', 'asc')->get();
        return $this->toListTree($list->toArray());
    }


    public function getTree()
    {
        $field = array(
            'id',
            'title',
            'pid'
        );
        $list = $this->orderBy('sort', 'desc')->orderBy('id', 'asc')->select($field)->get();
        return $this->toTree($list->toArray());
    }

    /**
     * 获取菜单
     * @return array
     */
    public function getMenu()
    {
        $field = array(
            'id',
            'component',
            'redirect',
            'always_show',
            'path',
            'name',
            'title',
            'icon',
            'hidden',
            'pid'
        );
        $list = $this->whereIsMenu(1)->orderBy('sort', 'desc')->orderBy('id', 'asc')->select($field)->get();
        return $this->toVueTree($list->toArray());
    }

    /**
     * 处理菜单，返回符合vue路由
     * @param $list
     * @param int $pid
     * @return array
     */
    private function toVueTree($list, $pid = 0)
    {
        $data = array();
        foreach ($list as $key => $item) {
            if ($item['pid'] == $pid) {
                $menu = array(
                    'component' => $item['component'],
                    'redirect' => $item['redirect'],
                    'alwaysShow' => $item['always_show'] == 1 ? 'true' : 'false',
                    'path' => $item['path'],
                    'name' => $item['name'],
                    'meta' => array(
                        'title' => $item['title'],
                        'icon' => $item['icon'],
                        'roles' => (new RoleMenuModel())->getRoleIdsByMenuId($item['id'])
                    ),
                    'hidden' => $item['hidden'] == 1 ? 'true' : 'false'
                );
                if ($list) {
                    $menu['children'] = $this->toVueTree($list, $item['id']);
                    if (empty($menu['children'])) {
                        unset($menu['children']);
                    }
                }
                $data[] = $menu;
                unset($list[$key]);
            }
        }
        return $data;
    }

    /**
     * 处理权限，返回符合vue-tree格式
     * @param $list
     * @param int $pid
     * @return array
     */
    private function toTree($list, $pid = 0)
    {
        $data = array();
        foreach ($list as $key => $item) {
            if ($item['pid'] == $pid) {
                $menu = array(
                    'id' => $item['id'],
                    'label' => $item['title'],
                );
                if ($list) {
                    $menu['children'] = $this->toTree($list, $item['id']);
                    if (empty($menu['children'])) {
                        unset($menu['children']);
                    }
                }
                $data[] = $menu;
                unset($list[$key]);
            }
        }
        return $data;
    }

    /**
     * 返回树状数组
     * @param $list
     * @param int $pid
     * @return array
     */
    private function toListTree($list, $pid = 0)
    {
        $data = array();
        foreach ($list as $key => $item) {
            if ($item['pid'] == $pid) {
                $value = $item;
                $value['children'] = $this->toListTree($list, $item['id']);
                if (empty($value['children'])) {
                    unset($value['children']);
                }
                $data[] = $value;
                unset($list[$key]);
            }
        }
        return $data;
    }

}

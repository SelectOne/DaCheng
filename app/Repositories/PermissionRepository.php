<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/4/27
 * Time: 14:47
 */

namespace App\Repositories;

use DB;
use  App\Repositories\Eloquent\Repository;

class PermissionRepository extends Repository
{
    public function model(){
        return 'App\Models\Permission';
    }

    // 分页
    public function limit($arr)
    {
        $arr['offset'] = ( $arr['page']-1 ) * $arr['limit'];
        if ( ! array_key_exists('field', $arr) && ! array_key_exists('order', $arr) )
        {
            $arr['field'] = "id";
            $arr['order'] = "desc";
        }
        $count = $this->model->count();
        $data = $this->model->orderBy($arr['field'], $arr['order'])->offset($arr['offset'])->limit($arr['limit'])->get();
        foreach ($data as $v) {
            $v['created_time'] = date("Y-m-d H:i:s", $v['created_time']);
            $v['updated_time'] = date("Y-m-d H:i:s", $v['updated_time']);
        }
        $data['count'] = $count;
        return $data;
    }

    public function getAll()
    {
        $permissions = $this->model()::all()->pluck("name", "id");
//        dd($permissions);
        return $permissions;
    }

    public function getPremissions($id)
    {
        $rolePermissions = DB::table("permission_role")->where("permission_role.role_id",$id)
            ->pluck('permission_role.permission_id','permission_role.permission_id')->toArray();
        return $rolePermissions;
    }
}
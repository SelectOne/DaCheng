<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/2
 * Time: 11:01
 */

namespace App\Repositories;

use App\Models\PermissionRole;
use App\Models\Role;
use App\Models\RoleAdmin;
use  App\Repositories\Eloquent\Repository;
use DB;

class RoleRepository extends Repository
{
    public function model()
    {
        return 'App\Models\Role';
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
        $data = $this->model->offset($arr['offset'])->orderBy($arr['field'], $arr['order'])->limit($arr['limit'])->get();
        foreach ($data as $v) {
            $v['created_time'] = date("Y-m-d H:i:s", $v['created_time']);
            $v['updated_time'] = date("Y-m-d H:i:s", $v['updated_time']);
        }
        return $data;
    }

    // 获取所有记录总数
    public function getCount($arr)
    {
        $count = $this->model->count();

        return $count;
    }

    public function update1($id, $data, $permission)
    {
        DB::transaction(function () use($id, $data, $permission){
            $data['updated_time'] = time();
            Role::where('id', $id)->update($data);
            PermissionRole::where("role_id", $id)->delete();
            $role =Role::find($id);
            if ( ! is_null($permission)) {
                foreach ($permission as $key => $value) {
                    $role->attachPermission($value);
                }
            }
        });
    }

    public function create1($data, $permission)
    {
        DB::transaction(function () use($data, $permission){
            $data['created_time'] = time();
            $data['updated_time'] = time();
            $role = Role::create($data);
            if ( ! is_null($permission)) {
                foreach ($permission as $key => $value) {
                    $role->attachPermission($value);
                }
            }
        });
    }

    public function getRoleID($id)
    {
        $rolesID = RoleAdmin::where('admin_id', $id)->pluck("role_id")->toArray();
        return $rolesID;
    }

    public function getRoles()
    {
        $roles = $this->model->pluck( 'name', 'id')->toArray();
        return $roles;
    }
}
<?php
namespace App\Repositories;

use App\Models\Admin;
use  App\Repositories\Contracts\RepositoryInterface;
use  App\Repositories\Eloquent\Repository;

class AdminRepository extends Repository{
    public function model(){
        return 'App\Models\Admin';
    }

    public function checkLogin($name, $password)
    {
        $admin = Admin:: where('admin_name', $name)->first();
        $password = md5($password . $admin['salt']);
        if ($password == $admin['password']) {
            return $admin;
        } else {
            return false;
        }
    }

    // 分页
    public function limit($arr, $app)
    {
        $arr['offset'] = ( $arr['page']-1 ) * $arr['limit'];
        $data = $this->model()::offset($arr['offset'])->limit($arr['limit'])->get();
        foreach ($data as $v) {
            $v['rolesID'] = $app->getRoleID($v['admin_id']);
            $v['created_time'] = date("Y-m-d H:i:s", $v['created_time']);
            $v['updated_time'] = date("Y-m-d H:i:s", $v['updated_time']);
        }
        return $data;
    }

    // 获取所有记录总数
    public function getCount($arr)
    {
        $count = $this->model()::count();

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
}
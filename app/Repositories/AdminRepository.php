<?php
namespace App\Repositories;

use App\Models\Admin;
use App\Models\RoleAdmin;
use  App\Repositories\Contracts\RepositoryInterface;
use  App\Repositories\Eloquent\Repository;
use DB;

class AdminRepository extends Repository{
    public function model(){
        return 'App\Models\Admin';
    }

    public function checkLogin($name, $password)
    {
        $admin = $this->model->where('admin_name', $name)->first();
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
        if ( ! array_key_exists('field', $arr) && ! array_key_exists('order', $arr) )
        {
            $arr['field'] = "admin_id";
            $arr['order'] = "desc";
        }
        $data = $this->model->offset($arr['offset'])->limit($arr['limit'])->orderBy($arr['field'], $arr['order'])->get();
        foreach ($data as $v) {
            $v['rolesID'] = $app->getRoleID($v['admin_id']);
            $roles = $app->getRoles();
            foreach ($v['rolesID'] as $k=>$value) {
                if (array_key_exists($value, $roles)) {
                    $haha[$k] = $roles[$value];
                }
            }
            $v['rolesID'] = $haha;
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

    public function first($id)
    {
        $admin = $this->model->where('admin_id', $id)->first();
        $admin['role'] = $admin->roles()->pluck('id')->toArray();
        return $admin;
    }

    public function update1($id, $role)
    {
        DB::transaction(function () use($id, $role){
            RoleAdmin::where("admin_id", $id)->delete();
            $admin = $this->model()::where('admin_id', $id)->first();
            if ( ! is_null($role)) {
                foreach ($role as $key => $value) {
                    $admin->attachRole($value);
                }
            }
        });
    }

    public function create1($data, $role)
    {
        DB::transaction(function () use($data, $role){
            $data['created_time'] = time();
            $data['updated_time'] = time();
            $password = "admin888";
            $data['salt'] = substr(uniqid(time()),2,6);
            $data['password'] = md5($password . $data['salt']);
            $admin = Admin::create($data);
            if ( ! is_null($role)) {
                foreach ($role as $key => $value) {
                    $admin->attachRole($value);
                }
            }
        });
    }
}
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
}
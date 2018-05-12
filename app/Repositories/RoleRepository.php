<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/2
 * Time: 11:01
 */

namespace App\Repositories;
use App\Models\Node;
use App\Models\Role;
use  App\Repositories\Contracts\RepositoryInterface;
use  App\Repositories\Eloquent\Repository;
use DB;

class RoleRepository extends Repository
{
    public function model()
    {
        return 'App\Models\Role';
    }

    public function getAll()
    {
        $data = Role::all();
        return $data;
    }

    public function  getNodes()
    {
        $data = Node::where('is_menu', '>', 0)->orderBy('sort','desc')->get()->toArray();
        return $data;
    }
}
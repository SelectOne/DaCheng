<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/4/27
 * Time: 14:47
 */

namespace App\Repositories;
use  App\Repositories\Contracts\RepositoryInterface;
use  App\Repositories\Eloquent\Repository;

class PermissionRepository
{
    public function model(){
        return 'App\Models\Permission';
    }

}
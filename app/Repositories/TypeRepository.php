<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/16
 * Time: 14:09
 */

namespace App\Repositories;

use App\Repositories\Eloquent\Repository;

class TypeRepository extends Repository
{

    function model()
    {
        return "App\Models\Type";
    }
}
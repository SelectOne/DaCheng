<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/14
 * Time: 17:42
 */

namespace App\Repositories;

use App\Repositories\Eloquent\Repository;

class OrderRepository extends Repository
{
    function model()
    {
        return "App\Models\Order";
    }
}
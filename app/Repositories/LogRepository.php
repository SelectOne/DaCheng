<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/17
 * Time: 14:57
 */

namespace App\Repositories;


use App\Repositories\Eloquent\Repository;

class LogRepository extends Repository
{

    function model()
    {
        return "App\Models\Log";
    }
}
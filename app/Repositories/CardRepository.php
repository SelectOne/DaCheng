<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/14
 * Time: 17:44
 */

namespace App\Repositories;

use App\Repositories\Eloquent\Repository;

class CardRepository extends Repository
{
    function model()
    {
        return "App\Models\Card";
    }
}
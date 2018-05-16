<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/16
 * Time: 16:41
 */

namespace App\Repositories;

use App\Repositories\Eloquent\Repository;

class CardInfoRepository extends Repository
{

    function model()
    {
        return "App\Models\CardInfo";
    }

}
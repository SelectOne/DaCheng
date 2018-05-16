<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/4/28
 * Time: 15:13
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\Repository;

class BaseController extends Controller
{

    /**
     * 表格数据接口
     * @param Request $request
     * @return json
     */
    public function TableApi( $arr, $repository )
    {
        $data = $repository->limit($arr);
        $count = $repository->getCount($arr);
        return ['code'=>0,'msg'=>'成功','count'=>$count, 'data'=>$data];
    }
}
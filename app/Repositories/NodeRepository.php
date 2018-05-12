<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/2
 * Time: 11:01
 */

namespace App\Repositories;
use App\Models\Node;
use  App\Repositories\Contracts\RepositoryInterface;
use  App\Repositories\Eloquent\Repository;

class NodeRepository extends Repository
{
    public function model()
    {
        return 'App\Models\Node';
    }

    public function getAll()
    {
        $data = Node::where("is_menu", 1)->get();
        return $data;
    }

    public function getNode()
    {
        $data = Node::where("is_menu", '>=', 0)->paginate(10);
        return $data;
    }
}
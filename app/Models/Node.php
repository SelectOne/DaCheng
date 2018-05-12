<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $table = "node";
    public $timestamps = false;
    public $primaryKey = 'node_id';
    protected $fillable = ['node_id', 'sort', 'pid', 'name', 'route', 'is_menu', 'icon'];
}

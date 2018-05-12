<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "role";
    public $timestamps = false;
    public $primaryKey = 'role_id';
    protected $fillable = ["role_id", "name"];
    public function nodes()
    {
        return $this->belongsToMany('App\Models\Node','node_role','node_id','role_id');
    }
}

<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $table = "role";
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = ["id", "name"];
}

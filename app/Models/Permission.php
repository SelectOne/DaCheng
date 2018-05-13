<?php

namespace App\Models;


use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $table = "permission";
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [ 'name', 'display_name', 'description'];
}

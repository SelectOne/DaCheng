<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restrict extends Model
{
    protected $table = "restrict";
    public $timestamps = false;
    protected $fillable = ['id', 'ip', 'limit_login', 'limit_regist', 'content', 'limit_time', 'create_time', 'type'];
}

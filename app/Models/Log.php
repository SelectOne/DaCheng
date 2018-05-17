<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = "log";
    public $timestamps = false;
    protected $fillable = ['admin_id', 'title', 'type', 'created_time'];
}

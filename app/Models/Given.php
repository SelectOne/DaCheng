<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Given extends Model
{
    protected $table = "given";
//    public $timestamps = false;
    protected $fillable = ['id', 'mid', 'type', 'num'];
    const CREATED_AT = 'created_at';
}

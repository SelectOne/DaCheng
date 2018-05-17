<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = "type";
    public $timestamps = false;
    protected $fillable = ['id', 'name', 'card_price', 'given'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinChange extends Model
{
    protected $table = "coin_change";
//    public $timestamps = false;
    protected $fillable = ['id', 'mid', 'type', 'stat_coin', "change_coin", "end_coin", "created_at"];
    const CREATED_AT = 'created_at';
}

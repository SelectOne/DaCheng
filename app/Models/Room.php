<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = "room";
    public $timestamps = false;
    protected $fillable = ['id', 'name'];

    public function num()
    {
        return $this->hasMany("App\Models\CoinChange", "room_id", "id");
    }
}

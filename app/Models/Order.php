<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "order";
    public $primaryKey = "order_id";
    public $timestamps = "false";
    protected $fillable = [ "sn", "mid", "game_id", "amout", "given", "paid", "type", "status", "address", "created_time", "card_id"];
}

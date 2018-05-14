<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = "card";
    public $primaryKey = "card_id";
    public $timestamps = "false";
    protected $fillable = [ "card_id", "admin_id", "card_name", "card_num", "card_price", "total_price", "given", "ip", "used", "created_time", "expire_time"];
}

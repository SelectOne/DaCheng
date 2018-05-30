<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = "card";
    public $primaryKey = "id";
    public $timestamps = "false";
    protected $fillable = [ "card_id", "type_id", "card_info_id", "is_used", "password"];

    public function info()
    {
        return $this->belongsTo("App\Models\CardInfo", "card_info_id", "id");
    }


}

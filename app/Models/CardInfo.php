<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardInfo extends Model
{
    protected $table = "card_info";
    public $primaryKey = "id";
    public $timestamps = "false";
    protected $fillable = [ "admin_id", "card_num", "total_price", "given", "max_use", "created_time", "expire_time"];

    public function admin()
    {
        return $this->belongsTo("App\Models\Admin", "admin_id", "admin_id");
    }
}

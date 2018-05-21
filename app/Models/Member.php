<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = "member";
    public $timestamps = false;

    public function room()
    {
        return $this->hasOne("App\Models\Room", "id", "room_id");
    }
}

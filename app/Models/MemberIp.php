<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberIp extends Model
{
    protected $table = "member_ip";
    public $timestamps = false;

    public function member()
    {
        return $this->hasOne("App\Models\Member", "id","mid");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckinReward extends Model
{
    protected $table = "checkin_reward";
    public $timestamps = false;
    protected $primaryKey = "reward_id";
}

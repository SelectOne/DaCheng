<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Admin extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;

    protected $table = "admin";
    public $timestamps = false;
    public $primaryKey = 'admin_id';
    protected $fillable = [ 'name', 'email', 'password', ];
}

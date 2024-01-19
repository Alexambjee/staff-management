<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IroLoginTrail extends Model
{
    use HasFactory;
    protected $fillable=['USER_NAME','SESSION_ID','STATUS','LOCAL_IP','LOGOUT_TIME','LOGIN_TIME'];
    protected $table='cpl_login_trail';
}

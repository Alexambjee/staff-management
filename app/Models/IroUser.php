<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class IroUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
    'USER_NAME',
    'ROLE_ID',
    'CREATED_BY',
    'LAST_NAME',
    'OTHER_NAMES',
    'EMAIL_ADDRESS',
    'STATUS',
    'password',
    'USER_ID',
    'STATUS'
];
    public $primaryKey='id';
    protected $table='cpl_user_mst';

    public function role(){
        return $this->belongsTo('App\Models\iroRole','ROLE_ID');
    }

    
}

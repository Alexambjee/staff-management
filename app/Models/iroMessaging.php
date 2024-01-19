<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iroMessaging extends Model
{
    use HasFactory;

    protected $fillable=['ATTACHEMENT_ID','SUBJECT','TYPE','CASE_ID','BODY','STATUS','FROM','TO'];
    
    protected $table='cpl_messaging';
    protected $connection = 'pgsql';
}

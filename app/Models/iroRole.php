<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iroRole extends Model
{
    use HasFactory;
    protected $fillable =['ROLE_ID','ROLE_NAME','ROLE_DESC','CREATED_BY'];
    public $primaryKey='ROLE_ID';
    protected $table='cpl_role_mst';
    // protected $connection = 'pgsql';
}

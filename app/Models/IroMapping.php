<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IroMapping extends Model
{
    use HasFactory;
    protected $table='cpl_mapping_mst';
    protected $fillable = ['PIN_NO','AM_PNO' ,'TL_PNO','SM_PNO','TSO_PNO'];
    protected $primaryKey = 'id';
    protected $connection = 'pgsql';
}

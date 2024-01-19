<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iroStation extends Model
{
    use HasFactory;
    public $primaryKey='id';
    protected $table='cpl_station_mst';
    protected $connection = 'pgsql';
    
}

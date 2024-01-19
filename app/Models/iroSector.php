<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iroSector extends Model
{
    use HasFactory;
    public $primaryKey='SECTOR_ID';
    protected $table='cpl_sector_mst';

    protected $connection = 'pgsql';

}

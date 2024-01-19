<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class managerCase extends Model
{
    use HasFactory;

    protected $fillable=['CASE_ID','PERIOD'];
    public $primaryKey='CASE_ID';

    protected $table='cpl_case_mst';
    protected $connection = 'pgsql';
    // defining relationship to sector_mst table
    public function sector(){
        return $this->belongsTo('App\Models\iroSector','SECTOR_ID');
    }
}

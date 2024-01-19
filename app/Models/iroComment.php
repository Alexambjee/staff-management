<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iroComment extends Model
{
    use HasFactory;
    protected $fillable=['REMARKS_NAME','CREATED_BY','REMARKS_STATUS','CASE_ID'];

    protected $table='cpl_remarks_mst';
    protected $connection = 'pgsql';
}

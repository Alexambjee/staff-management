<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iroStatus extends Model
{
    use HasFactory;
    protected $table = 'cpl_status_mst';
    protected $connection = 'pgsql';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iroSelect extends Model
{
    use HasFactory;
    public $primaryKey='id';
    protected $table='cpl_select_option';
    protected $connection = 'pgsql';
}

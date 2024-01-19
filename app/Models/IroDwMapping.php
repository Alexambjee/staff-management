<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IroDwMapping extends Model
{
    use HasFactory;
    protected $table='IRO_MAPPING1';
    protected $fillable = ['OFFICER','MANAGER','CHIEF_MANAGER','AMOUNT'];
    protected $primaryKey = 'OFFICER';
    protected $connection = 'oracle';
}

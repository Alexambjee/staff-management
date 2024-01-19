<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class irotaxpayerreg extends Model
{
    use HasFactory;
    protected $fillable=['PIN_NO'];
    protected $table='EDW_TAXPAYER_REG';
    protected $connection = 'oracle';
}

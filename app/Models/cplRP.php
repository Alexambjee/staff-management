<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cplRP extends Model
{
    use HasFactory;
    protected $table='COMPLIANCE_RISK_PROFILES';
    protected $connection = 'oracle';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iroAuditTrail extends Model
{
    use HasFactory;
    protected $fillable=['CASE_ID','ACTION','CREATED_BY','CLIENT_IP'];
    protected $table='cpl_audit_trail';
}

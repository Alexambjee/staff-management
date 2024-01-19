<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iroAttachement extends Model
{
    use HasFactory;
    protected $fillable=['ATTACHEMENT_NAME','ATTACHEMENT_LINK','ATTACHEMENT_STATUS','CASE_ID'];
    protected $table='cpl_attachement_mst';
    protected $connection = 'pgsql';


}

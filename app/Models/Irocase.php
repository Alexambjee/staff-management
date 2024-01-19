<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Irocase extends Model
{
    use HasFactory;
    // protected $fillable =[
    //     'CASE_ID',
    //     'TAXPAYER_PIN',
    //     'STATION_ID',
    //     'OBLIGATION_ID',
    //     'ASSESSMENT_NUMBER',
    //     'DATE_OF_OBJECTION',
    //     'OBJECTION_AMOUNT',
    //     'PERIOD_TO',
    //     'PERIOD_FROM',
    //     'ASSESSMENT_AMOUNT',
    //     'ASSESSMENT_OFFICER',
    //     'REASON_FOR_OBJECTION',
    //     'OFFICER_PNO',
    //     'MANAGER_PNO',
    //     'CM_PNO',
    //     'EXPECTED_COMPLETION_DATE',
    //     'STATUS',
    //     'TP_NAME',
    //     'TP_EMAIL',
    //     'TP_PHONE',
    //     'CASE_SOURCE'

    // ];
    public $primaryKey='id';
    protected $table='cpl_case_mst';
    protected $connection = 'pgsql';
    // protected $appends ='CURRENT';
    // defining relationship to sector_mst table
    public function obligation(){
        return $this->belongsTo('App\Models\IroObligations','OBLIGATION_ID');
    }
    // public function duration(){
    //     return $this->EXPECTED_COMPLETION_DATE - $this->DATE_OF_OBJECTION;
    // }
    public function status(){
        return $this->belongsTo(iroStatus::class, 'STATUS', 'STATUS_ID');
    }
}

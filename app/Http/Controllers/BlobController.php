<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class BlobController extends Eloquent
{
    //
    public function getAttachment(Request $request){
        $blobObj = new DB;
        
        $data = DB::connection('oracle')
        ->table('IRO_DATA_ATT')
        // ->select('ANALYSIS_REPORT')
        ->where('PIN_NO',$request->tPin)
        ->get();
        return $data;

  
}

}

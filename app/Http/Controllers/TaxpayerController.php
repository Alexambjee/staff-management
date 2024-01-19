<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaxpayerController extends Controller
{
    //Checking if taxpayer Exists
    public function isPinExist(Request $request)
    {
        $pin = $request->tPin;
        $isExist = DB::connection('oracle')
        // $isExist=DB::table('TAXPAYER_REG')
            ->table('EDW_TAXPAYER_REG')
            ->where('PIN_NO', $pin)
            ->first();
            // return $isExist;
        if (!empty($isExist)) {
            return response()->json(
                [
                    // 'res' => $isExist,
                    'message' => 'PIN Found',
                    'code' => 200
                ],
            );
        } else {
            return response()->json(
                [
                    // 'res' => '',
                    'message' => 'PIN NOT Found!',
                    'code' => 401
                ],
            );
        }
          
    }
    //Getting taxpayer details
    public function getTaxpayerDetails(Request $request)
    {
        $pin = $request->tPin;
        $PINsearch = DB::connection('oracle')
        // $PINsearch=DB::table('TAXPAYER_REG')
            ->table('EDW_TAXPAYER_REG')
            ->select('*')
            ->where('PIN_NO',  $pin)
            ->join('EDW_COMMON_CONTACT_DTL', 'EDW_TAXPAYER_REG.TAX_PAYER_ID', '=', 'EDW_COMMON_CONTACT_DTL.TAX_PAYER_ID')
            ->get();
        if($PINsearch->count() == 0){
            return response()->json([
                'msg'=>'Taxpayer pin submitted does not exist!'
            ],401);
        }
        else{
            return $PINsearch;
        }
        DB::beginTransaction();
        try {
        }
        catch (\Exception $e) {
            DB::rollback();
            return 'Something went wrong';
        }
        return  DB::connection('oracle')
            ->table('EDW_TAXPAYER_REG')
            ->select('*')
            ->where('PIN_NO',  $pin)
            ->join('EDW_COMMON_CONTACT_DTL', 'EDW_TAXPAYER_REG.TAX_PAYER_ID', '=', 'EDW_COMMON_CONTACT_DTL.TAX_PAYER_ID')
            ->get();
    }

    public function getStationId(Request $request) {
        $stationName = $request->tStation;
        $stationId =  DB::table('cpl_station_mst')
        ->select('id')
        ->where('STATION_NAME',  $stationName)
        ->get();
        return $stationId;
    }
}

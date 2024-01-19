<?php

namespace App\Imports;

use App\Models\Irocase;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UniqueNumController;

class ImportManual implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        $objUniqueNum = new UniqueNumController();
        $caseNo = $objUniqueNum->generateUniqueNum('0123456789', 6, true);
        $year = Date('Y');
        $caseNum = 'C' . $caseNo . '-' . $year . '-M';

        try {
            $pin=$row[0];
        
            $re2 = DB::table('cpl_mapping_mst')
                ->where('PIN_NO', '=', $pin)
                ->first();
        
            $aman = '';
            $tl = '';
            $sman = '';
            $tso = '';
        
            if ($re2) {
                $aman = $re2->AM_PNO;
                $tl = $re2->TL_PNO;
                $sman = $re2->SM_PNO;
                $tso = $re2->TSO_PNO;
                $status=14;
            }else{
                $aman = '';
                $tl = '';
                $sman = '';
                $tso = '';
                $status=27;
            }
            $tx = DB::connection('oracle')
            ->table('EDW_TAXPAYER_REG')
            ->select('*')
            ->where('PIN_NO',  $pin)
            ->join('EDW_COMMON_CONTACT_DTL', 'EDW_TAXPAYER_REG.TAX_PAYER_ID', '=', 'EDW_COMMON_CONTACT_DTL.TAX_PAYER_ID')
        ->get();
    
        if ($tx->count() > 0){
          foreach ($tx as $taxpayer) { 

                $stationid = $taxpayer->station_id;
                $tname = $taxpayer->tax_payer_name;
                $tmail = $taxpayer->email_id;
                $tphone = $taxpayer->mobile_number;
                $TID = $taxpayer->tax_payer_id;
            }
          
            // $tx=DB::table('TAXPAYER_REG')
            // ->select('*')
           //  ->where('PIN_NO',  $pin)
            // ->get();
        
           //  if ($tx->count() > 0){
             //  foreach ($tx as $taxpayer) {
               //      $stationid = $taxpayer->STATION_ID;
               //      $tname = $taxpayer->TAX_PAYER_NAME;
               //      $tmail = $taxpayer->EMAIL;
                //     $tphone = $taxpayer->PHONE;
                  //   $TID = $taxpayer->TAX_PAYER_ID;
               //  }
            }else{
                $stationid=null;
                $tname='';
                $tmail='';
                $tphone='';
                $TID='';

            }

                DB::table('cpl_case_mst')->insert([
                    'PIN_NO' => $pin,
                    'AM_PNO' => $aman,
                    'TL_PNO' => $tl,
                    'SM_PNO' => $sman,
                    'TSO_PNO' => $tso,
                    'STATUS'=>$status,
                    'STATION_ID'=>$stationid,
                    'TAXPAYER_ID'=>$TID,
                    'TAXPAYER_NAME'=> $tname,
                    'TP_EMAIL'=>$tmail,
                    'TP_PHONE'=>$tphone,
                    'CASE_ID'=>$caseNum,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                    'SOURCE'=>'Manual',
                                ]);
       
                
        // }
    }
    
    catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}

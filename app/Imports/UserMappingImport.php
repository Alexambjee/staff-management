<?php

namespace App\Imports;

use App\Models\IroMapping;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Illuminate\Support\Facades\DB;


class UserMappingImport implements ToModel, WithUpserts, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function upsertColumns()
    {
        return ['AM_PNO'];
    }

    public function startRow(): int
    {
        return 2;
    }
    public function uniqueBy()
    {
        return 'AM_PNO';
    
    }
    public function model(array $row)
    {
        
        // \Log::info(json_encode( $isOfficer))
        $PIN_NO = $row[0]; 
        $AM_PNO = $row[1]; 
        $TL_PNO = $row[2]; 
        $SN_PNO= $row[3]; 
        $TSO_PNO = $row[4]; 
      

        // $isOfficer = IroMapping::where('AM_NO','=',$AM_NO)->get();
        
        // if ($isOfficer->count() == 0) { 
            try{
                DB::insert('insert into cpl_mapping_mst ("PIN_NO", "AM_PNO","TL_PNO","SM_PNO","TSO_PNO") values (?,?,?,?,?)', [$PIN_NO,$AM_PNO,$TL_PNO,$SN_PNO,$TSO_PNO]);
            //    return new IroMapping([
            //         'PIN_NO'=> $PIN_NO,
            //         'AM_NO'=>$AM_PNO, 
            //         'TL_PNO'=> $TL_PNO, 
            //         'SN_PNO'=> $SN_PNO,
            //         'TSO_PNO'=> $TSO_PNO
            //     ]);  
            }catch(\Exception $r){
                 \Log::info(json_encode( $r));
            }
          
        //  } 
        // else if ($isOfficer->count() > 0) { 
        // $intialCount = IroMapping::where('OFFICER','=',$AM_NO)->get('AMOUNT');
        // $int =   $intialCount[0]->AMOUNT;
        // $newCount = $row[3];
        // $totalCount = $int + $newCount;
        // return new IroMapping([
        //     'OFFICER'     => $row[0],
        //     'MANAGER'    => $row[1], 
        //     'CHIEF_MANAGER'    => $row[2], 
        //     'AMOUNT'    => $totalCount 
        // ]);

        // // DB::table('iro_case_mst')->where('OFFICER_PNO', '=', $officer)->update([
        // //     'OFFICER_PNO' => $officer,
        // //     'MANAGER_PNO' => $manager,
        // //     'CM_PNO' => $chiefManager
        // // ]);   


        // } 

    }
}

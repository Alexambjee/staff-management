<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\Imports\UserMappingImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\IroMapping;
use App\Models\IroDwMapping;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Exit_;

class IroMappingController extends Controller
{
    //Adding user mapping
    public function uploadMapping(Request $request)
    {
       
        $request->validate([
            'mapping'=>'required|mimes:xlsx,csv,xls'
        ]);
        // $file = $request->mapping;
        $file = $request->file('mapping');
     
        if ($file) {
           try {
            Excel::import(new UserMappingImport, $file);

           } catch (\Exception $ex) {
           dd($ex);
          
           }
        }
 
    
    }
     //Getting user mapping
    public function getUserMapping(Request $request)
    {
        $search_1= request()->input('query', 1);
        $current_page = request()->input('page', 1);
        $manualOnly = request()->input('manualOnly', null);
        $items_per_page = 10;
        $skip = ($current_page - 1) * $items_per_page;
        $userMapping = DB::table('cpl_mapping_mst')
                    ->orWhere('PIN_NO', 'like', '%'.$search_1.'%')
                    ->select('*')
                    ->orderBy('created_at', 'DESC')
                    ->skip($skip)->take($items_per_page)
                    ->get();
                    // $this->fanya_Mapping();
        return $userMapping;
    }

public function fanya_Mapping(){
    $data =DB::table('cpl_mapping_mst')->select('PIN_NO','AM_PNO','TL_PNO','SM_PNO','TSO_PNO')->get();
 foreach($data as $i)
     {
        DB::table('cpl_case_mst')->where('PIN_NO',$i->PIN_NO)
            ->update( array(
                'AM_PNO'=>$i->AM_PNO,
                'TL_PNO'=>$i->TL_PNO,
                'SM_PNO'=>$i->SM_PNO,
                'TSO_PNO'=>$i->TSO_PNO,
                'STATUS'=>12,
                ));
    }
    return "Mapped";

}
}
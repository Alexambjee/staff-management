<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Irocase;
use App\Models\iroSelect;
use App\Models\iroComment;
use App\Models\cplRP;
use App\Models\IroMapping;
use App\Models\managerCase;
use App\Mail\DecisionIssued;
use Illuminate\Http\Request;
use App\Models\iroAuditTrail;
use App\Models\iroAttachement;
use App\Imports\ImportManual;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\UniqueNumController;
use App\Models\IroPeriods;
use Illuminate\Support\Facades\Log;


class CaseController extends Controller
{ ##manual upload###
    
    public function uploadManual(Request $request)
    {
        $request->validate([
            'cases'=>'required|mimes:xlsx,csv,xls'
        ]);
      
      
        $file = $request->file('cases');
       
        if ($file) {
           try {
            Excel::import(new ImportManual, $file);
           } catch (\Exception $ex) {
           dd($ex);
          
           }
        }
 
    }
    #########function create manual case begins#########
    public function createManualCase(Request $request)
    {
        $this->validate($request, [
            'tPin' => 'bail|required',
      

        ]);
        $objUniqueNum = new UniqueNumController();
        $caseNo = $objUniqueNum->generateUniqueNum('0123456789', 6, true);
        $year = Date('Y');
        $caseNum = 'C' . $caseNo . '-' . $year . '-M';

        $current = now();

        $caseSource = 'Manual';
        $action = 'Creating manual case';
        $clientIp = $request->ip();
        $username = $request->username;

        $attStatus = 0;
        $reason = 'Manual Case';
    
    
        $value_to_check = $request->tPin;
        $found = false;

        $re2 = DB::table('cpl_mapping_mst')
                ->where('PIN_NO', '=', $value_to_check)
                ->get()->first();
        $aman='';
        $tl='';
        $sman='';
        $tso='';

        while(!$found && $row = $re2){
            $found = true;
            $aman=$re2->AM_PNO;
            $tl= $re2->TL_PNO;
            $sman=$re2->SM_PNO;
            $tso= $re2->TSO_PNO;
            break;
        }

        $audit = iroAuditTrail::create([
            'ACTION' => $action,
            'CREATED_BY' => $username,
            'CLIENT_IP' => $clientIp
        ]);

        if ($audit) {

            if ($found){
                
                switch (true) {
                    
                    case ($request->role == 'AccountManager'):

                        $status=12;

                        break;
                    case ($request->role == 'TeamLead'):

                        $status=13;

                        break;
                    case ($request->role == 'SectorManager'):

                        $status=14;

                        break;
                    case ($request->role == 'TSOhead'):

                        $status=0;


                        break;
                    default:
                        \Log::info( "error kwa case mapping status ebu angalia");
                        break;
                }
                if (is_array($request->filename)){
                    DB::table('cpl_remarks_mst')-> insert([
                        'REMARKS' => $request->objReason,
                        'REMARKS_TYPE'=>$reason,
                        'CREATED_BY_' => $request->username,
                        'CASE_ID' => $caseNum,
                        'REASON_ID' => 0,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ]);
                    $datafile = (object) ['filename' => $request->filename];
                    foreach ($datafile->filename as $filename) {
                        $filePath = '/uploads/' . $filename;
                        DB::table('cpl_attachement_mst')-> insert([
                            'ATTACHEMENT_NAME' => $filename,
                            'ATTACHEMENT_LINK' => $filePath,
                            'CASE_ID' => $caseNum,
                            'ATTACHEMENT_STATUS' => $attStatus,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }

                    DB::table('cpl_case_mst')->insert([
                        'PIN_NO' => $request->tPin,
                        'STATION_ID' => $request->stId,
                        'OBLIGATION_ID' => $request->tObligation,
                        'STATUS' => $status,
                        'TAXPAYER_NAME' => $request->tName,
                        'TP_EMAIL' => $request->tMail,
                        'TP_PHONE' => $request->tPhone,
                        'SOURCE' => $caseSource,
                        'AM_PNO' => $aman,
                        'TL_PNO' => $tl,
                        'SM_PNO' => $sman,
                        'TSO_PNO' => $tso,
                        'CASE_ID' => $caseNum,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ]);
            
                
                    return DB::table('cpl_case_mst')->where( 'CASE_ID',$caseNum)
                    ->join('cpl_status_mst','cpl_case_mst.STATUS','=','cpl_status_mst.STATUS_ID')                
                    ->get();
                }
                else
                {
                    DB::table('cpl_remarks_mst')-> insert([
                        'REMARKS' => $request->objReason,
                        'REMARKS_TYPE'=>$reason,
                        'CREATED_BY_' => $request->username,
                        'CASE_ID' => $caseNum,
                        'REASON_ID' => 0,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ]);
                    DB::table('cpl_case_mst')->insert([
                        'PIN_NO' => $request->tPin,
                        'STATION_ID' => $request->stId,
                        'OBLIGATION_ID' => $request->tObligation,
                        'STATUS' => $status,
                        'TAXPAYER_NAME' => $request->tName,
                        'TP_EMAIL' => $request->tMail,
                        'TP_PHONE' => $request->tPhone,
                        'SOURCE' => $caseSource,
                        'CASE_ID' => $caseNum,
                        'AM_PNO' => $aman,
                        'TL_PNO' => $tl,
                        'SM_PNO' => $sman,
                        'TSO_PNO' => $tso,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ]);
                    return DB::table('cpl_case_mst')->where( 'CASE_ID',$caseNum)
                    ->join('cpl_status_mst','cpl_case_mst.STATUS','=','cpl_status_mst.STATUS_ID')                
                    ->get();
                }
        
            } 
            else {
                $status = 27;

                if (is_array( $request->filename)){
                    DB::table('cpl_remarks_mst')-> insert([
                        'REMARKS' => $request->objReason,
                        'REMARKS_TYPE'=>$reason,
                        'CREATED_BY_' => $request->username,
                        'CASE_ID' => $caseNum,
                        'REASON_ID' => 0,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ]);
                    $datafile = (object) ['filename' => $request->filename];
                    foreach ($datafile->filename as $filename) {
                        $filePath = '/uploads/' . $filename;
                        DB::table('cpl_attachement_mst')-> insert([
                            'ATTACHEMENT_NAME' => $filename,
                            'ATTACHEMENT_LINK' => $filePath,
                            'CASE_ID' => $caseNum,
                            'ATTACHEMENT_STATUS' => $attStatus,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    DB::table('cpl_case_mst')->insert([
                            'PIN_NO' => $request->tPin,
                            'STATION_ID' => $request->stId,
                            'OBLIGATION_ID' => $request->tObligation,
                            'STATUS' => $status,
                            'TAXPAYER_NAME' => $request->tName,
                            'TP_EMAIL' => $request->tMail,
                            'TP_PHONE' => $request->tPhone,
                            'SOURCE' => $caseSource,
                            'CASE_ID' => $caseNum,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                    ]);
                    return DB::table('cpl_case_mst')->where( 'CASE_ID',$caseNum)
                    ->join('cpl_status_mst','cpl_case_mst.STATUS','=','cpl_status_mst.STATUS_ID')                
                    ->get();
                
                }
                else
                {
                    DB::table('cpl_remarks_mst')-> insert([
                        'REMARKS' => $request->objReason,
                        'REMARKS_TYPE'=>$reason,
                        'CREATED_BY_' => $request->username,
                        'CASE_ID' => $caseNum,
                        'REASON_ID' => 0,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ]);
                    DB::table('cpl_case_mst')->insert([
                        'PIN_NO' => $request->tPin,
                        'STATION_ID' => $request->stId,
                        'OBLIGATION_ID' => $request->tObligation,
                        'STATUS' => $status,
                        'TAXPAYER_NAME' => $request->tName,
                        'TP_EMAIL' => $request->tMail,
                        'TP_PHONE' => $request->tPhone,
                        'SOURCE' => $caseSource,
                        'CASE_ID' => $caseNum,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ]);
                    return DB::table('cpl_case_mst')->where( 'CASE_ID',$caseNum)
                    ->join('cpl_status_mst','cpl_case_mst.STATUS','=','cpl_status_mst.STATUS_ID')                
                    ->get();
                }
            }
        }
    }
    #########function create manual case ends#########
    #########function get stations begins##############
    public function getStations(Request $request)
    {
        $stations = DB::table('cpl_station_mst')->select('id', 'STATION_NAME')->orderBy('STATION_NAME', 'ASC')->get();
        return $stations;
        
    }

    #########function get stations ends##############
    #########function get stations begins##############
    public function getObligations(Request $request)
    {
        $obligations = DB::table('cpl_obligation_mst')->select('OBLIGATION_ID', 'OBLIGATION_NAME')->orderBy('OBLIGATION_NAME', 'ASC')->get();
        return $obligations;
    }
    #########function get stations ends##############

    #######======Report Functions begins=====#######
    // =========== fetching admin for All Cases Report ===============
    public function  getAdminProgress(Request $request)
    {
            // return $username;
        $current_page = request()->input('page', 1);
        $items_per_page = 10;
        $skip = ($current_page - 1) * $items_per_page;
        $admin  = Irocase::join('cpl_status_mst', 'cpl_case_mst.STATUS', '=', 'cpl_status_mst.STATUS_ID')
        ->skip($skip)->take($items_per_page)    
        ->get();
        return $admin;

    }
    // =========== fetching Completed Cases Reports ===============
    public function fetchCompletedCases(Request $request)
    {
        $reportCompleted = Irocase::join('cpl_status_mst', 'cpl_case_mst.STATUS', '=', 'cpl_status_mst.STATUS_ID')
            ->whereIn('STATUS', [11])
            ->get();
        return  $reportCompleted;
    }
    // =========== fetching Cases In Progress Reports ===============
    public function fetchInProgress(Request $request)
    {
        $reportInProgress = Irocase::join('cpl_status_mst', 'cpl_case_mst.STATUS', '=', 'cpl_status_mst.STATUS_ID')
            ->whereIn('STATUS', [1, 2, 3, -3, 4, 8, 7])
            ->get();
        return   $reportInProgress;
    }
    
    // =========== fetching reverted Cases Reports ===============
    public function revertedCases(Request $request)
    {
        $reportsReverted = DB::connection('pgsql')->table('cpl_case_mst')
            ->join('cpl_status_mst', 'cpl_case_mst.STATUS', '=', 'cpl_status_mst.STATUS_ID')
            ->whereIn('STATUS', [-1, -2,-6,-5,-3])
            ->get();
        return  $reportsReverted;
    }
// ######=======getting donut reports======#####
    public function fetchReportData(Request $request)
    {
        $allCases = DB::connection('pgsql')->table('cpl_case_mst')
        // ->whereIn('STATUS', [0,1, 2, 3, -3, 4, 8, 7, -4, 9, 5, 6, -1, -2])
            ->get();
        $countAllCases = $allCases->count();

        $reportInProgress = DB::connection('pgsql')->table('cpl_case_mst')
            ->whereIn('STATUS', [1,2, 3, -3,-1,-2,-5,-6,4,5, 8, 9,18,20])
            ->get();
        
        $countProgress = $reportInProgress->count();

        $completedDecisionsMade = DB::connection('pgsql')->table('cpl_case_mst')
                    ->whereIn('STATUS',[11])
                    ->get();
        // \Log::info($completedDecisionsMade);
         $count_completedDecisionsMade = $completedDecisionsMade->count();

        $revertedCases = DB::connection('pgsql')->table('cpl_case_mst')
                    ->whereIn('STATUS',[-1,-2,-3,-6])
                    ->get();
         $count_revertedCases = $revertedCases->count();


        $reportSeriesData = array($countAllCases, $countProgress,$count_completedDecisionsMade, $count_revertedCases);
        return $reportSeriesData;
    }

    // ######====getting bar revenue report=====#####
    public function getReportBarData(Request $request)
    {
        $accepted = DB::connection('pgsql')->table('cpl_case_mst')
        ->get();
        $prelimAmount = $accepted->sum('PRELIM_REPORT_AMOUNT');
    
        $objected = DB::connection('pgsql')->table('cpl_case_mst')
        ->get();
        $Finalamount = $objected->sum('REPORT_FIN_AMOUNT');
        $seriesData = array( $prelimAmount, $Finalamount);
        return $seriesData;
    }
    #######======Report Functions begins=====#######
    public function getRpHistory(Request $request)
    {
        $tpin = $request->tpin;
        $conn3 = oci_connect('RISK', 'risk#dbm02', '10.30.100.62:1521/dbm02');

        $stidx = oci_parse($conn3, "SELECT * FROM RISK.COMPLIANCE_RISK_PROFILES WHERE PIN_NO = :tpin");
        oci_bind_by_name($stidx, ":tpin", $tpin);

        oci_execute($stidx);

        $results = [];
        while (($row = oci_fetch_assoc($stidx)) !== false) {
            $results[] = $row;
        }

        return $results;
    }
    #######======Risk Profile Histrory Function ends======#######


    #########function get all cases begins##########
    public function getAllCases(Request $request)
    {
        $search_1= request()->input('query', 1);
    if (request()->input('role', 1)=='Admin'){
        $search_1= request()->input('query', 1);

        $current_page = request()->input('page', 1);
        $items_per_page = 10;
        $skip = ($current_page - 1) * $items_per_page;
        return DB::connection('pgsql')
            ->table('cpl_case_mst')
            ->whereIn('STATUS',[27])
            ->where(function($query) use ($search_1) {
                $query->orwhere('PIN_NO', 'like', '%'.$search_1.'%')
                      ->orWhere('TAXPAYER_NAME', 'like', '%'.$search_1.'%')
                      ->orWhere('CASE_ID', 'like', '%'.$search_1.'%');
            })
            ->orderBy('cpl_case_mst.created_at', 'desc')
            ->skip($skip)->take($items_per_page)
            ->get();

     
    }else {
        $current_page = request()->input('page', 1);
      
        $items_per_page = 10;
        $skip = ($current_page - 1) * $items_per_page;
      

        if ($request->isMethod('get')) {
            return DB::connection('pgsql')->table('cpl_case_mst')
            ->join('cpl_status_mst','cpl_case_mst.STATUS','=','cpl_status_mst.STATUS_ID')
            ->where(function($query) use ($search_1) {
                $query->orwhere('PIN_NO', 'like', '%'.$search_1.'%')
                      ->orWhere('TAXPAYER_NAME', 'like', '%'.$search_1.'%')
                      ->orWhere('CASE_ID', 'like', '%'.$search_1.'%');
            })
            ->orderBy('cpl_case_mst.created_at', 'desc')
            ->skip($skip)->take($items_per_page)
            ->get();

        } else {
            $case_id = $request->input('caseId'); 
            $status = $request->input('status');
            DB::connection('pgsql')->table('cpl_case_mst')
                ->where('case_id', $case_id)
                ->where(function($query) use ($search_1) {
                    $query->orwhere('PIN_NO', 'like', '%'.$search_1.'%')
                          ->orWhere('TAXPAYER_NAME', 'like', '%'.$search_1.'%')
                          ->orWhere('CASE_ID', 'like', '%'.$search_1.'%');
                })
                ->skip($skip)->take($items_per_page)
                ->update(['STATUS' => $status]);
            return response()->json(['message' => 'Status updated']);
        }  
        }
     
     
    }
        ############function to update status###########
        public function updateCase(Request $request){
            $data = $request->all();
            
            if ($data['case'] === "reverted") {
                // Set status to "Case in teamleads profile"
                $status = $data['status'];
                // Updating Attachment DB
                $isAttached = iroAttachement::where('CASE_ID', $data['caseId'])->exists() ? 'true': 'false';
                if ($isAttached === 'true') {
    
                    iroAttachement::where('CASE_ID', $data['caseId'])->update(['updated_at' => Carbon::now()]);
                    $sum = 0;
                    for ($itemIdx=0; $itemIdx < count($request->items); $itemIdx++) { 
                        
                        $pItem = $request->items[$itemIdx];
                        $oblId = DB::connection('pgsql')->table('cpl_obligation_mst')->where('OBLIGATION_NAME', $pItem['TaxHead'])->get()->pluck('OBLIGATION_ID')->first();
                        $sum += $pItem['Amount'];
                        // Updating period's DB
                        DB::connection('pgsql')->table('cpl_periods_mst')
                        ->where('CASE_ID',$data['caseId'])
                        ->where('OBLIGATION_ID',$oblId)
                        ->update([
                            'YEAR_FROM' => $pItem['Year_from'], 
                            'YEAR_TO' => $pItem['Year_to'], 
                            'PRELIM_REPORT_AMOUNT' => $pItem['Amount'],
                            'OBLIGATION_ID' => $oblId,
                            'updated_at' => Carbon::now()
                        ]);
                        DB::connection('pgsql')->table('cpl_case_mst')
                        ->where('CASE_ID',$data['caseId'])
                        ->update([
                            'STATUS' => $status, 
                            'OBLIGATION_ID' => $oblId,
                        ]);
                    }
    
                    // Updating case's DB
                    DB::connection('pgsql')->table('cpl_case_mst')
                    ->where('CASE_ID',$data['caseId'])
                    ->update([ 
                        'PRELIM_REPORT_AMOUNT' => $sum,
                        'updated_at' => Carbon::now()
                    ]);
    
                } else {
    
                    $datafile = (object) ['filename' => $request->filename];
                    foreach ($datafile->filename as $filename) {
                        $filePath = '/uploads/' . $filename;
                            
                        // Check if the file already exists in the /uploads directory
                        if (file_exists(public_path($filePath))) {
                            $attachment = DB::table('cpl_attachement_mst')
                                ->where('ATTACHEMENT_NAME', $filename)
                                ->first();
                            
                            if ($attachment) {
                                // If the attachment record already exists, update it
                                DB::table('cpl_attachement_mst')
                                    ->where('ATTACHEMENT_NAME', $filename)
                                    ->where('CASE_ID', $data['caseId'])
                                    ->update([
                                        'ATTACHEMENT_STATUS' => 1 
                                    ]);
                            } else {
                                // If the attachment record doesn't exist, create a new 
                                $attStatus = 0;
                                DB::table('cpl_attachement_mst')->insert([
                                    'ATTACHEMENT_NAME' => $filename,
                                    'ATTACHEMENT_LINK' => $filePath,
                                    'CASE_ID' => $data['caseId'],
                                    'ATTACHEMENT_STATUS' => $attStatus,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now() 
                                ]);
                            }
                        }
                    }
    
                    
                    for ($itemIdx=0; $itemIdx < count($request->items); $itemIdx++) { 
                        $pItem = $request->items[$itemIdx];
                        // Retrieving Obligation ID's fom DB
                        $oblId = DB::connection('pgsql')->table('cpl_obligation_mst')->where('OBLIGATION_NAME', $pItem['TaxHead'])->get()->pluck('OBLIGATION_ID')->first();
                        // \Log::info([$oblId]);
                            
                        // Updating case's DB
                        DB::connection('pgsql')->table('cpl_case_mst')
                        ->where('CASE_ID',$data['caseId'])
                        ->update([
                            'STATUS' => $status, 
                            'OBLIGATION_ID' => $oblId,
                            'updated_at' => Carbon::now()
                        ]);
                    }
                }
            } 
            elseif($data['case'] === "AssessmentReturns"){
                switch (empty($data['filename'])) {
                    case true:
                            
                        // handle the case where the file doesn't exist
                        $data1 = $request->all();
                        $sum = 0;
                        foreach ($data1 as $data_save) {
                            if (is_array($data_save) && array_key_exists('REPORT_FIN_AMOUNT', $data_save)) {
                                $sum += intval($data_save['REPORT_FIN_AMOUNT']);
                                $fin_action = "Close Case";
                                DB::connection('pgsql')->table('cpl_periods_mst')
                                    ->where('CASE_ID', $data['caseId'])
                                    ->where('id', $data_save['id'])
                                    ->update([
                                        'REPORT_FIN_AMOUNT' => $data_save['REPORT_FIN_AMOUNT'],
                                        'REPORT_ACTION' => $fin_action,
                                        'updated_at' => Carbon::now()
                                    ]);
                            }
                        }
                        
    
                        iroCase::where('CASE_ID', $data['caseId'])
                            ->update([
                                'STATUS' => 11,
                                'REPORT_FIN_AMOUNT' => $sum,
                                'updated_at' => Carbon::now()
                        ]);
    
                        break;
    
                    case false:
    
                        $datafile = (object) ['filename' => $request->filename];
                        foreach ($datafile->filename as $filename) {
                            $filePath = '/uploads/' . $filename;
                                
                            // Check if the file already exists in the /uploads directory
                            if (file_exists(public_path($filePath))) {
                                $attachment = DB::table('cpl_attachement_mst')
                                    ->where('ATTACHEMENT_NAME', $filename)
                                    ->first();
                                
                                if ($attachment) {
                                    // If the attachment record already exists, update it
                                    DB::table('cpl_attachement_mst')
                                        ->where('ATTACHEMENT_NAME', $filename)
                                        ->where('CASE_ID', $data['caseId'])
                                        ->update([
                                            'ATTACHEMENT_STATUS' => 1 
                                        ]);
                                } else {
                                    // If the attachment record doesn't exist, create a new 
                                    $attStatus = 0;
                                    DB::table('cpl_attachement_mst')->insert([
                                        'ATTACHEMENT_NAME' => $filename,
                                        'ATTACHEMENT_LINK' => $filePath,
                                        'CASE_ID' => $data['caseId'],
                                        'ATTACHEMENT_STATUS' => $attStatus,
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now() 
                                    ]);
                                }
                            }
                        }
    
                        $data1 = $request->all();
                        \Log::info(["fgsdfg",$data1,]);
                        $sum = 0;
                        foreach ($data1 as $data_save) {
                            $fin_action = "Close Case";
                            $sum += $data_save['REPORT_FIN_AMOUNT'];
                            DB::connection('pgsql')->table('cpl_periods_mst')
                                ->where('CASE_ID', $data['caseId'])
                                ->where('id', $data_save['id'])
                                ->update([
                                    'REPORT_FIN_AMOUNT' => $data_save['REPORT_FIN_AMOUNT'],
                                    'REPORT_ACTION' => $fin_action,
                                    'updated_at' => Carbon::now()
                                ]);
                        }
                        iroCase::where('CASE_ID', $data['caseId'])
                            ->update([
                                'STATUS' => 11,
                                'REPORT_FIN_AMOUNT' => $sum,
                                'updated_at' => Carbon::now()
                            ]);
    
                        break;
    
                    default:
                        // handle any other cases (optional)
                        break;
                }
            } 
            elseif ($data['case'] === "prelimAmount" && $data['Choice'] == "periods") {
    
            } else {
    
                if ($data['Choice'] == "periods") {
                    
                    if ($data['here'] == "prelimAmount") {
                        $oblId = DB::connection('pgsql')->table('cpl_obligation_mst')->where('OBLIGATION_NAME', $data['Obligation'])->get()->pluck('OBLIGATION_ID')->first();
        
                        $periods_data = [
                            'YEAR_FROM' => $data['Year_from'] ?? null,
                            'YEAR_TO' => $data['Year_to'] ?? null,
                            'PRELIM_REPORT_AMOUNT' => $data['Amount'] ?? null,
                            'REPORT_ACTION' => $data['Recommendation'],
                            'CASE_ID' => $data['caseId'],
                            'OBLIGATION_ID' => $oblId,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
    
                        DB::connection('pgsql')->table('cpl_periods_mst')->insertGetId($periods_data);
    
                        $remark = DB::table('cpl_remarks_mst')
                                    ->where('REMARKS', $data['Remarks'])
                                    ->where('CASE_ID' , $data['caseId'])
                                    ->first();
                        if ($remark) {
                            // If the remarks already exists, update it to avoid duplication
                            DB::table('cpl_remarks_mst')
                                ->where('REMARKS', $data['Remarks'])
                                ->where('CASE_ID' , $data['caseId'])
                                ->update([
                                    'updated_at' => Carbon::now()
                                ]);   
                        } else {
                            // If the remarks doesn't exist, create a new 
                            $remarks_data = [
                                'REMARKS_TYPE' => $data['ReasonName'],
                                'RECOMMENDATION' => $data['Recommendation'],
                                'REMARKS_TYPE' => $data['Reason'],
                                'CASE_ID' => $data['caseId'],
                                'REMARKS' => $data['Remarks'],
                                'REASON_ID' => $data['remarks'],
                                'CREATED_BY_'=>$data['username'],
                                'REASON_ID' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];
    
                            DB::connection('pgsql')->table('cpl_remarks_mst')->insertGetId($remarks_data);
                        }
                        $case_mst_data = [
                            'STATUS' => $data['status'],
                            'OBLIGATION_ID' => $oblId,
                            'updated_at' => Carbon::now(),
                        ];
                        DB::connection('pgsql')->table('cpl_case_mst')->where('CASE_ID', $data['caseId'])->update($case_mst_data);
    
                        $datafile = (object) ['filename' => $request->filename];
    
                        foreach ($datafile->filename as $filename) {
                            $filePath = '/uploads/' . $filename;
                        
                            // Check if the file already exists in the /uploads directory
                            if (file_exists(public_path($filePath))) {
                                $attachment = DB::table('cpl_attachement_mst')
                                    ->where('ATTACHEMENT_NAME', $filename)
                                    ->first();
                        
                                if ($attachment) {
                                    // If the attachment record already exists, update it
                                    DB::table('cpl_attachement_mst')
                                        ->where('ATTACHEMENT_NAME', $filename)
                                        ->where('CASE_ID', $data['caseId'])
                                        ->update([
                                            'ATTACHEMENT_STATUS' => 1 
                                        ]);
                                } else {
                                    // If the attachment record doesn't exist, create a new 
                                    $attStatus = 0;
                                    DB::table('cpl_attachement_mst')->insert([
                                        'ATTACHEMENT_NAME' => $filename,
                                        'ATTACHEMENT_LINK' => $filePath,
                                        'CASE_ID' => $data['caseId'],
                                        'ATTACHEMENT_STATUS' => $attStatus,
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now() 
                                    ]);
                                }
                            }
                        }
    
                        return $data;
                    } else {
                        $case_mst_data = [
                            'PRELIM_REPORT_AMOUNT' => $data['prAmount'] ?? null,
                        ];
                        DB::connection('pgsql')->table('cpl_case_mst')->where('CASE_ID', $data['caseId'])->update($case_mst_data);
                    }
                    
                    // $case_mst_data = [
                    //     'STATUS' => $data['status'],
                    //     'OBLIGATION_ID' => $oblId,
                    //     'PRELIM_REPORT_AMOUNT' => $data['Amount'] ?? null,
                    //     'updated_at' => Carbon::now(),
                    // ];
    
                    DB::connection('pgsql')->table('cpl_case_mst')->where('CASE_ID', $data['caseId'])->update($case_mst_data);
    
                    $datafile = (object) ['filename' => $request->filename];
    
                    // foreach ($datafile->filename as $filename) {
                    //     $filePath = '/uploads/' . $filename;
                    
                    //     // Check if the file already exists in the /uploads directory
                    //     if (file_exists(public_path($filePath))) {
                    //         $attachment = DB::table('cpl_attachement_mst')
                    //             ->where('ATTACHEMENT_NAME', $filename)
                    //             ->first();
                    
                    //         if ($attachment) {
                    //             // If the attachment record already exists, update it
                    //             DB::table('cpl_attachement_mst')
                    //                 ->where('ATTACHEMENT_NAME', $filename)
                    //                 ->where('CASE_ID', $data['caseId'])
                    //                 ->update([
                    //                     'ATTACHEMENT_STATUS' => 1 
                    //                 ]);
                    //         } else {
                    //             // If the attachment record doesn't exist, create a new 
                    //             $attStatus = 0;
                    //             DB::table('cpl_attachement_mst')->insert([
                    //                 'ATTACHEMENT_NAME' => $filename,
                    //                 'ATTACHEMENT_LINK' => $filePath,
                    //                 'CASE_ID' => $data['caseId'],
                    //                 'ATTACHEMENT_STATUS' => $attStatus,
                    //                 'created_at' => Carbon::now(),
                    //                 'updated_at' => Carbon::now() 
                    //             ]);
                    //         }
                    //     }
                    // }
    
                    // return $data;
                } 
                elseif($data['Choice'] == "others"){
                    $attStatus = 0;
                     // insert into attachment table
                     $periods_data = [
                        'REPORT_ACTION' => $data['Recommendation'],
                        'created_at' => Carbon::now(),
                          'CASE_ID' => $data['caseId'],
                        'updated_at' => Carbon::now(),
                        'OBLIGATION_ID' => 0,
                    ];
                    DB::connection('pgsql')->table('cpl_periods_mst')->insertGetId($periods_data);
    
                    $datafile = (object) ['filename' => $request->filename];
    
                    foreach ($datafile->filename as $filename) {
                        $filePath = '/uploads/' . $filename;
                    
                        // Check if the file already exists in the /uploads directory
                        if (file_exists(public_path($filePath))) {
                            $attachment = DB::table('cpl_attachement_mst')
                                ->where('ATTACHEMENT_NAME', $filename)
                                ->first();
                    
                            if ($attachment) {
                                // If the attachment record already exists, update it
                                DB::table('cpl_attachement_mst')
                                    ->where('ATTACHEMENT_NAME', $filename)
                                    ->where('CASE_ID', $data['caseId'])
                                    ->update([
                                        'ATTACHEMENT_STATUS' => 1 
                                    ]);
                            } else {
                                // If the attachment record doesn't exist, create a new 
                                $attStatus = 0;
                                DB::table('cpl_attachement_mst')->insert([
                                    'ATTACHEMENT_NAME' => $filename,
                                    'ATTACHEMENT_LINK' => $filePath,
                                    'CASE_ID' => $data['caseId'],
                                    'ATTACHEMENT_STATUS' => $attStatus,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now() 
                                ]);
                            }
                        }
                    }
                    // insert into remarks table
                    DB::table('cpl_remarks_mst')-> insert([
                        'REMARKS_TYPE' => $data['ReasonName'],
                        'RECOMMENDATION' => $data['Recommendation'],
                        'CASE_ID' => $data['caseId'],
                        'REMARKS' => $data['Remarks'],
                        'REASON_ID' => $data['remarks'],
                        'CREATED_BY_'=>$data['username'],
                        'REASON_ID' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                    // \Log::info($rmk);
                    // update case mst table status to 3
                    DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array('STATUS' => $data['status'],
                        'updated_at'=>Carbon::now(),));
                }else {
                    # code...
                    // \Log::info("Else");
                }
            }
        }
    public function fetchAssessment(Request $request)
    {
        return Irocase::where('CASE_ID', $request->caseId)->get(['ASSESSMENT_ID']);
    }
    #########function get all cases ends############
    // fetching taxpayer response status
    public function fetchRespondedCases(Request $request)
    {
        $data = [$request->data];
        $d = explode(',', $data[0]);
        $caseStatus = $d[0];
        $username = $d[1];

        // return $request->caseStatus;
        // fetching all taxpayer responses on cases based on status
        return DB::connection('pgsql')->table('cpl_case_mst')
            ->where('AM_PNO', $username)
            ->where('STATUS', $caseStatus)
            ->join('cpl_status_mst', 'cpl_case_mst.STATUS', '=', 'cpl_status_mst.STATUS_ID')
            ->get();
    }

    // fetching Re-assigned cases
    public function getReassignedCases(Request $request)
    {
        // fetching all cases based on status
        return Irocase::where('STATUS', 1)->get();
    }
    // fetching Reassign cases AM_PNO
    public function fetchOfficers(Request $request)
    {
        $data = [$request->Fdata];
        $exploded = explode(',', $data[0]);
        $column = $exploded[0];
        $username = $exploded[1];
        // fetching all officers based on TL_PNOs
        return DB::table('cpl_mapping_mst')->where($column, $username)
            ->join('cpl_user_mst', 'cpl_mapping_mst.OFFICER', '=', 'cpl_user_mst.USER_NAME')
            ->get();
    }

    // fetching cases
    public function getCases(Request $request)
    {
        
        $search_1= request()->input('query', 1);
        $data = [$request->data];
        $data = explode('*', $request->data);
        $dstatus = $data[0];
        $status = explode(',', $dstatus);
        $other = $data[1];
        $Dother = explode(',', $other);
        $username = $Dother[0];
        $column = $Dother[1];

        // return $username;
        $current_page = request()->input('page', 1);
        $manualOnly = request()->input('manualOnly', null);
        $items_per_page = 10;
        $skip = ($current_page - 1) * $items_per_page;

        // fetching all cases based on status, username and column passed
        $queryBuilder = Irocase::where($column, $username)
                    ->whereIn('STATUS', $status)
                    ->where(function($query) use ($search_1) {
                        $query->orwhere('PIN_NO', 'like', '%'.$search_1.'%')
                              ->orWhere('TAXPAYER_NAME', 'like', '%'.$search_1.'%')
                              ->orWhere('CASE_ID', 'like', '%'.$search_1.'%');
                    })
                    ->orderBy('cpl_case_mst.created_at','ASC');
       
       
        if ($manualOnly != null){
            $queryBuilder -> where('SOURCE', 'Manual');
        }
        return 
        $queryBuilder
            ->join('cpl_status_mst', 'cpl_case_mst.STATUS', '=', 'cpl_status_mst.STATUS_ID')
            ->skip($skip)->take($items_per_page)
            ->get();
    }

    public function getCaseCount(Request $request){
        $data = explode('*', $request->data);
        $status = explode(',', $data[0]);
        [$username, $column] = explode(',', $data[1]);

        return iroCase::where($column, $username)
            ->whereHas('status', function ($query) use ($status) {
                $query->whereIn('STATUS', $status);
            })
            ->count();
    }


    // fetching single case details
    public function fetchDetails(Request $request)
    {
        
        // FETCHING A SINGLE CASE DETAIL
        $result = DB::connection('pgsql')->table('cpl_case_mst')->where('CASE_ID', $request->caseId)->get();
        // \Log::info(json_encode($result ));
        // check if rejection id does not exists
        // check if casetype exists
        // run if typeid does not exist
        if ($result[0] ->TYPE_ID == '') {
            $table1=DB::connection('pgsql')->table('cpl_case_mst')->where('CASE_ID', '=', $request->caseId)
                ->join('cpl_station_mst', 'cpl_case_mst.STATION_ID', '=', 'cpl_station_mst.STATION_ID')
                // ->join('cpl_obligation_mst', 'cpl_case_mst.OBLIGATION_ID', '=', 'cpl_obligation_mst.OBLIGATION_ID')
                ->join('cpl_status_mst', 'cpl_case_mst.STATUS', '=', 'cpl_status_mst.STATUS_ID')
                ->get(); 
                
                // \Log::info( $request->caseId);
            $table2=DB::connection('pgsql')->table('cpl_periods_mst')->where('CASE_ID', '=', $request->caseId)
                    ->join('cpl_obligation_mst', 'cpl_obligation_mst.OBLIGATION_ID' , '=','cpl_periods_mst.OBLIGATION_ID')
                    ->get();
                // \Log::info($table2);

            return ['details' => $table1, 'period' => $table2];
        }
        // run if typeid does exists
        else {
            return DB::connection('pgsql')->table('cpl_case_mst')->where('CASE_ID', '=', $request->caseId)
                ->join('cpl_station_mst', 'cpl_case_mst.STATION_ID', '=', 'cpl_station_mst.STATION_ID')
                ->join('cpl_obligation_mst', 'cpl_case_mst.OBLIGATION_ID', '=', 'cpl_obligation_mst.OBLIGATION_ID')
                ->join('cpl_status_mst', 'cpl_case_mst.STATUS', '=', 'cpl_status_mst.STATUS_ID')
                ->join('cpl_select_option', 'cpl_case_mst.TYPE_ID', '=', 'cpl_select_option.id')
                ->get();
        }
    }
    // fetch type name
    public function fetchTypeName(Request $request)
    {
        return DB::connection('pgsql')->table('cpl_select_option')->where('id', '=', $request->typeId)
            ->get('SELECT_OPTION_NAME');
    }
    // fetching case type_id
    public function fetchTypeID(Request $request)
    {
        // fetching all cases based on status
        return Irocase::where('CASE_ID', $request->caseId)->get('STATUS');
    }
    public function getTypeName(Request $request)
    {
        // fetching all cases based on status
        return DB::table('cpl_select_option')->where('id', $request->typeId)->get('SELECT_OPTION_NAME');
    }
    public function fetchDecisions(Request $request)
    {
        // fetching all cases based on status
        return DB::table('cpl_select_option')->where('TYPE', $request->decisiontype)->get('SELECT_OPTION_NAME');
    }
    // =========== fetching total cases ===============
    public function getOfficerTotalCases(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->whereIn('STATUS', [1, 8])
            ->where('AM_PNO', $request->username)->get();
        return $total->count();
    }

    // ========= fetching officers responses ===============
    public function getOfficerTotalResponses(Request $request)
    {
        // $username =$this->$request->username;
        $total = DB::connection('pgsql')->table('cpl_case_mst')->whereIn('STATUS', [2, -3, 7])
            ->where('AM_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }
    public function getOfficerTotalResponsed(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', 2)
            ->where('AM_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }
    public function getOfficerTotalNotResponded(Request $request)
    {
        // $username =$this->$request->username;
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', -3)
            ->where('AM_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }
    public function getOfficerTotalAwaitingResponse(Request $request)
    {
        // $username =$this->$request->username;
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', 7)
            ->where('AM_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }
    // chnaging the case status after taxpayer response
    public function changeResponseStatus(Request $request)
    {
        $this->validate($request, [
            'caseId' => 'required'
        ]);
        $action = 'Submiting Taxpayer Response Status';
        DB::beginTransaction();
        try {

            // inserting into audit trail
            iroAuditTrail::create([
                'CASE_ID' => $request->caseId,
                'CREATED_BY' => $request->username,
                'CLIENT_IP' =>$request->ip(),
                'ACTION' => $action
            ]);
            // update case mst table status to 3
            DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(['STATUS' => 2]);
            // if all is good
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return 'Something went wrong';
        }
    }
    // ======== fetching officers rejected cases=============
    public function getOfficerTotalRejected(Request $request)
    {
        // $username =$this->$request->username;
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', -1)
            ->where('AM_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }
    // ======== fetching forwarded cases=================
    public function getOfficerTotalForward(Request $request)
    {
        // $username =$this->$request->username;
        $total = DB::connection('pgsql')->table('cpl_case_mst')->whereIn('STATUS', [5, 6, 9])
            ->where('AM_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }

    //============ fetching TL_PNO's total cases=============
    public function getTotalTL_PNOCases(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', 3)
            ->where('TL_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }

    //============ fetching TL_PNO's reassign cases=============
    public function getTL_PNOReassignCases(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', 1)
            ->where('TL_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }
    // get officer wa tso na sm
    public function TsoassignCases(Request $request)
    {
        $data = explode('*', $request->data);
        $username = $data[0];
        $column = $data[1];

      $result = DB::connection('pgsql')->table('cpl_mapping_mst')
            ->where($column, $username)->get();
    $data1=[];
    foreach ($result as $row ) {
                $data1[] = $row->AM_PNO;
               
                $data12= array_unique($data1);
            }
       
        return $data12;
    }
    //============ fetching TL_PNO's total rejected cases=============
    public function getTL_PNOTotalRejected(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', -2)
            ->where('TL_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }
    public function getTL_PNOTotalDecisions(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->whereIn('STATUS', [5, 6, 9])
            ->where('TL_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }
    // ==============reporting AM_PNO====================
    // =================chief TL_PNO ===================

    //============ fetching chief TL_PNO's total cases=============
    public function getTotalChiefCases(Request $request)
    {

        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', 4)
            ->where('SM_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }

    //============ fetching chief TL_PNO's reassign cases=============
    public function getChiefReassignCases(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', 1)
            ->where('SM_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }
    //============ fetching chief TL_PNO's TOTAL INBOX=============
    public function getChiefTotalInbox(Request $request)
    {
        $total = DB::connection('pgsql')->table('iro_messaging')->where('STATUS', 0)
            ->where('TO', $request->username)->get();
        $count = $total->count();
        return $count;
    }
    //============ fetching chief TL_PNO's TOTAL DECISIONS=============
    public function getChiefTotalDecisions(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->whereIn('STATUS', [5, 6, 9])
            ->where('SM_PNO', $request->username)->get();
        $count = $total->count();
        return $count;
    }


    // fetching select options based on type
    public function fetchSelectOption(Request $request)
    {
        return iroSelect::where('TYPE', $request->type)->get();
    }
    // submitting report findings from response from taxpayer page
    public function submitReport(Request $request)
    {
        // validate fields
        $this->validate($request, [
            'caseId' => 'required',
            'username' => 'required',
            'Remarks' => 'required',
        ]);
        $action = "Response from taxpayer report submission";
            DB::beginTransaction();
            try {
                
                DB::table('cpl_remarks_mst')-> insert([
                    'REMARKS' => $request->Remarks,
                    'REMARKS_TYPE'=>$request->Reason,
                    'CREATED_BY_' => $request->username,
                    'CASE_ID' => $request->caseId,
                    'REASON_ID' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                // inserting into audit trail
                iroAuditTrail::create([
                    'CASE_ID' => $request->caseId,
                    'CREATED_BY' => $request->username,
                    'CLIENT_IP' =>$request->ip(),
                    'ACTION' => $action
                ]);
                // update periods mst table report final amount
                // \Log::info(["All TP response data:",$request->all()]);
                $finr_amount = $request->finr_amount;
                $options_r = $request->options_r;

                $sum = 0;
                foreach ($finr_amount as $key => $value) {
                    $sum += $value;
                    DB::table('cpl_periods_mst') ->where('CASE_ID', $request->caseId) ->where('id', $key)
                        ->update(['ASSESSMENT_AMOUNT' => $value, ]);
                }

                DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array(
                    'ASSESSMENT_AMOUNT' => $sum,
                ));

                foreach ($options_r as $key => $value) {
                    DB::table('cpl_periods_mst')->where('CASE_ID', $request->caseId)->where('id', $key)
                    ->update(['RECOMMENDATION' => $value,]);
                    // update case mst table status to 3
                DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array(
                    'STATUS' => 5,
                    'RECOMMENDATION' => $value,
                    'OBJECTION_GROUND' => $request->Reason,
                ));
                }
                
                
                // if all is good
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return 'Something went wrong';
            }
    }


    // Resubmitting report on sent back cases to TL_PNO
    public function ResubmitReport(Request $request)
    {
        // validate fields
        $this->validate($request, [
            'caseId' => 'required',
            'username' => 'required',
            'remarks' => 'required',
            'Option' => 'required',
            'Decision' => 'required'
        ]);
        $action = "Resubmitting Report to TL_PNO for Review";
        $filePath = '/uploads/' . $request->filename;
        $attStatus = 0;
        // check of file has been uploaded
        if ($request->filename) {
            //fully rejected
            if ($request->Decision == 'Fully accept') {
                $acceptedAmount = IroCase::where('CASE_ID', $request->caseId)->get('OBJECTION_AMOUNT');
                $acpt = $acceptedAmount[0]->OBJECTION_AMOUNT;
                //    begin transaction and rollback incase of error
                DB::beginTransaction();
                try {
                    // insert into attachment table
                    DB::table('cpl_attachement_mst')-> insert([
                        'ATTACHEMENT_NAME' => $request->filename,
                        'ATTACHEMENT_LINK' => $filePath,
                        'CASE_ID' => $request->caseId,
                        'ATTACHEMENT_STATUS' => $attStatus
                    ]);
                    // insert into remarks table
                    DB::table('cpl_remarks_mst')-> insert([
                        'REMARKS' => $request->remarks,
                        'REMARKS_TYPE'=>$request->reason,
                        'CREATED_BY_' => $request->username,
                        'CASE_ID' => $request->caseId,
                        'REASON_ID' => 0
                    ]);
                    // inserting into audit trail
                    iroAuditTrail::create([
                        'CASE_ID' => $request->caseId,
                        'CREATED_BY' => $request->username,
                        'CLIENT_IP' => $request->ip(),
                        'ACTION' => $action
                    ]);
                    // update case mst table status to 3
                    DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array(
                        'STATUS' => 3,
                        'TYPE_ID' => $request->Option,
                        'ASSESSMENT_BASIS' => $request->assessment_basis,
                        'IRO_RECOMMENDATION' => $request->iro_recommendation,
                        'DECISION_NAME' => $request->Decision,
                        'OBJECTION_GROUND' => $request->objection_ground,
                        'REJECTION_AMOUNT' => 0,
                        'AMOUNT_ACCEPTED' => $acpt
                    ));
                    // if all is good
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return 'Something went wrong';
                }
            }
            // fully rejected
            if ($request->Decision == 'Fully Reject') {
                //    begin transaction and rollback incase of error
                $objAmount = IroCase::where('CASE_ID', $request->caseId)->get('OBJECTION_AMOUNT');
                $objm = $objAmount[0]->OBJECTION_AMOUNT;
                DB::beginTransaction();
                try {
                    // insert into attachment table
                    DB::table('cpl_attachement_mst')-> insert([
                        'ATTACHEMENT_NAME' => $request->filename,
                        'ATTACHEMENT_LINK' => $filePath,
                        'CASE_ID' => $request->caseId,
                        'ATTACHEMENT_STATUS' => $attStatus
                    ]);
                    // insert into remarks table
                    DB::table('cpl_remarks_mst')-> insert([
                        'REMARKS' => $request->remarks,
                        'REMARKS_TYPE'=>$request->reason,
                        'CREATED_BY_' => $request->username,
                        'CASE_ID' => $request->caseId,
                        'REASON_ID' => 0
                    ]);
                    // inserting into audit trail
                    iroAuditTrail::create([
                        'CASE_ID' => $request->caseId,
                        'CLIENT_IP' => $request->ip(),
                        'CREATED_BY' => $request->username,
                        'ACTION' => $action
                    ]);
                    // update case mst table status to 3
                    DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array(
                        'STATUS' => 3,
                        'TYPE_ID' => $request->Option,
                        'ASSESSMENT_BASIS' => $request->assessment_basis,
                        'IRO_RECOMMENDATION' => $request->iro_recommendation,
                        'OBJECTION_GROUND' => $request->objection_ground,
                        'CURRENT_OBJECTION_AMOUNT' => 0,
                        'DECISION_NAME' => $request->Decision,

                        'REJECTION_AMOUNT' => $objm,
                        'AMOUNT_ACCEPTED' => 0
                    ));
                    // if all is good
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return 'Something went wrong';
                }
            }
            // partially accepted
            else {
                //    begin transaction and rollback incase of error
                $objAmount = IroCase::where('CASE_ID', $request->caseId)->get('OBJECTION_AMOUNT');
                $objm = $objAmount[0]->OBJECTION_AMOUNT;

                DB::beginTransaction();
                try {
                    // insert into attachment table
                    DB::table('cpl_attachement_mst')-> insert([
                        'ATTACHEMENT_NAME' => $request->filename,
                        'ATTACHEMENT_LINK' => $filePath,
                        'CASE_ID' => $request->caseId,
                        'ATTACHEMENT_STATUS' => $attStatus
                    ]);
                    // insert into remarks table
                    DB::table('cpl_remarks_mst')-> insert([
                        'REMARKS' => $request->remarks,
                        'REMARKS_TYPE'=>$request->reason,
                        'CREATED_BY_' => $request->username,
                        'CASE_ID' => $request->caseId,
                        'REASON_ID' => 0
                    ]);
                    // inserting into audit trail
                    iroAuditTrail::create([
                        'CASE_ID' => $request->caseId,
                        'CLIENT_IP' => $request->ip(),
                        'CREATED_BY' => $request->username,
                        'ACTION' => $action
                    ]);
                    // update case mst table status to 3
                    DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array(
                        'STATUS' => 3,
                        'TYPE_ID' => $request->Option,
                        'ASSESSMENT_BASIS' => $request->assessment_basis,
                        'IRO_RECOMMENDATION' => $request->iro_recommendation,
                        'OBJECTION_GROUND' => $request->objection_ground,
                        'CURRENT_OBJECTION_AMOUNT' => 0,
                        'REJECTION_AMOUNT' => $request->rejected_amount,
                        'DECISION_NAME' => $request->Decision,
                        'AMOUNT_ACCEPTED' => $request->amount_accepted
                    ));
                    // if all is good
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return 'Something went wrong';
                }
            }
        }
        // no file has been uploaded
        else {
            // no file exists
            //fully rejected
            if ($request->Decision == 'Fully accept') {
                $acceptedAmount = IroCase::where('CASE_ID', $request->caseId)->get('OBJECTION_AMOUNT');
                $acpt = $acceptedAmount[0]->OBJECTION_AMOUNT;
                //    begin transaction and rollback incase of error
                DB::beginTransaction();
                try {
                    // insert into remarks table
                    DB::table('cpl_remarks_mst')-> insert([
                        'REMARKS' => $request->remarks,
                        'REMARKS_TYPE'=>$request->reason,
                        'CREATED_BY_' => $request->username,
                        'CASE_ID' => $request->caseId,
                        'REASON_ID' => 0
                    ]);
                    // inserting into audit trail
                    iroAuditTrail::create([
                        'CASE_ID' => $request->caseId,
                        'CREATED_BY' => $request->username,
                        'CLIENT_IP' => $request->ip(),
                        'ACTION' => $action
                    ]);
                    // update case mst table status to 3
                    DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array(
                        'STATUS' => 3,
                        'TYPE_ID' => $request->Option,
                        'ASSESSMENT_BASIS' => $request->assessment_basis,
                        'IRO_RECOMMENDATION' => $request->iro_recommendation,
                        'DECISION_NAME' => $request->Decision,
                        'OBJECTION_GROUND' => $request->objection_ground,
                        'REJECTION_AMOUNT' => 0,
                        'AMOUNT_ACCEPTED' => $acpt
                    ));
                    // if all is good
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return 'Something went wrong';
                }
            }
            // fully rejected
            if ($request->Decision == 'Fully Reject') {
                //    begin transaction and rollback incase of error
                $objAmount = IroCase::where('CASE_ID', $request->caseId)->get('OBJECTION_AMOUNT');
                $objm = $objAmount[0]->OBJECTION_AMOUNT;
                DB::beginTransaction();
                try {

                    // insert into remarks table
                    DB::table('cpl_remarks_mst')-> insert([
                        'REMARKS' => $request->remarks,
                        'REMARKS_TYPE'=>$request->reason,
                        'CREATED_BY_' => $request->username,
                        'CASE_ID' => $request->caseId,
                        'REASON_ID' => 0
                    ]);
                    // inserting into audit trail
                    iroAuditTrail::create([
                        'CASE_ID' => $request->caseId,
                        'CLIENT_IP' => $request->ip(),
                        'CREATED_BY' => $request->username,
                        'ACTION' => $action
                    ]);
                    // update case mst table status to 3
                    DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array(
                        'STATUS' => 3,
                        'TYPE_ID' => $request->Option,
                        'ASSESSMENT_BASIS' => $request->assessment_basis,
                        'IRO_RECOMMENDATION' => $request->iro_recommendation,
                        'OBJECTION_GROUND' => $request->objection_ground,
                        'CURRENT_OBJECTION_AMOUNT' => 0,
                        'DECISION_NAME' => $request->Decision,

                        'REJECTION_AMOUNT' => $objm,
                        'AMOUNT_ACCEPTED' => 0
                    ));
                    // if all is good
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return 'Something went wrong';
                }
            }
            // partially accepted
            else {
                //    begin transaction and rollback incase of error
                $objAmount = IroCase::where('CASE_ID', $request->caseId)->get('OBJECTION_AMOUNT');
                $objm = $objAmount[0]->OBJECTION_AMOUNT;

                DB::beginTransaction();
                try {

                    // insert into remarks table
                    DB::table('cpl_remarks_mst')-> insert([
                        'REMARKS' => $request->remarks,
                        'REMARKS_TYPE'=>$request->reason,
                        'CREATED_BY_' => $request->username,
                        'CASE_ID' => $request->caseId,
                        'REASON_ID' => 0
                    ]);
                    // inserting into audit trail
                    iroAuditTrail::create([
                        'CASE_ID' => $request->caseId,
                        'CLIENT_IP' => $request->ip(),
                        'CREATED_BY' => $request->username,
                        'ACTION' => $action
                    ]);
                    // update case mst table status to 3
                    DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array(
                        'STATUS' => 3,
                        'TYPE_ID' => $request->Option,
                        'ASSESSMENT_BASIS' => $request->assessment_basis,
                        'IRO_RECOMMENDATION' => $request->iro_recommendation,
                        'OBJECTION_GROUND' => $request->objection_ground,
                        'CURRENT_OBJECTION_AMOUNT' => 0,
                        'REJECTION_AMOUNT' => $request->rejected_amount,
                        'DECISION_NAME' => $request->Decision,
                        'AMOUNT_ACCEPTED' => $request->amount_accepted
                    ));
                    // if all is good
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return 'Something went wrong';
                }
            }
        }
    }
    // Resubmitting report on sent back cases to TL_PNO
    public function ForwardCaseDecision(Request $request)
    {
        // validate fields
        $this->validate($request, [
            'caseId' => 'required',
            'username' => 'required',
            'remarks' => 'required',
            'Option' => 'required'
        ]);
        $action = "Re-submitting Report findings to TL_PNO for Review.";
        // check if file has been uploaded
        if ($request->filename) {
            // file path
            $filePath = '/uploads/' . $request->filename;
            $attStatus = 0;
            //    begin transaction and rollback incase of error
            DB::beginTransaction();
            try {
                // insert into attachment table
                DB::table('cpl_attachement_mst')-> insert([
                    'ATTACHEMENT_NAME' => $request->filename,
                    'ATTACHEMENT_LINK' => $filePath,
                    'CASE_ID' => $request->caseId,
                    'ATTACHEMENT_STATUS' => $attStatus
                ]);
                // insert into remarks table
                DB::table('cpl_remarks_mst')-> insert([
                    'REMARKS' => $request->remarks,
                    'REMARKS_TYPE'=>$request->reason,
                    'CREATED_BY_' => $request->username,
                    'CASE_ID' => $request->caseId,
                    'REASON_ID' => 0
                ]);
                // inserting into audit trail
                iroAuditTrail::create([
                    'CASE_ID' => $request->caseId,
                    'CREATED_BY' => $request->username,
                    'CLIENT_IP' =>$request->ip(),
                    'ACTION' => $action
                ]);
                // update case mst table status to 3
                DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(['STATUS' => 3, 'TYPE_ID' => $request->Option]);
                // if all is good
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return 'Something went wrong';
            }
        }
        //if no file has been uploaded, populate db without it
        else {
            //    begin transaction and rollback incase of error
            DB::beginTransaction();
            try {
                // insert into remarks table
                DB::table('cpl_remarks_mst')-> insert([
                    'REMARKS' => $request->remarks,
                    'REMARKS_TYPE'=>$request->reason,
                    'CREATED_BY_' => $request->username,
                    'CASE_ID' => $request->caseId,
                    'REASON_ID' => 0
                ]);
                // inserting into audit trail
                iroAuditTrail::create([
                    'CASE_ID' => $request->caseId,
                    'CREATED_BY' => $request->username,
                    'CLIENT_IP' =>$request->ip(),
                    'ACTION' => $action
                ]);
                // update case mst table status to 3
                DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(['STATUS' => 3, 'TYPE_ID' => $request->Option]);
                // if all is good
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return 'Something went wrong';
            }
        }
    }

    // fetch approval menu
    public function fetchApprovals(Request $request)
    {
        return iroSelect::where('TYPE', 'approval')->where('SELECT_OPTION_NAME', '!=', 'Forward to Chief TL_PNO')->get();
    }
    public function getForwardOption(Request $request)
    {
        return iroSelect::where('TYPE', 'approval')->where('SELECT_OPTION_NAME', '!=', 'Approve')
            ->where('SELECT_OPTION_NAME', '!=', 'Reject')->get();
    }
    public function fetchRejections(Request $request)
    {
        return iroSelect::where('TYPE', 'approval')->where('SELECT_OPTION_NAME', '!=', 'Approve')->get();
    }
    public function getOptionName(Request $request)
    {
        return iroSelect::where('SELECT_OPTION_NAME', '=', $request->optionName)->get('SELECT_OPTION_NAME');
    }

    // fetch reason menu
    public function fetchReasons(Request $request)
    {
        return DB::table('iro_select_option')->where('TYPE', $request->ReasonType)->get();
    }


    // =============== forward, approve and reject =================
    // approving case
    public function ApproveCase(Request $request)
    {
       
        $this->validate($request, [
            'username' => 'required',
        ]);
      
   
                 $status=$request->Mstatus?:$request->Astatus;
     

        // \Log::info(json_encode($status));

        $users = DB::connection('pgsql')->table('cpl_case_mst')
            ->where('CASE_ID', $request->caseId)
            ->get(['AM_PNO', 'TL_PNO', 'SM_PNO','TSO_PNO']);

        $AM_PNO = $users[0]->AM_PNO;
        $TL_PNO = $users[0]->TL_PNO;
        $SM_PNO = $users[0]->SM_PNO;
        $TSO_PNO=$users[0]->TSO_PNO;
    
        // \Log::info(json_encode($status));


        // fetching emails
        // $officerql = DB::connection('pgsql')->table('cpl_user_mst')
        //     ->where('USER_NAME', $AM_PNO)
        //     ->get(['EMAIL_ADDRESS']);
        // $officeemail = $officerql[0]->EMAIL_ADDRESS;

        // // // fetching TL_PNO email
        // $managerql = DB::connection('pgsql')->table('cpl_user_mst')
        //     ->where('USER_NAME', $TL_PNO)
        //     ->get(['EMAIL_ADDRESS']);
        // $mgtemail = $managerql[0]->EMAIL_ADDRESS;



        // // // fetching TL_PNO email
        // $cmql = DB::connection('pgsql')->table('cpl_user_mst')
        //     ->where('USER_NAME', $SM_PNO)
        //     ->get(['EMAIL_ADDRESS']);
        // $SM_PNOemail = $cmql[0]->EMAIL_ADDRESS;

        // // merging all email addresses into one array
        // $emails = [$officeemail, $mgtemail, $SM_PNOemail];

        // // fetching assessment no
        // $case = DB::connection('pgsql')->table('cpl_case_mst')
        //     ->where('CASE_ID', $request->caseId)
        //     ->get(['ASSESSMENT_NUMBER']);




        // $assessment_no = $case[0]->ASSESSMENT_NUMBER;
        // $action = "Case $request->caseId  and Assessment No. $assessment_no Approval";
            $action="Case approval";
        // $emaildata = [
        //     'message' => "Decison on Case $request->caseId and Assessment No. $assessment_no  has been Issued. by $request->username",
        //     'username' => $request->username
        // ];
       
  
        // if attachment exists
        if ($request->filename) {
            \Log::info(json_encode($request->filename));
            $action ="approval";
            // file path
           
            $attStatus = 0;
            
            // check if rejection id exists
            $result = DB::connection('pgsql')->table('cpl_case_mst')->where('CASE_ID', $request->caseId)->get();
            // run if rejection id is null
            if ($result[0]->REJECTION_ID == '') {
                // run if remarks is empty
                if (!$request->Remarks) {
                    //    begin transaction and rollback incase of error
                    DB::beginTransaction();
                    try {
                        // insert into attachment table
                        $datafile = (object) ['filename' => $request->filename];
                        foreach ($datafile->filename as $filename) {
                            $filePath = '/uploads/' . $filename;
                        
                        DB::table('cpl_attachement_mst')-> insert([
                            'ATTACHEMENT_NAME' => $request->filename,
                            'ATTACHEMENT_LINK' => $filePath,
                            'CASE_ID' => $request->caseId,
                            'ATTACHEMENT_STATUS' => $attStatus,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                        // inserting into audit trail
                        iroAuditTrail::create([
                            'CASE_ID' => $request->caseId,
                            'CREATED_BY' => $request->username,
                            'CLIENT_IP' =>$request->ip(),
                            'ACTION' => $action,
                      
                        ]);
                        // update case mst table status to 3
                        DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array('STATUS' => $status,
                        'updated_at'=>Carbon::now(),));
                        // if all is good
                        DB::commit();
                    } catch (\Exception $e) {
                        \Log::info($e);
                        DB::rollback();
                     
                        return 'Something went wrong';
                    }
                     // send notifiction mail

                    // Mail::to($emails)
                    // ->send(new DecisionIssued($emaildata));
      
                }
                // reun if remarks exists
                else {
                    //  \Log::info(json_encode("remarks"));
                    //    begin transaction and rollback incase of error
                    DB::beginTransaction();
                    try {
                        // insert into attachment table

                        $datafile = (object) ['filename' => $request->filename];
                        foreach ($datafile->filename as $filename) {
                            $filePath = '/uploads/' . $filename;
                  
                        DB::table('cpl_attachement_mst')-> insert([
                            'ATTACHEMENT_NAME' => $filename,
                            'ATTACHEMENT_LINK' => $filePath,
                            'CASE_ID' => $request->caseId,
                            'ATTACHEMENT_STATUS' => $attStatus,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                        // insert into remarks table
                        DB::table('cpl_remarks_mst')-> insert([
                            'REMARKS' => $request->remarks,
                            'REMARKS_TYPE'=>$request->reason,
                            'CREATED_BY_' => $request->username,
                            'CASE_ID' => $request->caseId,
                            'REASON_ID' => 0,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                        // inserting into audit trail
                        iroAuditTrail::create([
                            'CASE_ID' => $request->caseId,
                            'CREATED_BY' => $request->username,
                            'CLIENT_IP' =>$request->ip(),
                            'ACTION' => $action,
                           
                        ]);
                        // update case mst table status to 3
                        DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array('STATUS' => $status,
                        'updated_at'=>Carbon::now(),));
                        // if all is good
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return 'Something went wrong';
                    }
                    // Mail::to($emails)
                    // ->send(new DecisionIssued($emaildata));
                }
            }
            // remove the rejection_id
            else {
                // run if remarks is empty
                if (!$request->remarks) {
                    //    begin transaction and rollback incase of error
                    DB::beginTransaction();
                    try {
                        // insert into attachment table
                        $datafile = (object) ['filename' => $request->filename];
                        foreach ($datafile->filename as $filename) {
                            $filePath = '/uploads/' . $filename;
                        
                        DB::table('cpl_attachement_mst')-> insert([
                            'ATTACHEMENT_NAME' => $request->filename,
                            'ATTACHEMENT_LINK' => $filePath,
                            'CASE_ID' => $request->caseId,
                            'ATTACHEMENT_STATUS' => $attStatus,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                        // inserting into audit trail
                        iroAuditTrail::create([
                            'CASE_ID' => $request->caseId,
                            'CREATED_BY' => $request->username,
                            'CLIENT_IP' =>$request->ip(),
                            'ACTION' => $action,
                          
                        ]);
                        // update case mst table status to 3
                        DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array('STATUS' => $status,
                        'updated_at'=>Carbon::now(), 'REJECTION_ID' => null));
                        // if all is good
                        DB::commit();
                    } catch (\Exception $e) {
                        \Log::info($e);
                        DB::rollback();
                        return 'Something went wrong';
                    }
                    // Mail::to($emails)
                    // ->send(new DecisionIssued($emaildata));
                }
                // reun if remarks exists
                else {
                    //    begin transaction and rollback incase of error
                    DB::beginTransaction();
                    try {
                        // insert into attachment table
                        $datafile = (object) ['filename' => $request->filename];
                        foreach ($datafile->filename as $filename) {
                            $filePath = '/uploads/' . $filename;
                        
                        DB::table('cpl_attachement_mst')-> insert([
                            'ATTACHEMENT_NAME' => $request->filename,
                            'ATTACHEMENT_LINK' => $filePath,
                            'CASE_ID' => $request->caseId,
                            'ATTACHEMENT_STATUS' => $attStatus,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                        // insert into remarks table
                                DB::table('cpl_remarks_mst')-> insert([
                                    'REMARKS' => $request->remarks,
                                    'REMARKS_TYPE'=>$request->reason,
                                    'CREATED_BY_' => $request->username,
                                    'CASE_ID' => $request->caseId,
                                    'REASON_ID' => 0,
                                    'created_at'=>Carbon::now(),
                                    'updated_at'=>Carbon::now(),
                                ]);
                        // inserting into audit trail
                        iroAuditTrail::create([
                            'CASE_ID' => $request->caseId,
                            'CREATED_BY' => $request->username,
                            'CLIENT_IP' =>$request->ip(),
                            'ACTION' => $action,
                          
                        ]);
                        // update case mst table status to 3
                        DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array('STATUS' => $status,
                        'updated_at'=>Carbon::now(),));
                        // if all is good
                        DB::commit();
                    } catch (\Exception $e) {
                        \Log::info($e); 
                        DB::rollback();
                        return 'Something went wrong';
                    }
                    // Mail::to($emails)
                    // ->send(new DecisionIssued($emaildata));
                }
            }
        }




        // no file exists
        else {
            // check if rejection id exists
            // \Log::info('no remarks');
            $result = DB::connection('pgsql')->table('cpl_case_mst')->where('CASE_ID', $request->caseId)->get();
            // run if rejection id is null


            if ($result[0]->REJECTION_ID == '') {
                
                // run if remarks is empty
                if (!$request->remarks) {
                    // \Log::info('no remarks');
                    //    begin transaction and rollback incase of error
                    DB::beginTransaction();
                    try {

                        // inserting into audit trail
                        iroAuditTrail::create([
                            'CASE_ID' => $request->caseId,
                            'CREATED_BY' => $request->username,
                            'ACTION' => $action,
                        
                        ]);
                        // update case mst table status to 3
                        DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array('STATUS' => $status,
                       
                        'updated_at'=>Carbon::now(),));
                        // if all is good
                        DB::commit();
                    } catch (\Exception $e) {
                        \Log::info($e);
                        DB::rollback();
                        return 'Something went wrong';
                    }
                }
                // reun if remarks exists
                else {
                    //    begin transaction and rollback incase of error
                   
                    DB::beginTransaction();
                    try {

                        // insert into remarks table
                        DB::table('cpl_remarks_mst')-> insert([
                            'REMARKS' => $request->remarks,
                            'REMARKS_TYPE'=>$request->reason,
                            'CREATED_BY_' => $request->username,
                            'CASE_ID' => $request->caseId,
                            'REASON_ID' => 0,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                        // inserting into audit trail
                        iroAuditTrail::create([
                            'CASE_ID' => $request->caseId,
                            'CREATED_BY' => $request->username,
                            'ACTION' => $action,
                         
                        ]);
                        // update case mst table status to 
                       
                        DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array('STATUS' => $status,
                       
                        'updated_at'=>Carbon::now(),));
                        // if all is good
                        DB::commit();
                    } catch (\Exception $e) {
                        \Log::info($e);
                        DB::rollback();
                         
                        return 'Something went wrong';
                    }
                }
            }
            // remove the rejection_id
            else {
                // \Log::info("remove the rejection_id");
                // run if remarks is empty
                if (!$request->remarks) {
                    // \Log::info(json_encode( $request->all()));
                    //    begin transaction and rollback incase of error
                    DB::beginTransaction();
                    try {

                        // inserting into audit trail
                        iroAuditTrail::create([
                            'CASE_ID' => $request->caseId,
                            'CREATED_BY' => $request->username,
                            'ACTION' => $action,
                         
                        ]);
                        // update case mst table status to 3
                        DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array('STATUS' => $status, 
                        'REJECTION_ID' => null,
                        'updated_at'=>Carbon::now(),));
                        // if all is good
                        DB::commit();
                    } catch (\Exception $e) {
                        \Log::info($e);
                        DB::rollback();
                     
                        return 'Something went wrong';
                    }
                }
                // reun if remarks exists
                else {
                    // \Log::info(json_encode("approve TL"));
                    //    begin transaction and rollback incase of error
                    DB::beginTransaction();
                    try {
                        // \Log::info(json_encode( $request->all()));
                        // \Log::info(json_encode($request->Astatus));
                        // insert into remarks table
                        DB::table('cpl_remarks_mst')->insert([
                            'REMARKS' => $request->remarks,
                            'REMARKS_TYPE'=>$request->reason,
                            'CREATED_BY_' => $request->username,
                            'CASE_ID' => $request->caseId,
                            'REASON_ID' => 0,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                        // inserting into audit trail
                        iroAuditTrail::create([
                            'CASE_ID' => $request->caseId,
                            'CREATED_BY' => $request->username,
                            'ACTION' => $action,
                        
                        ]);
                
                      
                        // update case mst table status to 3
                        DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array('STATUS' => $status,
                        'updated_at'=>Carbon::now(),
                    ));
                        // if all is good
                        DB::commit();
                    } catch (\Exception $e) {
                        \Log::info($e);
                        DB::rollback();
                      
                        return 'Something went wrong';
                    }
                }
            }
        }
    }

    // rejecting case
    public function RejectCase(Request $request)
    {
        // \Log::info(json_encode( $request->all()));
        $this->validate($request, [
            'username' => 'required',
            'caseId' => 'required',
        ]);
        if ($request->Rstatus){

            $Rstatus=$request->Rstatus;
            
        }else if($request->RMstatus){

            $Rstatus=$request->RMstatus;
        }
        $action = 'Case Rejection';
        //    begin transaction and rollback incase of error
        DB::beginTransaction();
        try {
            // insert into remarks table
            DB::table('cpl_remarks_mst')->insert([
                'REMARKS' => $request->remarks,
                'REMARKS_TYPE'=>$request->reason,
                'CREATED_BY_' => $request->username,
                'CASE_ID' => $request->caseId,
                'REASON_ID' => 0,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            // inserting into audit trail
            iroAuditTrail::create([
                'CASE_ID' => $request->caseId,
                'CREATED_BY' => $request->username,
                'CLIENT_IP' =>$request->ip(),
                'ACTION' => $action,
           
            ]);
          
            // update case mst table status to 3
            DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array(
                'STATUS' => $Rstatus,
                'updated_at'=>Carbon::now(),
                // 'REJECTION_ID' => $request->reason
            ));
            // if all is good
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::info($e);
            return 'Something went wrong';
        }
    }
    public function compareAsmntAmnts(Request $request){
        $caseId = $request->caseId;
        $txpPin = Irocase::where('CASE_ID', $caseId)->value('TAXPAYER_ID');
        $periodsData = iroPeriods::where('CASE_ID', $caseId)->get(['YEAR_FROM','YEAR_TO','OBLIGATION_ID','REPORT_FIN_AMOUNT']);
        $itaxArray  = null;
        $periodDataArray = array();
        foreach ($periodsData as $period) {
            $yearFrom = $period->YEAR_FROM;
            $yearTo = $period->YEAR_TO;
            $date = new \DateTime($yearFrom);
            $dateto = new \DateTime($yearTo);
            $yearFromFormatted = $date->format('Y-m-d H:i:s');
            $yearToFormatted = $dateto->format('Y-m-d H:i:s');
            $obligationId = $period->OBLIGATION_ID;
            $reportFinAmount = $period->REPORT_FIN_AMOUNT;
    
            $itax = DB::connection('oracle')->table('EDW_ASSESEMENTS_MST')
                    ->where('TAX_PAYER_ID', $txpPin)
                    ->where('OBLIGATION_ID', $obligationId)
                    ->where('TRP_FROM_DT', $yearFromFormatted)
                    ->where('TRP_TO_DT', $yearToFormatted)
                    ->where('GROSS_TAX', $reportFinAmount)
                     ->get();
            $itaxArray[] = $itax;
        }
        return $itaxArray ;
    }

    // reassigned cases
    public function ReassignCase(Request $request)
    {
        $this->validate($request, [
            'cass'=> 'required',
            'officer' => 'required',
            'username' => 'required',
        ]);
        $action = 'Reassigning case to account manager ';
        //    begin transaction and rollback incase of error
        DB::beginTransaction();
        try {
    
            // Get officers from mst table 
            foreach($request->cass as $t ){
                // inserting into audit trail
                iroAuditTrail::create([
                    'CASE_ID' => $t,
                    'CREATED_BY' => $request->username,
                    'CLIENT_IP' =>$request->ip(),
                    'ACTION' => $action,
                
                ]);
            DB::table('cpl_case_mst')->where('CASE_ID', $t)->update([
                'updated_at'=>Carbon::now(),
                'AM_PNO' => $request->officer
            ]);
            // if all is good
            DB::commit();

            }

            return "worked";
        } catch (\Exception $e) {
            DB::rollback();
            return 'Something went wrong';
        }
    }
    //admin for assistance reassign case
    public function adminReassignCase(Request $request)
    {
        // $this->validate($request, [
        //     'caseId'=> 'required',
        //     'am' => 'required',
        //     'tl' => 'required',
        //     'sm' => 'required',
        //     'tso' => 'required',
        //     'username'

        // ]);
       
        $action = 'Case reassigning';
        //begin transaction and rollback incase of error
        DB::beginTransaction();
        try {
            // inserting into audit trail
            iroAuditTrail::create([
                'CASE_ID' => $request->caseId,
                'CREATED_BY' => $request->username,
                'CLIENT_IP' =>$request->ip(),
                'ACTION' => $action,
            
            ]); 
            
            // //check AM_PNO || TL_PNO || chief TL_PNO exist in mapping table
            $isAM= IroMapping::where('AM_PNO',$request->am)->get();
            $isTL = IroMapping::where('TL_PNO',$request->tl)->get();
            $isSM = IroMapping::where('SM_PNO',$request->sm)->get();
            $isTSO = IroMapping::where('TSO_PNO',$request->tso)->get();
          
            if ( $isAM->count()==0) {
                return response()->json([
                    'msg'=>'ACCOUNT MANAGER personal number was not found in the mapping! Kindly contact system admin for assistance.'
                ],401);
            } else if ( $isTL->count()==0) {
                return response()->json([
                    'msg'=>'TEAM LEAD personal number was not found in the mapping! Kindly contact system admin for assistance.'
                ],401);
            } else if ($isSM->count()==0) {
                return response()->json([
                    'msg'=>'SECTOR MANAGER personal number was found in the mapping! Kindly contact system admin for assistance.'
                ],401);
            }else if ($isTSO->count()==0) {
                return response()->json([
                    'msg'=>'TSO personal number was found in the mapping! Kindly contact system admin for assistance.'
                ],401);
            } 
            
            
            else {
              \Log::info ($request->all());
                if($request->role == 'Admin'){
               
                        // update cases table
                        DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update([
                            'AM_PNO'=>$request->am,
                            'TL_PNO'=>$request->tl,
                            'SM_PNO'=>$request->sm,
                            'TSO_PNO'=>$request->tso,
                            'STATUS'=>$request->status,
                            'updated_at'=>Carbon::now(),
                        ]);   

                    
                     
               
                }else{
                        // update cases table
                        DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update([
                            'AM_PNO'=>$request->am,
                            'TL_PNO'=>$request->tl,
                            'SM_PNO'=>$request->sm,
                            'TSO_PNO'=>$request->tso
                            
                        ]);   

                        // //get total cases from cases table
                        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('AM_PNO', $request->AM_PNO)->get();
                        $totalCases = $total->count();

                        //update mapping table
                        DB::table('cpl_mapping_mst')->where('AM_PNO', $request->AM_PNO)->update([
                            'AM_PNO'=>$request->am,
                            'TL_PNO'=>$request->tl,
                            'SM_PNO'=>$request->sm,
                            'TSO_PNO'=>$request->tso,
                            'updated_at'=>Carbon::now(),
                        ]);

                        $total1 = DB::connection('pgsql')->table('cpl_case_mst')->where('AM_PNO', $request->officer1)->get();
                        $totalCases1 = $total1->count(); 

                        DB::table('cpl_mapping_mst')->where('AM_PNO', $request->officer1)->update([
                            'AMOUNT' => $totalCases1,
                            'updated_at'=>Carbon::now(),
                        ]); 
                }
            }            
            // if all is good
            DB::commit();
        } catch (\Exception $e) {
            
            DB::rollback();
            return response()->json([
                        'msg'=>$e
                    ],401);
            // return 'Something went wrong';
        }
    }

    // forwading to SM_PNO
    public function ForwardCaseToSM_PNO(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'caseId' => 'required',
            'Fstatus' => 'required',
            'remarks' => 'required'
        ]);
        $action = "Forwarding  case of ID:$request->caseId and Assessment Number:$request->assessment_no to Chief TL_PNO";
        //    begin transaction and rollback incase of error
        DB::beginTransaction();
        try {
            // insert into remarks table
            DB::table('cpl_remarks_mst')-> insert([
                'REMARKS' => $request->remarks,
                'REMARKS_TYPE'=>$request->reason,
                'CREATED_BY_' => $request->username,
                'CASE_ID' => $request->caseId,
                'REASON_ID' => 0
            ]);
            // inserting into audit trail
            iroAuditTrail::create([
                'CASE_ID' => $request->caseId,
                'CLIENT_IP' =>$request->ip(),
                'CREATED_BY' => $request->username,
                'ACTION' => $action
            ]);
            // update case mst table status to 3
            DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(array('STATUS' => $request->Fstatus));
            // if all is good
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return 'Something went wrong';
        }
    }
    // =========== fetching total cases ===============
    public function getTotal(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', 2)->get();
        $count = $total->count();
        return $count;
    }
    // =========== fetching total cases ===============
    public function getAppealedTotal(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', 1)->get();
        $count = $total->count();
        return $count;
    }
    // =========== fetching total cases ===============
    public function getCompletedTotal(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', 9)->get();
        $count = $total->count();
        return $count;
    }
    // =========== fetching total cases ===============
    public function getReassignedTotal(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', 8)->get();
        $count = $total->count();
        return $count;
    }
    // =========== fetching total cases ===============
    public function getRejectedTotal(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where('STATUS', -1)->get();
        $count = $total->count();
        return $count;
    }
    // =========== fetching In progress ===============
    public function getInprogress(Request $request)
    {
        $total = DB::connection('pgsql')->table('cpl_case_mst')->where([
            ['STATUS', '=', 1],
            // ['STATUS','=', 2],
            // ['STATUS','=', 3],
            // ['STATUS','=', 4],
            // ['STATUS','=', 5],
            // ['STATUS','=', 6],
            // ['STATUS','=', 7],
            // ['STATUS','=', 8],
            // ['STATUS','=', 9]
        ])->get();


        $count = $total->count();
        return $count;
    }
    // =========== fetching total assigned ===============
    public function getAssigned(Request $request)
    {

        $total = DB::connection('pgsql')->table('cpl_case_mst')
            ->where([
                ['STATUS', '=', 1]
                // ,['STATUS','=', 8],
            ])
            ->get();

        $count = $total->count();
        return $count;
    }
    // =========== fetching total asgsigned ===============
    public function getDonData(Request $request)
    {

        $data = [$request->data];
        $data = explode('*', $request->data);
        $dstatus = $data[0];
        $AsStatus= explode(',', $dstatus);
        $other = $data[1];
        $Dother = explode(',', $other);
        $column = $Dother[0];
        $username = $Dother[1];
      
        Log::info($AsStatus);
        $assigned = DB::connection('pgsql')->table('cpl_case_mst')
            ->where($column, $username)
            ->whereIn('STATUS',[...$AsStatus])
            
            ->get();
        $countAssigned = $assigned->count();

        $inProgress = DB::connection('pgsql')->table('cpl_case_mst')
            ->where($column, $username)
            ->whereIn('STATUS', [1,-2,9,-5,2, 3, -3, -1, -2, 4, 5, 6, 7])
            ->get();
        $countProgress = $inProgress->count();

        $completedDecisionsMade = DB::connection('pgsql')->table('cpl_case_mst')
                    ->where($column,$username)
                    // ->whereIn('STATUS',[-4,5,6,9])
                    ->whereIn('STATUS',[11])
                    ->get();
         $count_completedDecisionsMade = $completedDecisionsMade->count();

        $seriesData = array($countAssigned, $countProgress, $count_completedDecisionsMade);
        return $seriesData;
    }
    // =========== fetching Progress Cases Report ===============
    public function getProgress(Request $request)
    {
        $data = [$request->data];
        $d = explode(',', $data[0]);
        $column = $d[0];
        $username = $d[1];
        return DB::connection('pgsql')->table('cpl_case_mst')
            ->where($column, $username)
            ->join('cpl_status_mst', 'cpl_case_mst.STATUS', '=', 'cpl_status_mst.STATUS_ID')
            ->get();
    }
    public function getCompletedCases(Request $request)
    {
        // $data=[$request->data];
        // $d=explode(',',$data[0]);
        // $column=$d[0];
        // $username=$d[1];
        $casesCompleted = DB::connection('pgsql')->table('cpl_case_mst')
            // ->where($column,$username)
            ->whereIn('STATUS', [9])
            ->get();

        return  $casesCompleted;
    }
    // =========== fetching total assigned ===============
    public function getBarData(Request $request)
    {
    
        $data = [$request->data];
        $d = explode(',', $data[0]);
        $column = $d[0];
        $username = $d[1];

        $accepted = DB::connection('pgsql')->table('cpl_case_mst')
            ->where($column,$username)
            ->get();
        $prelimAmount = $accepted->sum('PRELIM_REPORT_AMOUNT');

        $assment = DB::connection('pgsql')->table('cpl_case_mst')
            ->where($column,$username)
            ->get();
        $assmentAmount = $assment->sum('ASSESSMENT_AMOUNT');

        $objected = DB::connection('pgsql')->table('cpl_case_mst')
            ->where($column,$username)
            ->get();
            // $Finalamount = $objected->sum('REPORT_FIN_AMOUNT');
            $seriesData = array( $prelimAmount, $assmentAmount);
            return $seriesData;
    }

    //    get reason name for rejection
    public function getReasonName(Request $request)
    {
        // check if reason name exists
        if ($request->reasonId) {
            return iroSelect::where('id', $request->reasonId)->get('SELECT_OPTION_NAME');
        }
        // reason name does not exist
        else {
            // do nothing
        }
    }
}

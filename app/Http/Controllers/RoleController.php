<?php

namespace App\Http\Controllers;

use App\Models\iroRole;
use App\Models\iroAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use  Illuminate\Support\Str;
use Symfony\Contracts\Service\Attribute\Required;
use Carbon\Carbon;

class RoleController extends Controller
{
    //fetching all roles
    public function fetchRoles() {
        $roles = DB::table('cpl_role_mst')
        ->get()
        ->whereNotIn('ROLE_NAME',['Super','Admin']);
        return $roles;
        // return iroRole::whereIn('ROLE_NAME',['Admin','Super'])->get();
    
    }
    
    public function fetchRolesWithout() {
        return iroRole::whereNotIn('ROLE_NAME',['Super'])->get();
    }
    //store roles
    public function store(Request $request) {
      

        $this->validate($request,[
            'ROLE_NAME'=>'bail|required|unique:App\Models\iroRole',
            'roleDesc'=>'required',
        ]);
        
        $action ='Creating role';       
        $clientIp = $request->ip();
        $iid =Str::uuid();
      
        $username =$request->username;   
            $audit =iroAuditTrail::create([
                    'CASE_ID'=>$iid,
                    'ACTION'=>$action,
                    'CREATED_BY'=>$username,
                    'CLIENT_IP'=>$clientIp,
                
                ]);
   
                if($audit){
                    return iroRole::create([
                        'ROLE_NAME' =>$request->ROLE_NAME,
                        'ROLE_DESC' =>$request->roleDesc,
                        'CREATED_BY'=> $request-> username,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ]);
                }
                else{
                    return response()->json([
                        'msg'=>'Something went wrong'
                    ]);
                    
                }
                
       
    }
    public function updateRole(Request $request) {
       $this->validate($request,[
            'ROLE_NAME'=>'bail|required|unique:App\Models\iroRole',
            'roleDesc'=>'required',
            'id'=>'required',
        ]); 
        \Log::info(json_encode($request->ROLE_NAME));
        $action ='Updating role'; 
        $iid =Str::uuid();     
        $clientIp = $request->ip();
        $username =$request->username;

        $audit =iroAuditTrail::create([
            'ACTION'=>$action,
            'CASE_ID'=>$iid,
            'CREATED_BY'=>$username,
            'CLIENT_IP'=>$clientIp,
        
        ]);
            if ($audit) {
                return iroRole::where('id',$request->id)->update([
                    'ROLE_NAME' =>$request->ROLE_NAME,
                    'ROLE_DESC' =>$request->roleDesc,
                    'CREATED_BY'=> $request-> username,
                    'updated_at'=>Carbon::now(),
                ]);
            }
    }
}
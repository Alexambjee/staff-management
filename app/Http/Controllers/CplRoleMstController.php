<?php

namespace App\Http\Controllers;
// use Illuminate\Support\Facades\DB;
use App\Models\iroRole;
use App\Models\iroAuditTrail;
use Illuminate\Http\Request;
use App\Http\Requests\Storecpl_role_mstRequest;
use App\Http\Requests\Updatecpl_role_mstRequest;
use Carbon\Carbon;

class CplRoleMstController extends Controller
{
        //fetching all roles
        public function fetchRoles() {
            return iroRole::where('ROLE_NAME','=','Admin')
                        ->get();
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
            \Log::info(json_encode($request->ROLE_NAME));
            $action ='Creating role';       
            $clientIp = $request->ip();
            $username =$request->username;   
                $audit =iroAuditTrail::create([
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
            $action ='Updating role';      
            $clientIp = $request->ip();
            $username =$request->username;
    
            $audit =iroAuditTrail::create([
                'ACTION'=>$action,
                'CREATED_BY'=>$username,
                'CLIENT_IP'=>$clientIp,
         
            ]);
                if ($audit) {
                    return iroRole::where('id',$request->id)->update([
                        'ROLE_NAME' =>$request->ROLE_NAME,
                        'ROLE_DESC' =>$request->roleDesc,
                        'updated_at'=>Carbon::now(),
                    ]);
                }
        }
}

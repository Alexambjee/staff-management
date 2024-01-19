<?php

namespace App\Http\Controllers;

use App\Models\IroUser;
use App\Models\iroAuditTrail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Contracts\Service\Attribute\Required;
use  Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

class UserController extends Controller

{
    //Getting all users without condition
    public function getAllUsers()
    {

        $current_page = request()->input('page', 1);
        $items_per_page = 30;
        $skip = ($current_page - 1) * $items_per_page;

        $usersDetails = DB::table('cpl_role_mst')
            ->whereIn('ROLE_NAME',['Admin'])
            ->join('cpl_user_mst', 'cpl_role_mst.ROLE_ID', '=', 'cpl_user_mst.ROLE_ID')
            ->select('cpl_role_mst.ROLE_NAME', 'cpl_user_mst.*')
            ->orderBy('cpl_role_mst.created_at','ASC')
            ->skip($skip)->take($items_per_page)
            ->get();

        return $usersDetails;
    }
    public function getAllUsersWithout()
    {
        $current_page = request()->input('page', 1);
        $items_per_page = 30;
        $skip = ($current_page - 1) * $items_per_page;

        $usersDetails = DB::table('cpl_role_mst')
            ->whereNotIn('ROLE_NAME',['Super','Admin'])
            ->join('cpl_user_mst', 'cpl_role_mst.ROLE_ID', '=', 'cpl_user_mst.ROLE_ID')
            ->select('cpl_role_mst.ROLE_NAME', 'cpl_user_mst.*')
            ->orderBy('cpl_role_mst.created_at','ASC')
            ->skip($skip)->take($items_per_page)
            ->get();
        return $usersDetails;
    }
    //Getting user details with condition
    public function getUserInfoWhere(Request $request)
    {
        $name = $request->userName;
      
        // $userInfo = DB::table('HR_DATA')
        $userInfo = DB::connection('oracle')
            ->table('EDW_HR_DATA')
            ->select('USER_NAME', 'SURNAME', 'OTHERNAMES', 'ID_NUMBER', TRIM('EMAIL_ADDRESS'))
            ->where('USER_NAME', $name)
            ->get();
        if ($userInfo->count() == 0) {
            return response()->json([
                'msg' => 'Username submitted does not exist!'
            ], 401);
        } else {
            return $userInfo;
        }
    }
    //Creating user
    public function createUser(Request $request)
    {
        $this->validate($request, [
            'userName' => 'bail|required',
            'roleId' => 'bail|required',
            'firstName' => 'bail|required',
            'otherNames' => 'required',
            'email' => 'required|email',
            'nationalId' => 'required'
        ]);
    
       

        $action = 'Creating User';
        $caseId =Str::uuid();
        // \Log::info(json_encode($caseId));
        $clientIp = $request->ip();
        $username = $request->username;
        $domainName = 'T' . $request->nationalId;
        $status = 1;
        $user = IroUser::where('USER_NAME',$request->userName)->get();
        if($user->count() > 0){
            return response()->json([
                'msg'=>'A user with that Username already exists!'
            ],401);
        }
        else{
            $audit = iroAuditTrail::create([
                'CASE_ID'=>$caseId,
                'ACTION' => $action,
                'CREATED_BY' => $username,
                'CLIENT_IP' => $clientIp,
             
            ]);
            if ($audit) {
             return  IroUser::create([
                    'USER_NAME' => $request->userName,
                    'ROLE_ID' => $request->roleId,
                    'LAST_NAME' => $request->firstName,
                    'OTHER_NAMES' => $request->otherNames,
                    'EMAIL_ADDRESS' => $request->email,
                    'CREATED_BY' => $username,
                    'USER_ID' => $domainName,
                    'STATUS' => $status,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
                // return $addUser;
            }
            else{
                return response()->json([
                    'msg'=>'Something went wrong!'
                ]);
            }

        }


    }
    //Deactivating / activating user account
    public function activateDeactivateUser(Request $request)
    {
        $this->validate($request, [
            'action' => 'required',
            'reason' => 'required',
            'id' => 'required',
        ]);
        $action = 'User Activation/Deactivation';
        $clientIp = $request->ip();
       $objUniqueNum = new UniqueNumController();
       $caseId  = $objUniqueNum->generateUniqueNum('0123456789', 6, true);
      $username = $request->username;
        $audit = iroAuditTrail::create([
            'ACTION' => $action,
            'CLIENT_IP' => $clientIp,
            'CASE_ID'=> $caseId,
            'CREATED_BY'=>$username,
      
        ]);
        if ($audit) {
            return DB::table('cpl_user_mst')
                ->where('id', $request->id)
                ->update(['STATUS_REASON' => $request->reason, 'STATUS' => $request->action ,
                'updated_at'=>Carbon::now(),]);
        }
    }
    //Changing user role
    public function changeUserRole(Request $request)
    {
      
        $this->validate($request, [
            'roleId' => 'required',
            'id' => 'required',
        ]);

        \Log::info(json_encode($request->roleId));
        $action = 'User Activation/Deactivation';
        $clientIp = $request->ip();
        $username = $request->username;
        $audit = iroAuditTrail::create([
            'ACTION' => $action,
            'CREATED_BY' => $username,
            'CLIENT_IP' => $clientIp,
      
        ]);
        if ($audit) {
             return  DB::table('cpl_user_mst')
                ->where('id', $request->id)
                ->update(['ROLE_ID' => $request->roleId,
                'updated_at'=>Carbon::now(),]);

        }
    }
}

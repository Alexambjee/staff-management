<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\iroRole;
use App\Models\IroUser;
use Illuminate\Http\Request;
use App\Models\IroLoginTrail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
// use Illuminate\Contracts\Session\Session;
use Symfony\Component\HttpFoundation\Session\Session;
//exit();
class LoginController extends Controller
{
    // protecting routes
    public function Index(Request $request){
        return view('home');
    }
    // login function
    public function Login(Request $request ){
        // validate
        $this->validate($request,[
            'username' => 'bail|required|min:9',
            'password' => 'bail|required|'
        ]);

        $ldap_password = $request->password;
        $ldap_username = $request->username.'@kra.gov';
        $ldapconfig['host'] = '10.180.1.12';
        $ldapconfig['port'] = '389';
        // $ldap_connection = ldap_connect($ldapconfig['host'], $ldapconfig['port']);
      
        //  try{
            // user exists
            // ldap_bind($ldap_connection, $ldap_username, $ldap_password);
            try{

                $password = bcrypt($request->password);
                $userFind = IroUser::where('USER_ID',$request->username)->get();
                // it has not found user using USER_ID column
                if($userFind->count() == 0){
                    // try finding using USER_NAME column
                    $userFind2 = IroUser::where('USER_NAME',$request->username)->get();
                    if($userFind2->count() > 0){
                        IroUser::where('USER_NAME',$request->username)->update([
                            'password'=>$password
                        ]);
                    }
                    // return 'no user';
                }
                // user is found using USER_ID column
                else{
                    IroUser::where('USER_ID',$request->username)->update([
                        'password'=>$password
                    ]);
                }
            }

            catch (\Exception $e) {
                \Log::info($e);
                return response()->json([
                    'msg'=>`Something went wrong,$e`
                ],401);
            }

        //  }
        //  catch (\Exception $e) {
        //     \Log::info($e);
        //     return response()->json([
        //         'msg'=>'Invalid Credentials'
        //     ],401);
        // }
        \Log::info(json_encode($request->username));
       
        // first try to check match with USER_ID COLUMN and attempt login
        $firstTry = Auth::attempt(['USER_ID'=>$request->username,'password'=>$request->password]);
       
        if($firstTry){
            \Log::info(json_encode($firstTry));
            $user = Auth::user();
            session_start();
            $_SESSION['session'] = $user;
            // check if login trail has values
            $trailFind1 = IroLoginTrail::where('USER_NAME',$user->USER_NAME)
                            ->where('STATUS',1)
                            ->get();
            // if($trailFind1->count() == 0){
            //     // user does not exist in login trail then create trail
            //     IroLoginTrail::create(
            //         [
            //             'USER_NAME' =>$user->USER_NAME,
            //             'SESSION_ID' =>null,
            //             'STATUS' =>1,
            //             'LOGIN_TIME'=>now(),
            //             'LOCAL_IP' =>$request->ip()
            //         ]);
            // }
            // // user is not logged in create logon trail
            // else{
            //     session_destroy();
            //     Auth::logout();
            //     return response()->json([
            //         'msg'=>'Access Denied. Your account has been logged
            //             in somewhere else kindly logout first then try again!',
            //     ],401);}
        }
        // first try user not found try second and attempt login
        else{
            $secondTry = Auth::attempt(['USER_NAME'=>$request->username,'password'=>$request->password]);
            if($secondTry){
                $user = Auth::user();
                session_start();
                $_SESSION['session'] = $user;
                // check if login trail has values
                $trailFind2 = IroLoginTrail::where('USER_NAME',$user->USER_NAME)
                                ->where('STATUS',1)
                                ->get();
                // if($trailFind2->count() == 0){
                //     // user does not exist in login trail then create trail
                 
                //     IroLoginTrail::create(
                //         [
                //             'USER_NAME' =>$user->USER_NAME,
                //             'SESSION_ID' =>null,
                //             'STATUS' =>1,
                //             'LOGIN_TIME'=>now(),
                //             'LOCAL_IP' =>$request->ip()
                //         ]);
                // }
                // // user is not logged in create logon trail
                // else{
                //     // check if status column is 1 
                //     // if($trailFind2[0]->STATUS ==1 ){
                //     session_destroy();
                //     Auth::logout();
                //     return response()->json([
                //         'msg'=>'Access Denied. Your account has been logged
                //             in somewhere else kindly logout first then try again!',
                //         'proceed'=>'proceed'

                //     ],401);
    
                //     }
                // }

            }
        }

        $role = $user->role;
        \Log::info(json_encode($user));
        // ->ROLE_NAME;
        // return $user->STATUS;
        // check user account status
            // check if user is active
        
        if($user->STATUS == 0){
            session_destroy();
            return response()->json([
                'msg'=>'Access Denied!Your Account is Inactive,  Kindly Contact your Administrator.'
            ],401);
        }
        // // user is active proceed
        else{
                return response()->json([
                    'msg'=>"Hi, $user->LAST_NAME, Welcome back.",
                    'user'=>$user,
                    'role'=>$role,
                ]);
        }
}

public function Logout(Request $request){
        // return 'hello';
        // return $request->Cookie();
        Cookie::forget('PHPSESSID');
        Cookie::forget('XSRF-TOKEN');
        Cookie::forget('iroinsight_session');
       
            // unset($_COOKIE['XSRF-TOKEN']); 
            // unset($_COOKIE['iroinsight_session']);
            // unset($_COOKIE['PHPSESSID']);
            // setcookie('session', null, -1, '/'); 
            
       
        // terminate session using user ip address

        IroLoginTrail::where('LOCAL_IP',$request->ip())
                    ->where('STATUS',1)
                    ->update([
                    'LOGOUT_TIME'=>now(),
                    'STATUS'=>0
                ]);
        return redirect('/');
    }
}
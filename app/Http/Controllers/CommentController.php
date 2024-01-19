<?php

namespace App\Http\Controllers;

use App\Models\iroComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CommentController extends Controller
{
    // fetching comments based on caseID
    public function getComments(Request $request){
        return DB::connection('pgsql')->table('cpl_remarks_mst')->where('CASE_ID','=',$request->caseId)
                ->join('cpl_user_mst','cpl_remarks_mst.CREATED_BY_','=','cpl_user_mst.USER_NAME')
                ->join('cpl_role_mst','cpl_user_mst.ROLE_ID','=','cpl_role_mst.ROLE_ID')
                ->orderBy('cpl_remarks_mst.created_at','desc')
                ->get();
    }
}

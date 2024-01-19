<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AuditTrailController extends Controller
{
    //Get audit trail
    public function getAuditTrail() {
        $auditTrail = DB::table('cpl_audit_trail')->select('*')->orderBy('created_at', 'DESC')->get();
        return $auditTrail; 
    }
}

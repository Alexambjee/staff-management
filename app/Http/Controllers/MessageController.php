<?php

namespace App\Http\Controllers;

// use Mail;
use Carbon\Carbon;
use App\Models\IroUser;
use App\Models\iroSelect;
use App\Models\attachment;
use App\Mail\emailTaxpayer;
use App\Mail\emailRecipient;
use App\Mail\InvalidateCase;
use App\Models\iroMessaging;
use Illuminate\Http\Request;
use App\Models\iroAuditTrail;
use App\Models\iroAttachement;
use App\Http\Controllers\NewPdfController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    //user all messages using respective username
    public function fetchMessages(Request $request)
    {
        return iroMessaging::where('TO', $request->username)->get();
    }
    public function getMessageUsers(Request $request)
    {
        return IroUser::where('USER_NAME', '!=', $request->username)
            ->where('STATUS', 1)
            ->join('cpl_user_role', 'cpl_user_mst.ROLE_ID', '=', 'cpl_user_role.ROLE_ID')
            ->get();
    }
    // fetching taxpayer to officer message communication using case_id
    public function fetchTaxpayerMmessages(Request $request)
    {

        // return DB::table('iro_messaging')->where('CASE_ID','=',$request->caseId)
        // ->join('iro_attachement_mst', 'iro_messaging.ATTACHEMENT_ID', '=', 'iro_attachement_mst.ATTACHEMENT_ID')
        // ->get();
        return iroMessaging::where('CASE_ID', $request->caseId)->get();
    }



    public function contactTaxpayer(Request $request)
    {
        $this->validate($request, [
            'taxpayerEmail' => 'required|email',
            'caseId' => 'required',
            'body' => 'required',
        ]);

        
        // fetching user email
        $officerql = DB::connection('pgsql')->table('cpl_user_mst')
            ->where('USER_NAME', $request->username)
            ->get(['EMAIL_ADDRESS']);
        $officeemail = $officerql[0]->EMAIL_ADDRESS;
        // fetching taxpayer pin
        $tpinql = DB::connection('pgsql')->table('cpl_case_mst')
            ->where('CASE_ID', $request->caseId)
            ->get(['PIN_NO']);
        $tpin = strtoupper($tpinql[0]->PIN_NO);

        $type = "taxpayerContact";
        $action = 'Contacting Taxpayer through email notification';

        // setting date at which taxpayer is contacted and neeeds to response +14 days
        $current_date = now();
        $contacted_date  = date_add($current_date, date_interval_create_from_date_string("14 days"));

        $upload_genPath = NewPdfController::generateContactTaxpayerPdf($request->caseId, true);
        $upload_genName = basename($upload_genPath);

        // add generated file into the attachments table
        $upload_gen = iroAttachement::create([
            'ATTACHEMENT_NAME' => $upload_genName,
            'ATTACHEMENT_LINK' => $upload_genPath,
            'CASE_ID' => $request->caseId,
            'ATTACHMENT_STATUS' => '0'
        ]);

        $upload_genId = $upload_gen['id'];
        $upload_genPdf = iroAttachement::where(['id' => $upload_genId])->first();
        $autogenName = '/upload_gen/'.$upload_genPdf['ATTACHEMENT_NAME'];
        $autogenPath = public_path().$autogenName;
        $autogenFile = file_get_contents($autogenPath);
        // checking for path
        $file_xst = $request->filename ? 'true' : 'false';
        $maildata = [
            'subject' => $request->subject,
            'body' => $request->body,
            'username' => $request->username,
            'tpin' => $tpin,
            'attached_file' => $autogenFile
        ];
        if ($file_xst == 'true') {
            // defining attachment variables
            \Log::info(["Filename",$request->filename]);
            $filePath = '/uploads/' . $request->filename[0];
            $Path = public_path() . $filePath;

            // $file = file_get_contents($Path);
            // defining maildata
            $maildata['filename'] =  $Path;
        }

        // \Log::info("Email data:", $maildata);

        $attStatus = 0;

        // begin transaction and rollback incase of error
        DB::beginTransaction();
        try {
            // insert into attachment table
            $isAttached = iroAttachement::where('CASE_ID', $request->caseId)->exists() ? 'true' : 'false';
            if ($isAttached === 'true') {
                $file = $request->filename;
                iroAttachement::where('CASE_ID', $request->caseId)->update(['updated_at' => Carbon::now()]);
            } else {
                iroAttachement::create([
                    'ATTACHEMENT_NAME' => $request->filename,
                    'ATTACHEMENT_LINK' => $filePath,
                    'CASE_ID' => $request->caseId,
                    'ATTACHEMENT_STATUS' => $attStatus
                ]);
            }

            // insert into messaging table
            $message = iroMessaging::create([
                'SUBJECT' => $request->subject,
                'BODY' => $request->body,
                'TYPE' => $type,
                'CASE_ID' => $request->caseId,
                'ATTACHMENT_ID' => null,
                'FROM' => $officeemail,
                'TO' => $request->taxpayerEmail,
                'STATUS' => 0,
            ]);
            // inserting into audit trail 
            iroAuditTrail::create([
                'CASE_ID' => $request->caseId,
                'CREATED_BY' => $request->username,
                'CLIENT_IP' => $request->ip(),
                'ACTION' => $action
            ]);
            // update case mst table
            DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update([
                'STATUS' => 20,
                'RESPONSE_ON' => $contacted_date,
                'CONTACTED_ON' => now()
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return 'Something went wrong';
        }

        // sending with cc if cc exists
        if ($request->cc) {
            return Mail::to($request->taxpayerEmail)
                ->cc($request->cc)
                ->send(new emailTaxpayer($maildata));
        }
        // send without cc 
        else {
            return Mail::to($request->taxpayerEmail)
                ->send(new emailTaxpayer($maildata));
        }
    }
    // invalidate case
    public function invalidateCase(Request $request)
    {
        // validate file
        $this->validate($request, [
            'taxpayerEmail' => 'required|email',
            'caseId' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);
        // fetching user email
        $officerql = DB::connection('pgsql')->table('cpl_user_mst')
            ->where('USER_NAME', $request->username)
            ->get(['EMAIL_ADDRESS', 'OTHER_NAMES']);
        $officeemail = $officerql[0]->EMAIL_ADDRESS;
        $officename = $officerql[0]->OTHER_NAMES;
        $maildata = [
            'subject' => $request->subject,
            'message' => $request->body,
            'username' => $officename,
            'useremail' => $officeemail
        ];
        // sending with cc if cc exists


        $type = "case-Invalidation";
        $action = 'Invalidating case and contacting taxpayer and assemment officer';
        // setting date at which taxpayer is contacted

        //    begin transaction and rollback incase of error
        DB::beginTransaction();
        try {
            // insert into messaging table
            iroMessaging::create([
                'SUBJECT' => $request->subject,
                'BODY' => $request->body,
                'TYPE' => $type,
                'CASE_ID' => $request->caseId,
                'ATTACHMENT_ID' => null,
                'FROM' => $request->username,
                'TO' => $request->taxpayerEmail,
                'STATUS' => 0,
            ]);
            // // inserting into audit trail
            iroAuditTrail::create([
                'CASE_ID' => $request->caseId,
                'CREATED_BY' => $request->username,
                'CLIENT_IP' => $request->ip(),
                'ACTION' => $action
            ]);
            // update case mst table
            DB::table('cpl_case_mst')->where('CASE_ID', $request->caseId)->update(['STATUS' => 7]);
            // // all good
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return 'Something went wrong';
        }
        // send mail
        if ($request->cc) {
            return Mail::to($request->taxpayerEmail)
                ->cc($request->cc)
                ->send(new InvalidateCase($maildata));
        }
        // send without cc 
        else {
            return Mail::to($request->taxpayerEmail)
                ->send(new InvalidateCase($maildata));
        }
    }

    // compose message
    public function composeMail(Request $request)
    {
        // validate file
        $this->validate($request, [
            'to' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'username' => 'required'
        ]);

        $to = $request->to;
        $action = "Sending message to $to";
        $data = $request;
        DB::beginTransaction();
        try {

            // inserting into audit trail
            // iroAuditTrail::create([
            //     'CREATED_BY' => $request->username,
            //     'CLIENT_IP'=>$request->ip(),
            //     'ACTION' => $action,
            //     'CASE_ID'=>''
            // ]);
            //  insert into messaging table
            iroMessaging::create([
                'TO' => $request->to,
                'FROM' => $request->username,
                'CASE_ID' => '',
                'ATTACHMENT_ID' => '',
                'TYPE' => 'System Messaging',
                'STATUS' => 0,
                'SUBJECT' => $request->subject,
                'BODY' => $request->message
            ]);

            // if all is good
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return 'Something went wrong.Failed to send message';
        }
        return $data;

        // \Log::info(json_decode($data));
    }

    public function composeMail1(Request $request)
    {
        try {
            $this->validate($request, [
                'to' => 'required',
                'subject' => 'required',
                'message' => 'required',
                'username' => 'required'
            ]);

            $to = $request->to;
            $action = "Sending message to $to";
            $data = $request;

            DB::beginTransaction();

            // inserting into audit trail
            // iroAuditTrail::create([
            //     'CREATED_BY' => $request->username,
            //     'CLIENT_IP'=>$request->ip(),
            //     'ACTION' => $action,
            //     'CASE_ID'=>''
            // ]);

            // insert into messaging table
            iroMessaging::create([
                'TO' => $request->to,
                'FROM' => $request->username,
                'CASE_ID' => '',
                'ATTACHMENT_ID' => '',
                'TYPE' => 'System Messaging',
                'STATUS' => 0,
                'SUBJECT' => $request->subject,
                'BODY' => $request->message
            ]);

            // if all is good
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Something went wrong. Failed to send message'], 422);
        }

        return response()->json(['message' => 'Message sent successfully']);
    }


    // reply message
    public function replyMail(Request $request)
    {
        // validate file
        $this->validate($request, [
            'to' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'username' => 'required'
        ]);

        $to = $request->to;
        $action = "Replying message to $to";
        DB::beginTransaction();
        try {

            // inserting into audit trail
            iroAuditTrail::create([
                'CREATED_BY' => $request->username,
                'CLIENT_IP' => $request->ip(),
                'ACTION' => $action,
                'CASE_ID' => ''
            ]);
            //  insert into messaging table
            iroMessaging::create([
                'TO' => $request->to,
                'FROM' => $request->username,
                'CASE_ID' => '',
                'ATTACHMENT_ID' => '',
                'TYPE' => 'System Messaging',
                'STATUS' => 0,
                'SUBJECT' => $request->subject,
                'BODY' => $request->message
            ]);

            // if all is good
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return 'Something went wrong.Failed to send message';
        }
    }

    public function unreadMessages(Request $request)
    {
        $total = iroMessaging::where('TO', $request->username)->where('STATUS', '=', 0)->get();
        $count = $total->count();
        return $count;
    }
    // user total messages
    public function getUserTotalMessages(Request $request)
    {
        $total = iroMessaging::where('TO', $request->username)->where('STATUS', '=', 0)->get();
        $count = $total->count();
        return $count;
    }
    public function changeMessageStatus(Request $request)
    {
        return iroMessaging::where('id', $request->id)->update(['STATUS' => 1]);
    }
}

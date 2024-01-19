<?php

namespace App\Http\Controllers;

use App\Models\iroAttachement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;
use Illuminate\Support\Str;

use Carbon\Carbon;
class AttachmentController extends Controller
{    // upload attachment
    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|max:210000'
        ]);

        $filenames = [];
    try {
        if (is_array($request->file)) {
            foreach ($request->file as $file) {
                $fileName = time() . '.' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $fileName);
                $filenames[] = $fileName;
            }
        } else {
            $fileName = time() . '.' . $request->file->getClientOriginalName();
            $request->file->move(public_path('uploads'), $fileName);
            $filenames[] = $fileName;
        }
        \Log::info($filenames);
        return response()->json(['filenames' => $filenames]);
    } catch (\Throwable $th) {
        return  $th;
    }
      
    }
    // fetch attachments based on caseid
    public function fetchAttachments(Request $request)
    {
       
        return iroAttachement::where('CASE_ID', $request->caseId)
            // ->orderBy('created_at', 'desc')
            ->get();
    }


    public function getTaxpayerAttachments(Request $request)
    {
        // $assmnt='KRA201815359264';
        return $request->assmtNo;
        $data = DB::connection('oracle')
            ->table('IRO_DATA_ATT')
            ->where('ASSMT_NO', $request->tPin)
            ->get();
        return $data;
    }
    public function deleteAttachment($caseId, $attachmentId){
        try {
            //code...
            // Find attachment from DB
            $attachment = iroAttachement::where('CASE_ID', $caseId)->find($attachmentId);
            $attached = $attachment->ATTACHEMENT_LINK;
            $file = Str::of($attached)->replaceFirst('/', '');
            // Delete attachment from public storage
            unlink(public_path($file));
            // Delete attachement from DB
            $attachment->delete();
            return response()->json(['message'=>'Attachment deleted successfuly!']);
        } catch (\Exception  $er) {
            \Log::info($er);
            return 'Ooops! Failed to delete attachment.';
        }
    }
}
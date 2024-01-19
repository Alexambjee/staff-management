<?php

namespace App\Http\Controllers;

use App\Models\IroCase;
use App\Models\IroObligations;
use App\Models\IroPeriods;
use App\Models\IroUser;
use App\Http\Controllers\PdfController;
// use App\Models\EcmuEntry;
use App\Pdfs\ContactTaxpayerPdf;
use Illuminate\Http\Request;

class NewPdfController extends Controller
{

    public function printContactTaxpayer(Request $request)
    {
        $caseId = $request->caseId;
        self::generateContactTaxpayerPdf($caseId, false);
    }

    static function generateContactTaxpayerPdf(String $caseId, Bool $saveFile)
    {
        $cplData = IroCase::where('CASE_ID', $caseId)
        ->addSelect([
            'OBLIGATION_NAME' => IroObligations::select('OBLIGATION_NAME')
              ->whereColumn('cpl_case_mst.OBLIGATION_ID', 'cpl_obligation_mst.OBLIGATION_ID')
          ])
          ->addSelect([
            'AM_EMAIL' => IroUser::select('EMAIL_ADDRESS')
              ->whereColumn('cpl_case_mst.AM_PNO', 'cpl_user_mst.USER_NAME')
          ])
          ->addSelect([
            'AM_NAME' => IroUser::select('OTHER_NAMES')
              ->whereColumn('cpl_case_mst.AM_PNO', 'cpl_user_mst.USER_NAME')
          ])
            ->first();

        if (!$cplData) {
            abort(404, 'Case data not found');
        }

        $periods = IroPeriods::where('CASE_ID',$caseId)
        ->addSelect([
            'OBLIGATION_NAME' => IroObligations::select('OBLIGATION_NAME')
              ->whereColumn('cpl_periods_mst.OBLIGATION_ID', 'cpl_obligation_mst.OBLIGATION_ID')
          ])
        ->get();

        $savePath = null;

        if ($saveFile) {
            // generate unique filename
            $uniquePart = (new PdfController)->generateUniqId('0123456789', 15);
            $filename = "$caseId-contact-$uniquePart.pdf";
            $folderName = '/upload_gen';
            $savePath = strtolower("$folderName/$filename");
        }

        $pdfInstance = new ContactTaxpayerPdf();
        $pdfInstance->getOutput($cplData, $periods, $savePath);
        return $savePath;
    }
}


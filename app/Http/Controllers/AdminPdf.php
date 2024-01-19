<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Irocase;
use Illuminate\Support\Facades\DB;
class AdminPdf extends Controller
{
   
    protected $fpdf;
    // protected $code;

    public function __construct()
    {
        $this->fpdf = new Fpdf;
        // $this->code = new code128;
     
    }

     
    function generateUniqId($c, $l, $u = FALSE) {
        if (!$u) for ($s = '', $i = 0, $z = strlen($c)-1; $i < $l; $x = rand(0,$z), $s .= $c[$x], $i++);
        else for ($i = 0, $z = strlen($c)-1, $s = $c[rand(0,$z)], $i = 1; $i != $l; $x = rand(0,$z), $s .= $c[$x], $s = ($s[$i] == $s[$i-1] ? substr($s,0,-1) : $s), $i=strlen($s));
        return $s;
        }     

    public function printPDF(Request $request) 
    {
    
        $info=[$request->action];
        // $d=explode(',',$info[0]);
        $action=$info[0];
        // $case_id=$d[0];
        $data=[];
        $data= DB::table('cpl_case_mst')
            ->join('cpl_station_mst', 'cpl_case_mst.STATION_ID', '=', 'cpl_station_mst.id')
            ->join('cpl_status_mst', 'cpl_case_mst.STATUS', '=', 'cpl_status_mst.STATUS_ID')        
         ->get();

         
         foreach($data as $datum){
                $datum = get_object_vars($datum);
                \Log::info([$datum]);
                $caseId=$datum['CASE_ID'];
                $caseType=$datum['ASSESSMENT_ID'];
                //$createdBy=$datum['UPLOADED_BY'];
                $dateCreated=$datum['created_at'];
                $dateUpdated=$datum['updated_at'];
                $status=$datum['STATUS_DESCRIPTION'];
                $tPin=$datum['PIN_NO'];
                $tName=$datum['TAXPAYER_NAME'];
                $tStation=$datum['STATION_NAME'];
                $tTel=$datum['TP_PHONE'];
                $tEmail=$datum['TP_EMAIL'];
                //$objDate=$datum['DATE_OF_OBJECTION'];
                $taxHead=$datum['STATION_NAME'];
                //$objType=$datum['OBJECTION_TYPE'];
                //$objReason=$datum['REASON_FOR_OBJECTION'];
                $objAmount=$datum['REPORT_FIN_AMOUNT'];
                $assNumber=$datum['ASSESSMENT_ID'];
                $assPeriod=$datum['RECOMMENDATION'];
                $assAmount=$datum['ASSESSMENT_AMOUNT'];
                //$assOfficer=$datum['ASSESSMENT_OFFICER'];
                //$department=$datum['DEPARTMENT'];
                $date1 = date("dmY");
                $date2 = date('H');
                $date3 = date('is');    
                $date4 = date('Ymd');
                $date5 = date('is');
                $noticeCode='KRA'.$date4.$this->generateUniqId('0123456789',6,TRUE);
                // $code=!! DNS1D::getBarcodeHTML("1noticeCode", 'C39') !!;
        }
       
    
         

        $this->fpdf->AddPage("L", ['200', '200']);
        $this->fpdf->SetFont('Times','B','16');
        $this->fpdf->SetDrawColor(50,50,100);
        $this->fpdf->SetFillColor(178, 190, 181);
        $this->fpdf->Image(public_path().'/images/kra.logo.png',0,5,70,0,'PNG');  
        $this->fpdf->SetFont('Times','B','16');     
        $this->fpdf->Text(70,15,'PROGRESS REPORT');
        $this->fpdf->SetFont('Times','B',8,'R');
        $this->fpdf->Text(150,10,'For General Tax Questions');
        $this->fpdf->Text(150,13,'Contact KRA Call Centre');
        $this->fpdf->Text(150,16,'Tel: +254 (020) 4999 999');
        $this->fpdf->Text(150,19,'Cell: +254(0711)099 999');
        $this->fpdf->Text(150,22,'Email: callcentre@kra.go.ke');
        $this->fpdf->Text(150,25,'www.kra.go.ke');
       
        $date = $date1.$date2.$date3;
        $reportNo = 'KRA/CPL/'.$date4.$date2.$date5.'/'.$tPin;
        $this->fpdf->Ln(13);
        // $this->fpdf->SetFont('Times','B',14);
        // $this->fpdf->Cell(190,5,$rTitle,0,0,'C');
        // $this->fpdf->Ln(2);
        // $this->fpdf->SetFont('Times','',12);
        // $this->fpdf->Cell(190,10,$cpage,'',1,'C');
        $this->fpdf->SetFont('Times','B',8);
        $this->fpdf->Cell(190,0,'S/No:'.$reportNo,0,0,'C');
        $this->fpdf->SetFont('Arial','B','8');
        $this->fpdf->Ln(-1);
        $this->fpdf->Cell(180,5,'________________________________________________________________________________________________________________________','',1,'C');       
        $this->fpdf->Ln(10);

        //$this->fpdf->Cell(180, 8,'Case Progress',1,1,'C',true);
       
       
       
         //$this->fpdf->Row(Array(
         //    "Period From",
         //    "Vehicle Type",
         //    "Reg Number",
         //    "Brand",
         //    "Model",
         //    "Cargo or Passenger Capacity",
         //    "Advance Tax Payable",
         //    "Amended",
         //    "Cancelled", 
         //    "Amount Paid",
         // ));

        // $this->fpdf->SetFont('Times','B','8');
        // $this->fpdf->SetDrawColor(50,50,100);
        // $this->fpdf->SetFillColor(178, 190, 181);
        // $this->fpdf->SetFont('Times','B','8');
        // $this->fpdf->Cell(10,8,'Period From','1',0,'C');       
        // $this->fpdf->SetFont('Times','B','8');
        // $this->fpdf->Cell(28,8,'Case Type','1',0,'C');
        // $this->fpdf->SetFillColor(178, 190, 181);
        // $this->fpdf->SetFont('Times','B','8');
        // $this->fpdf->Cell(28,8,'Reg Number','1',0,'C');
        // $this->fpdf->SetFillColor(178, 190, 181);
        // $this->fpdf->SetFont('Times','B','8');
        // $this->fpdf->Cell(28,8,'Brand','1',0,'C');
        // $this->fpdf->SetFillColor(178, 190, 181);
        // $this->fpdf->SetFont('Times','B','8');
        // $this->fpdf->Cell(28,8,'Model','1',0,'C');
        // $this->fpdf->SetFillColor(178, 190, 181);
        // $this->fpdf->SetFont('Times','B','8');
        // $this->fpdf->Cell(30,8,'Cargo or Passenger Capacity','1',0,'C');
        // $this->fpdf->SetFillColor(178, 190, 181);
        // $this->fpdf->SetFont('Times','B','8');
        // $this->fpdf->Cell(28,8,'Advance Tax Payable','1',0,'C');
        // $this->fpdf->SetFillColor(178, 190, 181);
        // $this->fpdf->Ln(8);

          

        // Taxpayer Details
        #$this->fpdf->SetFont('Times','B',8);
        #$this->fpdf->Text(10,30,'Taxpayer PIN:');
        #$this->fpdf->SetFont('Times','',8);
        #$this->fpdf->Text(30,30,$tPin);
        // $this->fpdf->Ln(20);

    //  $this->fpdf->SetFont('Times','B',8);
    //  $this->fpdf->Text(10,35,'Name and Address:');
    //  $this->fpdf->SetFont('Times','',8);
    //  $this->fpdf->Text(10,40,$tName);
    //  $this->fpdf->Text(10,45,'HANOVER, NAIROBI, Westlands District');
    //  $this->fpdf->Text(10,48,'PO Box:30264,');
    //  $this->fpdf->Text(10,51,'Postal Code:00100');

       #$this->fpdf->SetFont('Times','B',8);
       #$this->fpdf->Text(140,30,'Notice Date:');
       #$this->fpdf->SetFont('Times','',8);
       #$this->fpdf->Text(160,30,$dateCreated);

       #$this->fpdf->SetFont('Times','B',8);
       #$this->fpdf->Text(140,40,'Notice Number:');
        // $this->fpdf->Code39(80,40,'CODE 39',1,10);
        // $this->fpdf->Text(140,60,$code);        
        #$this->fpdf->SetFont('Times','',8);
        #$this->fpdf->Text(140,45,$noticeCode);
        // echo DNS1D::getBarcodeSVG('4445645656', 'PHARMA2T');

        //$this->fpdf->Ln(10);
        //$this->fpdf->Ln(20);
        
        // Application Information
        $this->fpdf->SetFont('Times','B','12');
        $this->fpdf->SetDrawColor(50,50,100);
        $this->fpdf->SetFillColor(178, 190, 181);
        $this->fpdf->Cell(180,8,"Section A: Application Information",1,1,'C',true);
        $this->fpdf->Ln(1);

        $date1 = date("d-m-Y");
        $date2 = date('H')+1;
        $date3 = date('i:s');    
        $date4 = date('Ymd');
        $date5 = date('is');
        $date = $date1.' '.$date2.':'.$date3;
        $reportNo = $date4.$date2.$date5; 
        
        $this->fpdf->SetFont('Times','B','8');
        $this->fpdf->Cell(50,8,'Acknowledgement Number ','1',0,'L');
        $this->fpdf->SetFont('Times','','8');
        $this->fpdf->Cell(130,8,'KRA'.$date4.hexdec(uniqid()),'1',1,'L');

        $this->fpdf->SetFont('Times','B','8');
        $this->fpdf->Cell(50,8,'Acknowledgement Date ','1',0,'L');
        $this->fpdf->SetFont('Times','','8');
        $this->fpdf->Cell(130,8,$dateUpdated,'1',1,'L');

        $this->fpdf->SetFont('Times','B','8');
        $this->fpdf->Cell(50,8,'Notice Type ','1',0,'L');
        $this->fpdf->SetFont('Times','','8');
        $this->fpdf->Cell(130,8, 'Vacated Assessment Notice','1',1,'L');

        $this->fpdf->SetFont('Times','B','8');
        $this->fpdf->Cell(50,8,'Assessment Number ','1',0,'L');
        $this->fpdf->SetFont('Times','','8');
        $this->fpdf->Cell(130,8,'KRA'.$date4.$assNumber,'1',1,'L');

        
        $this->fpdf->SetFont('Times','','8');
        $this->fpdf->Ln(10);

                
        // Objection Details
       $this->fpdf->SetFont('Times','B','12');
       $this->fpdf->SetDrawColor(50,50,100);
       $this->fpdf->SetFillColor(178, 190, 181);
       $this->fpdf->Cell(180,8,"Section B: TEST",1,1,'C',true);        
       $this->fpdf->Ln(1);

        $this->fpdf->SetFont('Times','B','8');
        $this->fpdf->SetDrawColor(50,50,100);
        $this->fpdf->SetFillColor(178, 190, 181);
        $this->fpdf->SetFont('Times','B','8');
        $this->fpdf->Cell(26.6,8,'Sr.No ','1',0,'C');       
        $this->fpdf->SetFont('Times','B','8');
        $this->fpdf->Cell(39.6,8,'Tax Obligation ','1',0,'C');
        $this->fpdf->SetFillColor(178, 190, 181);
        $this->fpdf->SetFont('Times','B','8');
        $this->fpdf->Cell(39.6,8,'Assessment Period ','1',0,'C');
        $this->fpdf->SetFillColor(178, 190, 181);
        $this->fpdf->SetFont('Times','B','8');
        $this->fpdf->Cell(34.6,8,'Assessment Amount','1',0,'C');
        $this->fpdf->SetFillColor(178, 190, 181);
        $this->fpdf->SetFont('Times','B','8');
        $this->fpdf->Cell(39.6,8,'Objected Amount ','1',0,'C');
        $this->fpdf->Ln(8);
        

       $this->fpdf->SetFont('Times','','8');
       $this->fpdf->SetFillColor(255, 255, 255);
       $this->fpdf->Cell(26.6,8,$caseId,'1',0,'J',false);       
       $this->fpdf->SetFont('Times','','8');
       $this->fpdf->Cell(39.6,8,$taxHead,'1',0,'C');
       $this->fpdf->SetFillColor(178, 190, 181);
       $this->fpdf->SetFont('Times','','8');
       $this->fpdf->Cell(39.6,8,$assPeriod,'1',0,'C');
       $this->fpdf->SetFillColor(178, 190, 181);
       $this->fpdf->SetFont('Times','','8');
       $this->fpdf->Cell(34.6,8,$assAmount,'1',0,'C');
       $this->fpdf->SetFillColor(178, 190, 181);
       $this->fpdf->SetFont('Times','','8');
       $this->fpdf->Cell(39.6,8, $objAmount,'1',0,'C');
       $this->fpdf->SetFillColor(178, 190, 181);
       $this->fpdf->Ln(8);
       $this->fpdf->SetFillColor(178, 190, 181);
       
       $this->fpdf->Ln(12); 

       $this->fpdf->SetFont('Times','B','8');
       $this->fpdf->Cell(59.6,8,'Status ','1',0,'C');
       $this->fpdf->SetFillColor(178, 190, 181);
       $this->fpdf->SetFont('Times','B','8');
       $this->fpdf->Cell(37.6,8,'Accepted Amount ','1',0,'C');
       $this->fpdf->SetFillColor(178, 190, 181);
       $this->fpdf->Ln(8);
       
       $this->fpdf->SetFont('Times','','8');
       $this->fpdf->Cell(59.6,8, $status,'1',0,'C');
       $this->fpdf->SetFillColor(178, 190, 181);
       $this->fpdf->Cell(37.6,8, $objAmount,'1',0,'C');
       $this->fpdf->SetFillColor(178, 190, 181);
   
        #$this->fpdf->Ln(15);
         // Official KRA representative
        #$this->fpdf->SetFont('Times','B','12');
        #$this->fpdf->SetDrawColor(50,50,100);
        #$this->fpdf->SetFillColor(178, 190, 181);
        #$this->fpdf->Cell(180,8,"Section C:  Official KRA Representative",1,1,'C',true);
        #$this->fpdf->Ln(1);

        #$this->fpdf->SetFont('Times','B','8');
        #$this->fpdf->Cell(100,8,'Name and Designation of Authorized Signatory','1',0,'L');
        #$this->fpdf->SetFont('Times','','8');
        #$this->fpdf->Cell(80,8,$createdBy,'1',1,'L');

        #$this->fpdf->SetFont('Times','B','8');
        #$this->fpdf->Cell(100,8,'Contact Number ','1',0,'L');
        #$this->fpdf->SetFont('Times','','8');
        #$this->fpdf->Cell(80,8,'0712345679','1',1,'L');

        // footer
        // $date1 = date("d-m-Y");
        // $date2 = date('H')+1;
        // $date3 = date('i:s');    
        // $date4 = date('Ymd');
        // $date5 = date('is');
        // $date = $date1.' '.$date2.':'.$date3;
        // $reportNo = $date4.$date2.$date5; 
     
        
        $this->fpdf->SetY(-15);
        $this->fpdf->SetAutoPageBreak(false);
        $this->fpdf->SetFont('Times','B','8');
        // $this->fpdf->Cell(90,10,'Disclaimer : This is a system generated notice and does not require signature ',0,0,'C');  
        $this->fpdf->Ln(3);
        // $this->fpdf->Cell(170,5,'________________________________________________________________________________________________________________________','',1,'C');  

        $this->fpdf->Image(public_path().'/images/insight.png',10,188,20,0,'PNG');  
        $this->fpdf->SetFont('Times','',8);
        $this->fpdf->Text(65,193,'Printed on ' .$date. ' by ' .'Admin'. ' ');
        $this->fpdf->Ln(1);
        $this->fpdf->SetFont('Times','B',8);
        $this->fpdf->Text(145,193,'Tulipe Ushuru Tujitegemee!');
        // generate pdf
        if ($action=='download'){
            $this->fpdf->Output('DTD/'.$tPin.'/decision'.'.pdf','D');
        }
        else if($action=='print')
        {
            $this->fpdf->Output();

        }
        exit;
        // return public_path().'/images';
    }

}

<?php

namespace App\Pdfs;

use App\Pdfs\MasterPage;
use DateTime;

class ContactTaxpayerPdf
{

  protected  $fontFamily = 'Times';
  protected $fontSize = 12;
  protected $fpdf;

  public function __construct()
  {

    $fontFamily = 'Times';
    $fontSize = 12;

    $this->fontFamily = $fontFamily;
    $this->fontSize = $fontSize;
    $this->fpdf = new MasterPage('CONFIDENTIAL', $fontFamily, $fontSize);
  }

  function writeLink($URL, $txt)
  {
    // Put a hyperlink
    $this->fpdf->SetTextColor(0, 0, 255);
    $this->fpdf->SetFont('', 'U');
    $this->fpdf->Write(5, $txt, $URL);
    $this->fpdf->SetFont('', '');
    $this->fpdf->SetTextColor(0);
  }

  function getOutput($cplData, $periods, $savePath)
  {

    // page setup
    $leftMargin = 30;
    $yMargin = 20;
    $rightMargin = $yMargin;
    $xMargins = $leftMargin + $rightMargin;
    $contentWidth = $this->fpdf->GetPageWidth() - $xMargins;

    $fontFamily = $this->fontFamily;
    $fontSize = $this->fontSize;

    // $refNo = $cplData['REF_NO'];
    $refNo = 'REF/NO/001';
    $taxpayerName = $cplData['TAXPAYER_NAME'];
    $taxpayerEmail = $cplData['TP_EMAIL'];
    $taxpayerPin = $cplData['PIN_NO'];


    $this->fpdf->SetMargins($leftMargin, $yMargin, $rightMargin);
    $this->fpdf->AddPage("P", "A4");
    $this->fpdf->SetFont($fontFamily, 'B', 16);
    $this->fpdf->SetDrawColor(25, 25, 25);
    $this->fpdf->SetFillColor(178, 190, 181);


    // start drawing
    $this->fpdf->SetFont($fontFamily, 'B', $fontSize, 'R');
    $this->fpdf->Cell(0, 5, 'DOMESTIC TAX DEPARTMENT', 0, 1, 'C');
    $this->fpdf->Ln(10);

    $this->fpdf->SetFont($fontFamily, 'B', $fontSize);
    $this->fpdf->Cell(0, 5, $refNo, 0, 1);

    $date = date("l, d F Y");
    $this->fpdf->SetFont($fontFamily, '', $fontSize);
    $this->fpdf->Cell(0, 5, $date, 0, 1);

    $this->fpdf->Ln();

    // address
    $this->fpdf->SetFont($fontFamily, 'B', $fontSize);
    $this->fpdf->Cell(50, 5, 'Name', 0, 0);
    $this->fpdf->Cell(100, 5, $taxpayerName, 0, 1);

    $this->fpdf->Cell(50, 5, 'KRA PIN', 0, 0);
    $this->fpdf->Cell(100, 5, $taxpayerPin, 0, 1);

    $this->fpdf->Cell(50, 5, 'Email', 0, 0);
    $this->fpdf->Cell(100, 5, $taxpayerEmail, 0, 1);

    $this->fpdf->Ln();

    // salutation
    $this->fpdf->SetFont($fontFamily, '', $fontSize);
    $this->fpdf->Cell(0, 5, 'Dear sir/madam', 0, 1);
    $this->fpdf->Ln();

    $obligationName = $cplData['OBLIGATION_NAME'];
    $this->fpdf->SetFont($fontFamily, 'BU', $fontSize);
    $this->fpdf->Write(5, $obligationName);
    $this->fpdf->Ln(10);

    // ===================================================
    // Entries table
    // ===================================================

    $col1W = (3.5 / 8) * $contentWidth;
    $col2W = (1.5 / 8) * $contentWidth;
    $col3W = (1.5 / 8) * $contentWidth;
    $col4W = (1.5 / 8) * $contentWidth;

    $this->fpdf->SetFont($fontFamily, 'B', 10);
    $this->fpdf->SetFillColor(217, 217, 217);

    // headers
    $this->fpdf->Cell($col1W, 6, 'Obligation', 1, 0, 'L', true);
    $this->fpdf->Cell($col2W, 6, 'Amount', 1, 0, 'L', true);
    $this->fpdf->Cell($col3W, 6, 'From', 1, 0, 'L', true);
    $this->fpdf->Cell($col3W, 6, 'To', 1, 1, 'L', true);

    // revert bold font to regular
    $this->fpdf->SetFont($fontFamily, '', 11);

    // rows data
    for ($i = 0; $i < count($periods); $i++) {
    $row = $periods[$i];
    $fromDate  = (new DateTime($row['YEAR_FROM']))->format('d-m-Y');
    $toDate  = (new DateTime($row['YEAR_TO']))->format('d-m-Y');

    $maxObName = 38;
    $obName = $row['OBLIGATION_NAME'];

    if (strlen($obName) >= $maxObName){
      $obName = substr($obName,0,$maxObName) .'...';
    }

    $this->fpdf->Cell($col1W, 6,  $obName, 1, 0, 'L');
    $this->fpdf->Cell($col2W, 6,  number_format($row['PRELIM_REPORT_AMOUNT'],2), 1, 0, 'L');
    $this->fpdf->Cell($col3W, 6, $fromDate, 1, 0, 'L');
    $this->fpdf->Cell($col4W, 6, $toDate, 1, 1, 'L');
    }
    $this->fpdf->Ln(10);

    // ===================================================
    // End of entries table
    // ===================================================


    $this->fpdf->SetFont($fontFamily, '', $fontSize);
    $this->fpdf->Write(5, 'The amounts listed  are due and payable immediately. You are therefore required to make the payments or  provide a valid explanation for the above discrepancies.');
    $this->fpdf->Ln(10);
    $amEmail = strtolower($cplData['AM_EMAIL']);
    $amName = $cplData['AM_NAME'];
    $this->fpdf->Write(5, 'Kindly channel all responses  through email: ');
    $this->writeLink("mailto::$amEmail", $amEmail);
    
    $this->fpdf->Ln(10);

    $this->fpdf->Cell(0, 5, 'Yours faithfully,', 0, 1);
    // $this->fpdf->Ln(10);
    $this->fpdf->SetFont($fontFamily, 'B', $fontSize);
    $this->fpdf->Cell(0, 5, $amName, 0, 1);
    $this->fpdf->SetFont($fontFamily, 'BU', $fontSize);
    $this->fpdf->Cell(0, 5, 'FOR: COMMISSIONER,DOMESTIC TAX DEPARTMENT', 0, 1);
    $this->fpdf->Ln();

    $this->fpdf->SetFont($fontFamily, 'B', $fontSize);
    $copyToLabel = 'Copy to:';
    $copyToLength = $this->fpdf->GetStringWidth($copyToLabel) + 5;
    $this->fpdf->Cell($copyToLength, 5, $copyToLabel, 0, 0);

    $copiedTo = "Deputy Commissioner-Cargo Monitoring & Scanning Division
Chief Manager, Enforcement Southern Region
Chief Manager, Southern Region (POPs)
Manager-Enforcement, Southern Region
Manager-Malaba OSBP
Manager-Enforcement, Western Region
";
    $copiedToWidth = $this->fpdf->GetPageWidth() - $copyToLength;
    $this->fpdf->SetFont($fontFamily, '', $fontSize);
    $this->fpdf->MultiCell($copiedToWidth, 5, $copiedTo, 0, 'L');


    if ($savePath) {
      $this->fpdf->Output('F', public_path() . $savePath);
    } else {
      $this->fpdf->Output();
      exit;
    }
  }
}
